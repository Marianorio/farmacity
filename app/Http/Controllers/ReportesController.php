<?php

namespace App\Http\Controllers;

use App\Models\Reporte;
use App\Models\ObraSocial;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('reportes.reportes');
    }

    public function getObrasSociales()
    {
        try {
            $obrasSociales = \App\Models\ObraSocial::select('id', 'nombre')->get();
            return response()->json($obrasSociales);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getProveedores()
    {
        try {
            $proveedores = \App\Models\Proveedor::select('id', 'nombre')->get();
            return response()->json($proveedores);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function generarReporte(Request $request)
    {
        try {
            $fechaInicio = $request->fecha_inicio;
            $fechaFin = $request->fecha_fin;
            $obraSocialId = $request->obra_social_id;

            // Obtener la obra social con sus productos
            if ($obraSocialId) {
                $obraSocial = \App\Models\ObraSocial::find($obraSocialId);
                
                if ($obraSocial) {
                    // Obtener productos con sus coberturas y obra social
                    $productos = \DB::table('productos AS p')
                        ->join('productos_obras_sociales AS pos', 'p.id', '=', 'pos.id_producto')
                        ->join('obras_sociales AS os', 'pos.id_obra_social', '=', 'os.id')
                        ->where('os.id', $obraSocialId)
                        ->select(
                            'p.nombre',
                            'p.precio_venta',
                            'pos.descuento',
                            'os.nombre as obra_social_nombre',
                            'os.cuit'
                        )
                        ->get();

                    $productosFormateados = $productos->map(function($item) {
                        $precioConDescuento = $item->precio_venta - ($item->precio_venta * ($item->descuento / 100));
                        return [
                            'nombre' => $item->nombre,
                            'precio_original' => number_format($item->precio_venta, 2),
                            'descuento' => $item->descuento,
                            'precio_con_descuento' => number_format($precioConDescuento, 2),
                            'obra_social' => $item->obra_social_nombre
                        ];
                    });

                    $obraSocialData = [
                        'nombre' => $obraSocial->nombre,
                        'cuit' => $obraSocial->cuit,
                        'productos' => $productosFormateados
                    ];
                }
            }

            $datos = [
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'obra_social' => $obraSocialData ?? [
                    'nombre' => 'Todas las Obras Sociales',
                    'productos' => []
                ]
            ];

            $pdf = Pdf::loadView('reportes.reporte_obrasocial', $datos);
            return $pdf->stream('reporte_obra_social.pdf');

        } catch (\Exception $e) {
            \Log::error('Error en generarReporte: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function generarReporteProveedor(Request $request)
    {
        try {
            $proveedorId = $request->proveedor_id;

            // Obtener datos del proveedor
            $proveedor = \DB::table('proveedores')
                ->select('nombre', 'contacto', 'direccion')
                ->where('id', $proveedorId)
                ->first();

            // Obtener productos del proveedor
            $productos = \DB::table('productos')
                ->where('id_proveedor', $proveedorId)
                ->select(
                    'nombre',
                    'precio_compra',
                    'stock_inicial',
                    'stock_actual',
                    'caducidad',
                    'fecha_creacion'
                )
                ->get();

            // Calcular el total
            $total = $productos->sum(function($producto) {
                return $producto->precio_compra * $producto->stock_inicial;
            });

            $datos = [
                'proveedor' => $proveedor,
                'productos' => $productos,
                'total' => $total
            ];

            $pdf = Pdf::loadView('reportes.reporte_proveedores', $datos);
            return $pdf->stream('reporte_proveedor.pdf');

        } catch (\Exception $e) {
            \Log::error('Error en generarReporteProveedor: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
