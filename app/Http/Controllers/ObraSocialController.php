<?php

namespace App\Http\Controllers;

use App\Models\ObraSocial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Schema;
use Barryvdh\DomPDF\Facade\Pdf;

class ObraSocialController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return $this->getData();
        }
        
        return view('obras_sociales.obras_sociales');
    }

    public function getData()
    {
        try {
            $obras_sociales = ObraSocial::select([
                'id',
                'nombre',
                'cuit',
                'fecha_convenio',
                'fecha_vencimiento_convenio'
            ])->get();

            return response()->json([
                'data' => $obras_sociales
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'data' => [],
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:100',
                'cuit' => 'required|string|max:20|unique:obras_sociales,cuit',
                'fecha_convenio' => 'required|date',
                'fecha_vencimiento_convenio' => 'required|date|after:fecha_convenio',
                'codigo_validacion' => 'nullable|string|max:50'
            ]);

            DB::beginTransaction();
            
            $obraSocial = ObraSocial::create($request->all());
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Obra Social creada exitosamente',
                'data' => $obraSocial
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la Obra Social: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:100',
                'cuit' => 'required|string|max:20|unique:obras_sociales,cuit,' . $id,
                'fecha_convenio' => 'required|date',
                'fecha_vencimiento_convenio' => 'required|date|after:fecha_convenio',
                'codigo_validacion' => 'nullable|string|max:50'
            ]);

            DB::beginTransaction();
            
            $obraSocial = ObraSocial::findOrFail($id);
            $obraSocial->update($request->all());
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Obra Social actualizada exitosamente',
                'data' => $obraSocial
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la Obra Social: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        \Log::info('Intentando eliminar Obra Social con ID: ' . $id);
        try {
            DB::beginTransaction();
            
            $obraSocial = ObraSocial::findOrFail($id);
            \Log::info('Obra Social encontrada:', $obraSocial->toArray());
            
            $obraSocial->delete();
            \Log::info('Obra Social eliminada correctamente');
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Obra Social eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error en ObraSocialController@destroy: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la Obra Social: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $obraSocial = ObraSocial::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $obraSocial
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la Obra Social: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProductos($id)
    {
        try {
            $productos = DB::table('productos')
                ->join('productos_obras_sociales', 'productos.id', '=', 'productos_obras_sociales.id_producto')
                ->where('productos_obras_sociales.id_obra_social', $id)
                ->select([
                    'productos.nombre',
                    'productos.descripcion',
                    'productos_obras_sociales.descuento'
                ])
                ->get();

            return response()->json([
                'success' => true,
                'data' => $productos
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error en getProductos: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los productos'
            ], 500);
        }
    }

    public function getObrasSocialesSelect()
    {
        try {
            $obras_sociales = ObraSocial::select('id', 'nombre')->get();
            \Log::info('Obras Sociales encontradas:', $obras_sociales->toArray());
            return response()->json($obras_sociales);
        } catch (\Exception $e) {
            \Log::error('Error en ObraSocialController@getObrasSocialesSelect: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Error al cargar las obras sociales: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verificarCuit(Request $request)
    {
        $cuit = $request->input('cuit');
        $existe = ObraSocial::where('cuit', $cuit)->exists();
        
        return response()->json([
            'disponible' => !$existe
        ]);
    }

    public function validarCodigo(Request $request)
    {
        $codigo = $request->input('codigo');
        $obraSocial = ObraSocial::where('codigo_validacion', $codigo)->first();

        return response()->json([
            'valid' => $obraSocial !== null
        ]);
    }

    public function generarReporte($id)
    {
        try {
            $obraSocial = ObraSocial::with(['productos' => function($query) {
                $query->select('productos.*', 'productos_obras_sociales.descuento');
            }])->findOrFail($id);

            $productos = $obraSocial->productos;

            $pdf = Pdf::loadView('reportes.reporte_obrasocial', [
                'obraSocial' => $obraSocial,
                'productos' => $productos,
                'fecha_inicio' => now()->subMonth()->format('Y-m-d'),
                'fecha_fin' => now()->format('Y-m-d'),
                'recetas' => [],
                'totales' => [
                    'monto_total' => 0,
                    'monto_cobertura' => 0,
                    'monto_paciente' => 0
                ]
            ]);

            return $pdf->download('reporte-obra-social-' . $obraSocial->id . '.pdf');

        } catch (\Exception $e) {
            Log::error('Error al generar reporte de obra social: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al generar el reporte: ' . $e->getMessage()
            ], 500);
        }
    }

    public function lista()
    {
        try {
            $obrasSociales = ObraSocial::select('id', 'nombre')->get();
            \Log::info('Obras sociales cargadas:', $obrasSociales->toArray());
            return response()->json($obrasSociales);
        } catch (\Exception $e) {
            \Log::error('Error al cargar obras sociales: ' . $e->getMessage());
            return response()->json(['error' => 'Error al cargar obras sociales'], 500);
        }
    }

    public function productos($id)
    {
        try {
            $obraSocial = ObraSocial::findOrFail($id);
            $productos = $obraSocial->productos()
                ->select('productos.id', 'productos.nombre', 'productos.descripcion', 'productos_obras_sociales.descuento')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $productos
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al obtener productos de obra social: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los productos'
            ], 500);
        }
    }
}