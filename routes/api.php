<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcademyController;
use Illuminate\Http\Request;


// Route::get = esta ruta responde a peticiones GET.
// '/ping' = la parte final de la URL (la real será /api/ping).
// La función de dentro es el código que se ejecuta al recibir la petición.
Route::get('/ping', function () {

    // response()->json() convierte un array de PHP en JSON y lo devuelve.
    return response()->json([
        'message' => 'La API funciona',
    ]);
});

Route::get('/inicio', function () {

    return response()->json([
        'message' => 'Este es el inicio',
    ]);

});

/// apiResource crea de una vez las cinco rutas del CRUD para academias.
// Busca en AcademyController los métodos: index, show, store, update y destroy.
// 'academies' es la parte de la URL: /api/academies
Route::apiResource('academies', AcademyController::class);

// Ruta anidada: los cursos de una academia.
// {id} es el id de la academia. El método "courses" lo escribimos nosotros
// en AcademyController (no es uno de los cinco del apiResource).
Route::get('/academies/{id}/courses', [AcademyController::class, 'courses']);