<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::all();
        return response()->json(['data' => $categorias]);
    }

    public function lista()
    {
        try {
            $categorias = Categoria::select('id', 'nombre')->get();
            return response()->json($categorias);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al cargar categorías'], 500);
        }
    }
}