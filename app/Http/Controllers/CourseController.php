<?php

namespace App\Http\Controllers;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        // Trae todos los cursos de la tabla courses.
        $courses = Course::all();

        return response()->json($courses, 200);
    }

    public function show($id)
    {
        $course = Course::find($id);

        if ($course == null) {
            return response()->json(['message' => 'Curso no encontrado'], 404);
        }

        return response()->json($course, 200);
    }
    // CourseRequest: Laravel valida los datos ANTES de entrar en el método.
// Si algo falla, devuelve un 422 y este código no llega a ejecutarse.
    public function store(CourseRequest $request)
    {
        // Objeto Course vacío (todavía no está en la base de datos).
        $course = new Course();

        // Rellenamos las seis columnas con lo que envió Postman.
        $course->academy_id = $request->academy_id;
        $course->name = $request->name;
        $course->description = $request->description;
        $course->start_date = $request->start_date;
        $course->end_date = $request->end_date;
        $course->capacity = $request->capacity;

        // save() hace un INSERT. Laravel rellena id, created_at y updated_at.
        $course->save();

        // 201 = Created.
        return response()->json($course, 201);
    }

    // Recibe un Request normal (no CourseRequest) para poder buscar ANTES de validar.
    public function update(Request $request, $id)
    {
        // 1. Buscamos el curso.
        $course = Course::find($id);

        // 2. Si no existe, 404.
        if ($course == null) {
            return response()->json(['message' => 'Curso no encontrado'], 404);
        }

        // 3. Validamos DESPUÉS del 404, usando las reglas de CourseRequest.
        $request->validate(CourseRequest::rules());

        // 4. Cambiamos los valores del objeto que ya existe (sin "new").
        $course->academy_id = $request->academy_id;
        $course->name = $request->name;
        $course->description = $request->description;
        $course->start_date = $request->start_date;
        $course->end_date = $request->end_date;
        $course->capacity = $request->capacity;

        // 5. save() sobre un objeto que viene de la base de datos hace un UPDATE.
        $course->save();

        // 6. 200: se ha modificado, no creado.
        return response()->json($course, 200);
    }
    public function destroy($id)
    {
        // 1. Buscamos el curso.
        $course = Course::find($id);

        // 2. Si no existe, 404.
        if ($course == null) {
            return response()->json(['message' => 'Curso no encontrado'], 404);
        }

        // 3. $course->students usa la relación belongsToMany de la Fase 3.
        // count() devuelve cuántos alumnos hay matriculados.
        $totalAlumnos = $course->students->count();

        // 4. Si hay alumnos, no borramos y avisamos con un 409 (Conflict).
        if ($totalAlumnos > 0) {
            return response()->json(
                ['message' => 'No se puede eliminar: el curso tiene alumnos matriculados'],
                409
            );
        }

        // 5. Sin alumnos, se puede borrar.
        $course->delete();

        return response()->json(['message' => 'Curso eliminado'], 200);
    }
}
