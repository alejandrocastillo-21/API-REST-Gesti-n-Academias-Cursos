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

// Cuando llegue un GET a /api/academies, Laravel ejecuta
// el método "index" de la clase AcademyController.
Route::get('/academies', [AcademyController::class, 'index']);

// {id} es un parámetro, puede ser cualquier numero que este registrado.
// Laravel pasa ese valor al método "show" del controlador.
Route::get('/academies/{id}', [AcademyController::class, 'show']);

// POST = crear. Es la misma URL que el listado (/api/academies),
// pero con otro verbo HTTP, así que Laravel sabe que es otra acción.
Route::post('/academies', [AcademyController::class, 'store']);
// put = editar. {id} indica qué academia se modifica.
Route::put('/academies/{id}', [AcademyController::class, 'update']);

// DELETE = borrar. {id} indica qué academia se elimina.
Route::delete('/academies/{id}', [AcademyController::class, 'destroy']);