<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;


class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() && $request->wantsJson() && !$request->has('simple')) {
            $proveedores = Proveedor::select('id', 'nombre', 'contacto', 'direccion', 'telefono', 'email');
            return DataTables::of($proveedores)
                ->addColumn('acciones', function($proveedor) {
                    return '
                        <div class="btn-group">
                            <button class="btn btn-info btn-sm ver-productos" data-id="'.$proveedor->id.'">
                                <i class="fas fa-box"></i>
                            </button>
                            <button class="btn btn-primary btn-sm editar-proveedor" data-id="'.$proveedor->id.'">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm eliminar-proveedor" data-id="'.$proveedor->id.'">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button class="btn btn-success btn-sm realizar-compra" data-id="'.$proveedor->id.'">
                                <i class="fas fa-shopping-cart"></i> Realizar Compra
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }
        
        if ($request->ajax() && $request->wantsJson()) {
            return response()->json(Proveedor::select('id', 'nombre')->get());
        }

        return view('proveedores.proveedores');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'contacto' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
            'email' => 'required|email|unique:proveedores,email',
        ]);

        Proveedor::create($request->all());
        return response()->json(['success' => 'Proveedor creado correctamente']);
    }

    public function show($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        return response()->json(['success' => true, 'data' => $proveedor]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required',
            'contacto' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
            'email' => 'required|email|unique:proveedores,email,'.$id,
        ]);

        $proveedor = Proveedor::findOrFail($id);
        $proveedor->update($request->all());
        return response()->json(['success' => 'Proveedor actualizado correctamente']);
    }

    public function destroy($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->delete();
        return response()->json(['success' => 'Proveedor eliminado correctamente']);
    }

    public function getProductos($id)
    {
        $proveedor = Proveedor::with('productos.categoria')->findOrFail($id);
        return response()->json([
            'success' => true,
            'productos' => $proveedor->productos
        ]);
    }

    public function createPedido($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        return response()->json(['success' => true, 'proveedor' => $proveedor]);
    }

    public function lista()
    {
        try {
            $proveedores = Proveedor::select('id', 'nombre')->get();
            \Log::info('Proveedores cargados:', $proveedores->toArray());
            return response()->json($proveedores);
        } catch (\Exception $e) {
            \Log::error('Error al cargar proveedores: ' . $e->getMessage());
            return response()->json(['error' => 'Error al cargar proveedores'], 500);
        }
    }

    public function getLista()
    {
        return $this->lista();
    }

    public function generarReporte($id)
    {
        try {
            $proveedor = Proveedor::with('productos')->findOrFail($id);

            $productos = $proveedor->productos->map(function($producto) {
                return [
                    'nombre' => $producto->nombre,
                    'precio_compra' => $producto->precio_compra,
                    'stock_inicial' => $producto->stock_inicial,
                    'stock_actual' => $producto->stock_actual,
                    'caducidad' => $producto->caducidad,
                    'fecha_creacion' => $producto->created_at,
                ];
            });

            $total = $productos->sum(function($producto) {
                return $producto['precio_compra'] * $producto['stock_inicial'];
            });

            $pdf = Pdf::loadView('reportes.reporte_proveedores', [
                'proveedor' => [
                    'nombre' => $proveedor->nombre,
                    'contacto' => $proveedor->contacto,
                    'direccion' => $proveedor->direccion,
                ],
                'productos' => $productos,
                'total' => $total
            ]);

            return $pdf->download('reporte-proveedor-' . $proveedor->id . '.pdf');

        } catch (\Exception $e) {
            Log::error('Error al generar reporte de proveedor: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al generar el reporte: ' . $e->getMessage()
            ], 500);
        }
    }
}