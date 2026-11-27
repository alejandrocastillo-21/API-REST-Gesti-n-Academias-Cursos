<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AcademyController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;


// ---------- RUTAS PÚBLICAS: no necesitan token ----------

Route::get('/inicio', function () {

    return response()->json([
        'message' => 'Este es el inicio',
    ]);

});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Cursos de una academia. R5 la pide pública.
// {academy} es solo el nombre del hueco: el controlador lo recibe como $id, por posición.
Route::get('/academy/{academy}/courses', [AcademyController::class, 'courses']);

// ---------- RUTAS PROTEGIDAS: todo lo de aquí dentro exige token ----------
// auth:sanctum es un middleware: un "guardia" que comprueba el token
// ANTES de ejecutar el controlador. Si no es válido, devuelve 401.
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // apiResource crea de una vez las cinco rutas del CRUD de cada recurso:
    // index, show, store, update y destroy.
    Route::apiResource('academies', AcademyController::class);
    Route::apiResource('courses', CourseController::class);
    Route::apiResource('students', StudentController::class);

    // Alumnos de un curso: basta con estar autenticado.
    Route::get('/course/{course}/students', [CourseController::class, 'students']);

    // Matricular y desmatricular: autenticado y además dueño (lo comprueba el controlador).
    // El alumno viaja en la URL, ya no en el body.
    Route::post('/courses/{course}/enroll/{student}', [CourseController::class, 'enroll']);
    Route::delete('/courses/{course}/unenroll/{student}', [CourseController::class, 'unenroll']);
});