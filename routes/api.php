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

// Rutas PÚBLICAS: el usuario todavía no tiene token.
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas PROTEGIDAS: solo entra quien envíe un token válido.
// auth:sanctum es un "middleware": un guardia que se pone delante de la ruta.
// Comprueba el token ANTES de ejecutar el controlador. Si no es válido, devuelve 401.
// group agrupa varias rutas bajo el mismo guardia.
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

// PÚBLICAS: sin token.
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/academies/{id}/courses', [AcademyController::class, 'courses']);

// PROTEGIDAS: todo lo que esté aquí dentro exige token.
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);


    /// apiResource crea de una vez las cinco rutas del CRUD para academias.
// Busca en AcademyController los métodos: index, show, store, update y destroy.
// 'academies' es la parte de la URL: /api/academies
    Route::apiResource('academies', AcademyController::class);
    Route::apiResource('courses', CourseController::class);
    Route::apiResource('students', StudentController::class);

    // Ruta anidada: los cursos de una academia.
// {id} es el id de la academia. El método "courses" lo escribimos nosotros
// en AcademyController (no es uno de los cinco del apiResource).
    // Aquí dentro también tus tres rutas de matrículas (students, enroll, unenroll).
    Route::get('/courses/{id}/students', [CourseController::class, 'students']);
    Route::post('/courses/{id}/students', [CourseController::class, 'enroll']);
    Route::delete('/courses/{id}/students/{studentId}', [CourseController::class, 'unenroll']);
});

