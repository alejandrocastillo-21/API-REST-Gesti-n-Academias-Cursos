<?php

namespace App\Http\Controllers;

use App\Models\Academy; // Importamos el modelo para poder usarlo

class AcademyController extends Controller
{
    // "index" es el nombre habitual del método que lista todos los registros.
    public function index()
    {
        // Academy::all() trae TODAS las filas de la tabla academies.
        $academies = Academy::all();

        // Devolvemos esa lista como JSON.
        // El 200 es el código de estado: "todo correcto".
        return response()->json($academies, 200);
    }
}