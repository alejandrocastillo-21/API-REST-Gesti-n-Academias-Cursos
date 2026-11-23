<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcademyController;
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

// Cuando llegue un GET a /api/academies, Laravel ejecuta
// el método "index" de la clase AcademyController.
Route::get('/academies', [AcademyController::class, 'index']);