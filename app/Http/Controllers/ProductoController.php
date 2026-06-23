<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Proveedor;
use App\Models\Categoria;

class ProductoController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:productos')->only(['index', 'show', 'store', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $productos = Producto::with(['categoria', 'proveedor'])
                    ->select('productos.*');

                return datatables()->of($productos)
                    ->addColumn('categoria.nombre', function($producto) {
                        return $producto->categoria ? $producto->categoria->nombre : 'Sin categoría';
                    })
                    ->addColumn('proveedor.nombre', function($producto) {
                        return $producto->proveedor ? $producto->proveedor->nombre : 'Sin proveedor';
                    })
                    ->toJson();
            }

            return view('productos.productos');
        } catch (\Exception $e) {
            \Log::error('Error en ProductoController@index: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'draw' => $request->input('draw', 1),
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => 'Error al cargar los productos'
                ]);
            }
            
            return back()->with('error', 'Error al cargar los productos: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validación básica
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
                'precio_compra' => 'required|numeric|min:0',
                'precio_venta' => 'required|numeric|min:0',
                'stock_inicial' => 'required|integer|min:0',
                'stock_actual' => 'required|integer|min:0',
                'stock_minimo' => 'required|integer|min:0',
                'id_categoria' => 'required|exists:categorias,id',
                'id_proveedor' => 'required|exists:proveedores,id',
                'caducidad' => 'nullable|date'
            ]);

            // Crear el producto
            $producto = Producto::create($validatedData);

            // Procesar coberturas
            if ($request->has('coberturas') && !empty($request->coberturas)) {
                $coberturas = json_decode($request->coberturas, true);
                
                if (is_array($coberturas)) {
                    $coberturasData = [];
                    foreach ($coberturas as $cobertura) {
                        if (isset($cobertura['obraSocialId'], $cobertura['porcentaje'])) {
                            $coberturasData[$cobertura['obraSocialId']] = [
                                'descuento' => $cobertura['porcentaje']
                            ];
                        }
                    }
                    
                    if (!empty($coberturasData)) {
                        try {
                            $producto->obrasSociales()->attach($coberturasData);
                        } catch (\Exception $e) {
                            // Si hay un error de duplicado, lo manejamos específicamente
                            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                                throw new \Exception('Ya existe una cobertura para esta obra social en este producto.');
                            }
                            throw $e;
                        }
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Producto guardado exitosamente',
                'data' => $producto->load(['categoria', 'proveedor', 'obrasSociales'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al guardar producto: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el producto: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $producto = Producto::with(['categoria', 'proveedor', 'obrasSociales'])
                ->findOrFail($id);
            
            $coberturas = $producto->obrasSociales->map(function($obra) {
                return [
                    'obraSocialId' => $obra->id,
                    'nombre' => $obra->nombre,
                    'porcentaje' => $obra->pivot->descuento ?? 0
                ];
            })->toArray();
            
            return response()->json([
                'success' => true,
                'producto' => $producto,
                'coberturas' => $coberturas
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en ProductoController@show: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $producto = Producto::findOrFail($id);
            $producto->update($request->except('coberturas'));

            // Actualizar coberturas
            if ($request->has('coberturas')) {
                $coberturas = json_decode($request->coberturas, true);
                $coberturasData = [];
                
                foreach ($coberturas as $obraSocialId => $descuento) {
                    $coberturasData[$obraSocialId] = ['descuento' => $descuento];
                }
                
                $producto->obrasSociales()->sync($coberturasData);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Producto actualizado exitosamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el producto: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $producto = Producto::findOrFail($id);
            $producto->obrasSociales()->detach();
            $producto->delete();

            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el producto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener productos con stock menor al stock mínimo
     */
    public function productsLowStock(Request $request)
    {
        try {
            if ($request->ajax()) {
                $productos = Producto::with(['categoria', 'proveedor'])
                    ->whereRaw('stock_actual < stock_minimo')
                    ->select('productos.*');

                return datatables()->of($productos)
                    ->addColumn('categoria.nombre', function($producto) {
                        return $producto->categoria ? $producto->categoria->nombre : 'Sin categoría';
                    })
                    ->addColumn('proveedor.nombre', function($producto) {
                        return $producto->proveedor ? $producto->proveedor->nombre : 'Sin proveedor';
                    })
                    ->toJson();
            }

            return view('productos.productos');
        } catch (\Exception $e) {
            \Log::error('Error en ProductoController@productsLowStock: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'draw' => $request->input('draw', 1),
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => 'Error al cargar los productos'
                ]);
            }
            
            return back()->with('error', 'Error al cargar productos con bajo stock');
        }
    }

    /**
     * Obtener productos próximos a vencer (próximos 30 días) e incluir ya vencidos
     */
    public function productsExpiringSoon(Request $request)
    {
        try {
            if ($request->ajax()) {
                $productos = Producto::with(['categoria', 'proveedor'])
                    ->whereNotNull('caducidad')
                    ->where('caducidad', '<=', \Carbon\Carbon::now()->addDays(30))
                    ->select('productos.*');

                return datatables()->of($productos)
                    ->addColumn('categoria.nombre', function($producto) {
                        return $producto->categoria ? $producto->categoria->nombre : 'Sin categoría';
                    })
                    ->addColumn('proveedor.nombre', function($producto) {
                        return $producto->proveedor ? $producto->proveedor->nombre : 'Sin proveedor';
                    })
                    ->toJson();
            }

            return view('productos.productos');
        } catch (\Exception $e) {
            \Log::error('Error en ProductoController@productsExpiringSoon: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'draw' => $request->input('draw', 1),
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => 'Error al cargar los productos'
                ]);
            }
            
            return back()->with('error', 'Error al cargar productos por vencer');
        }
    }
}

