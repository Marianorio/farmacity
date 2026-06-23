<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\ObraSocial;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Schema;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VentasController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:ventas');
    }

    // Método para mostrar la vista de ventas
    public function index()
    {
        $ventas = Venta::all();
        $productos = Producto::select('id', 'nombre', 'precio_venta as precio', 'stock_actual')->get();
        $obrasSociales = ObraSocial::all();

        return view('ventas.ventas', compact('ventas', 'productos', 'obrasSociales'));
    }

    // Método para almacenar una nueva venta
    public function store(Request $request)
    {
        try {
            // Validar entrada
            $request->validate([
                'productos' => 'required|array|min:1',
                'productos.*.id' => 'required|exists:productos,id',
                'productos.*.cantidad' => 'required|integer|min:1',
                'productos.*.precio' => 'required|numeric|min:0',
                'subtotal' => 'required|numeric|min:0',
                'total' => 'required|numeric|min:0',
                'metodo_pago' => 'required|string',
            ]);

            DB::beginTransaction();
            
            // Verificar que el usuario existe
            $userId = auth()->id();
            $userExists = DB::table('users')->where('id', $userId)->exists();
            
            if (!$userExists) {
                throw new \Exception('El usuario autenticado no existe en la base de datos');
            }

            // Obtener el próximo número de cliente
            $ultimaVenta = Venta::orderBy('id', 'desc')->first();
            $numeroCliente = str_pad(($ultimaVenta ? intval($ultimaVenta->numero_cliente) + 1 : 1), 5, '0', STR_PAD_LEFT);

            // Crear la venta
            $venta = new Venta();
            $venta->numero_cliente = $numeroCliente;
            $venta->fecha = now();
            $venta->subtotal = $request->subtotal;
            $venta->impuestos = $request->impuestos;
            $venta->descuento = $request->descuento;
            $venta->total = $request->total;
            $venta->id_usuario = $userId;
            $venta->id_obra_social = $request->id_obra_social;
            $venta->codigo_validacion = $request->codigo_validacion;
            $venta->estado = 'ACTIVA';
            $venta->save();

            // Procesar los detalles de la venta
            foreach ($request->productos as $item) {
                // Asegurar que cantidad no excede stock
                $productoCheck = Producto::find($item['id']);
                if (!$productoCheck) {
                    throw new \Exception('Producto no encontrado: ' . $item['id']);
                }
                if ($productoCheck->stock_actual < $item['cantidad']) {
                    throw new \Exception('Stock insuficiente para el producto: ' . $productoCheck->nombre);
                }
                $detalle = new DetalleVenta([
                    'id_venta' => $venta->id,
                    'id_producto' => $item['id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'descuento' => $item['descuento'] ?? 0,
                    'subtotal' => $item['cantidad'] * $item['precio'] * (1 - ($item['descuento'] ?? 0) / 100)
                ]);
                $venta->detalles()->save($detalle);

                // Actualizar stock
                $producto = Producto::findOrFail($item['id']);
                $producto->stock_actual -= $item['cantidad'];
                $producto->save();
            }

            DB::commit();

            // Auditoría simple
            Log::info('Venta registrada', [
                'venta_id' => $venta->id,
                'user_id' => $userId,
                'total' => $venta->total,
                'productos_count' => count($request->productos)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Venta registrada exitosamente',
                'venta_id' => $venta->id
            ]);

        } catch (\Illuminate\Validation\ValidationException $ve) {
            DB::rollBack();
            $errors = $ve->validator->errors()->messages();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $errors
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en venta:', [
                'message' => $e->getMessage(),
                'user_id' => auth()->id(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la venta: ' . $e->getMessage()
            ], 500);
        }
    }

    // Método para mostrar una venta específica
    public function show($id)
    {
        try {
            $venta = Venta::with([
                'detalles.producto',
                'obraSocial',
                'usuario',
                'usuarioAnulacion'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'venta' => $venta,
                'detalles' => $venta->detalles,
                'cajero' => $venta->usuario ? [
                    'id' => $venta->usuario->id,
                    'nombre' => $venta->usuario->name
                ] : [
                    'id' => null,
                    'nombre' => 'Usuario no disponible'
                ],
                'anulacion' => $venta->estado === 'ANULADA' && $venta->usuarioAnulacion ? [
                    'usuario' => $venta->usuarioAnulacion->name,
                    'fecha' => $venta->fecha_anulacion,
                    'motivo' => $venta->motivo_anulacion
                ] : null
            ]);

        } catch (\Exception $e) {
            Log::error('Error en VentasController@show:', [
                'error' => $e->getMessage(),
                'venta_id' => $id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la venta: ' . $e->getMessage()
            ], 500);
        }
    }

    // Método para eliminar una venta
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $venta = Venta::with('detalles')->findOrFail($id);
            
            // Restaurar stock de productos
            foreach ($venta->detalles as $detalle) {
                $producto = Producto::findOrFail($detalle->id_producto);
                $producto->stock_actual += $detalle->cantidad;
                $producto->save();
            }
            
            $venta->delete();
            
            DB::commit();
            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function generarPDF($id)
    {
        try {
            $venta = Venta::with([
                'detalles.producto',
                'obraSocial',
                'usuario'
            ])->findOrFail($id);

            $pdf = PDF::loadView('ventas.ticket', [
                'venta' => $venta,
                'detalles' => $venta->detalles
            ]);

            $pdf->setPaper([0, 0, 302.36, 1000], 'portrait');

            return $pdf->stream('ticket-' . $venta->id . '.pdf');

        } catch (\Exception $e) {
            Log::error('Error al generar PDF: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al generar el ticket: ' . $e->getMessage()
            ], 500);
        }
    }

    // Método para verificar la cobertura
    public function verificarCobertura(Request $request)
    {
        try {
            $productoId = $request->input('producto_id');
            $codigoValidacion = $request->input('codigo_validacion');

            // Buscar la obra social por código de validación
            $obraSocial = ObraSocial::where('codigo_validacion', $codigoValidacion)->first();
            
            if (!$obraSocial) {
                return response()->json([
                    'cubierto' => false, 
                    'message' => 'Código de validación inválido'
                ]);
            }

            // Buscar la cobertura específica para este producto y obra social
            $cobertura = DB::table('productos_obras_sociales')
                ->where('id_producto', $productoId)
                ->where('id_obra_social', $obraSocial->id)
                ->first();

            if ($cobertura) {
                return response()->json([
                    'cubierto' => true,
                    'descuento' => $cobertura->descuento,
                    'obra_social_id' => $obraSocial->id,
                    'obra_social_nombre' => $obraSocial->nombre
                ]);
            }

            return response()->json([
                'cubierto' => false,
                'message' => 'Producto no cubierto por la obra social'
            ]);
        } catch (\Exception $e) {
            Log::error('Error en verificación de cobertura: ' . $e->getMessage());
            return response()->json([
                'cubierto' => false,
                'message' => 'Error al verificar la cobertura'
            ], 500);
        }
    }

    public function buscarProductos(Request $request)
    {
        try {
            $query = $request->input('query');
            
            $productos = Producto::where('nombre', 'LIKE', "%{$query}%")
                ->where('stock_actual', '>', 0)
                ->select('id', 'nombre', 'precio_venta as precio', 'stock_actual')
                ->limit(10)
                ->get();
            
            return response()->json($productos);
            
        } catch (\Exception $e) {
            Log::error('Error en búsqueda de productos: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al buscar productos'
            ], 500);
        }
    }

    public function validarCodigo(Request $request)
    {
        try {
            $request->validate([
                'codigo' => 'required|string',
                'id_obra_social' => 'required|exists:obras_sociales,id'
            ]);

            // Aquí puedes agregar la lógica específica de validación
            // Por ejemplo, verificar en una tabla de códigos válidos
            
            // Por ahora, simulamos una validación básica
            $codigoValido = true; // Aquí irá tu lógica real de validación

            if ($codigoValido) {
                return response()->json([
                    'valid' => true,
                    'message' => 'Código válido'
                ]);
            }

            return response()->json([
                'valid' => false,
                'message' => 'Código inválido'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al validar código: ' . $e->getMessage());
            return response()->json([
                'valid' => false,
                'message' => 'Error al validar el código: ' . $e->getMessage()
            ], 500);
        }
    }

    public function proximoNumeroCliente()
    {
        try {
            $ultimaVenta = Venta::orderBy('numero_cliente', 'desc')->first();
            $numeroCliente = $ultimaVenta ? intval($ultimaVenta->numero_cliente) + 1 : 1;
            
            return response()->json([
                'success' => true,
                'numero_cliente' => str_pad($numeroCliente, 5, '0', STR_PAD_LEFT)
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener número de cliente: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener número de cliente'
            ], 500);
        }
    }

    public function proximoNumeroVenta()
    {
        try {
            $ultimaVenta = Venta::orderBy('id', 'desc')->first();
            $numeroVenta = $ultimaVenta ? intval($ultimaVenta->id) + 1 : 1;
            
            return response()->json([
                'success' => true,
                'numero_venta' => str_pad($numeroVenta, 5, '0', STR_PAD_LEFT)
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener número de venta: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener número de venta'
            ], 500);
        }
    }

    // Agregar método para anular venta
    public function anular(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $venta = Venta::findOrFail($id);

            // Verificar si la venta ya está anulada
            if ($venta->estado === 'ANULADA') {
                throw new \Exception('La venta ya se encuentra anulada');
            }

            // Restaurar stock de productos
            foreach ($venta->detalles as $detalle) {
                $producto = Producto::findOrFail($detalle->id_producto);
                $producto->stock_actual += $detalle->cantidad;
                $producto->save();
            }

            // Anular la venta
            $venta->estado = 'ANULADA';
            $venta->fecha_anulacion = now();
            $venta->id_usuario_anulacion = auth()->id();
            $venta->motivo_anulacion = $request->input('motivo_anulacion');
            $venta->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Venta anulada exitosamente'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error al anular venta:', [
                'message' => $e->getMessage(),
                'venta_id' => $id,
                'user_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al anular la venta: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generarReporte(Request $request)
    {
        try {
            $fechaInicio = $request->get('fechaInicio');
            $fechaFin = $request->get('fechaFin');

            if (!$fechaInicio || !$fechaFin) {
                throw new \Exception('Las fechas son requeridas');
            }

            // Convertir fechas a objetos Carbon
            $fechaInicio = \Carbon\Carbon::parse($fechaInicio)->startOfDay();
            $fechaFin = \Carbon\Carbon::parse($fechaFin)->endOfDay();

            $ventas = Venta::query()
                ->whereDate('fecha', '>=', $fechaInicio)
                ->whereDate('fecha', '<=', $fechaFin)
                ->with(['usuario', 'obraSocial', 'usuarioAnulacion'])
                ->orderBy('fecha', 'desc')
                ->get();

            if ($ventas->isEmpty()) {
                throw new \Exception('No hay ventas en el período seleccionado');
            }

            // Calcular totales
            $totalActivas = $ventas->where('estado', 'ACTIVA')->sum('total');
            $totalAnuladas = $ventas->where('estado', 'ANULADA')->sum('total');
            $cantidadActivas = $ventas->where('estado', 'ACTIVA')->count();
            $cantidadAnuladas = $ventas->where('estado', 'ANULADA')->count();

            // Generar el PDF con todas las variables necesarias
            $pdf = PDF::loadView('ventas.reporte-pdf', [
                'ventas' => $ventas,
                'totalActivas' => $totalActivas,
                'totalAnuladas' => $totalAnuladas,
                'cantidadActivas' => $cantidadActivas,
                'cantidadAnuladas' => $cantidadAnuladas,
                'fechaInicio' => $fechaInicio,
                'fechaFin' => $fechaFin
            ]);

            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('reporte-ventas.pdf');

        } catch (\Exception $e) {
            \Log::error('Error en generación de reporte de ventas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al generar el reporte: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generarReportePDF(Request $request)
    {
        try {
            $fechaInicio = $request->get('fecha_inicio');
            $fechaFin = $request->get('fecha_fin');

            // Validar fechas
            if (!$fechaInicio || !$fechaFin) {
                throw new \Exception('Las fechas son requeridas');
            }

            // Convertir fechas a objetos Carbon
            $fechaInicio = \Carbon\Carbon::parse($fechaInicio)->startOfDay();
            $fechaFin = \Carbon\Carbon::parse($fechaFin)->endOfDay();

            // Obtener todas las ventas del período, incluyendo anuladas
            $ventas = Venta::query()
                ->whereDate('fecha', '>=', $fechaInicio)
                ->whereDate('fecha', '<=', $fechaFin)
                ->with(['usuario', 'obraSocial', 'usuarioAnulacion'])
                ->orderBy('fecha', 'desc')
                ->get();

            if ($ventas->isEmpty()) {
                throw new \Exception('No hay ventas en el período seleccionado');
            }

            // Calcular totales separados
            $totalActivas = $ventas->where('estado', 'ACTIVA')->sum('total');
            $totalAnuladas = $ventas->where('estado', 'ANULADA')->sum('total');
            $cantidadActivas = $ventas->where('estado', 'ACTIVA')->count();
            $cantidadAnuladas = $ventas->where('estado', 'ANULADA')->count();

            // Generar el PDF con la información adicional
            $pdf = PDF::loadView('ventas.reporte-pdf', [
                'ventas' => $ventas,
                'totalActivas' => $totalActivas,
                'totalAnuladas' => $totalAnuladas,
                'cantidadActivas' => $cantidadActivas,
                'cantidadAnuladas' => $cantidadAnuladas,
                'fechaInicio' => $fechaInicio,
                'fechaFin' => $fechaFin
            ]);

            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('reporte-ventas.pdf');

        } catch (\Exception $e) {
            \Log::error('Error al generar PDF del reporte: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al generar el reporte: ' . $e->getMessage()
            ], 500);
        }
    }

    // Método para obtener datos del gráfico de ventas por mes
    public function getChartData()
    {
        try {
            // Obtener los últimos 12 meses de ventas (COMPLETADAS o ACTIVAS)
            $ventas = Venta::selectRaw('EXTRACT(MONTH FROM fecha) as mes, EXTRACT(YEAR FROM fecha) as ano, SUM(total) as total')
                ->whereIn('estado', ['COMPLETADA', 'ACTIVA'])
                ->where('fecha', '>=', now()->subMonths(11)->startOfMonth())
                ->groupBy('ano', 'mes')
                ->orderBy('ano')
                ->orderBy('mes')
                ->get();

            // Nombres de meses en español
            $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                      'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

            // Preparar array con todos los meses del último año
            $labels = [];
            $datos = [];
            $hoy = now();

            for ($i = 11; $i >= 0; $i--) {
                $fecha = $hoy->copy()->subMonths($i);
                $mes = $fecha->month;
                $ano = $fecha->year;
                $labels[] = $meses[$mes - 1] . ' ' . $ano;

                // Buscar si hay datos para este mes
                $venta = $ventas->firstWhere(function($item) use ($mes, $ano) {
                    return $item->mes == $mes && $item->ano == $ano;
                });

                $datos[] = $venta ? round($venta->total, 2) : 0;
            }

            return response()->json([
                'labels' => $labels,
                'data' => $datos,
                'success' => true
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al obtener datos del gráfico: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener datos del gráfico'
            ], 500);
        }
    }
}