<?php

namespace App\Http\Controllers;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\Student;

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

        // Buscamos la academia donde se quiere crear el curso.
        $academy = Academy::find($request->academy_id);

        // Si la academia es de otro usuario, 403.
        if ($academy->user_id != $request->user()->id) {
            return response()->json(['message' => 'No puedes crear cursos en una academia que no es tuya'], 403);
        }

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
        $course = Course::find($id);

        if ($course == null) {
            return response()->json(['message' => 'Curso no encontrado'], 404);
        }

        // $course->academy (sin paréntesis) es la academia del curso.
        // ->user_id es el dueño de esa academia.
        if ($course->academy->user_id != $request->user()->id) {
            return response()->json(['message' => 'No tienes permiso para modificar este curso'], 403);
        }

        $request->validate(CourseRequest::rules());

        // Segundo control: el usuario puede cambiar el academy_id del curso.
        // La academia NUEVA también tiene que ser suya; si no, podría "regalar"
        // su curso a la academia de otro. Aquí ya sabemos que existe (lo validó exists).
        $nuevaAcademia = Academy::find($request->academy_id);

        if ($nuevaAcademia->user_id != $request->user()->id) {
            return response()->json(['message' => 'No puedes mover el curso a una academia que no es tuya'], 403);
        }

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
    public function destroy(Request $request, $id)
    {
        // 1. Buscamos el curso.
        $course = Course::find($id);

        // 2. Si no existe, 404.
        if ($course == null) {
            return response()->json(['message' => 'Curso no encontrado'], 404);
        }

        if ($course->academy->user_id != $request->user()->id) {
            return response()->json(['message' => 'No tienes permiso para eliminar este curso'], 403);
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

    // Es el mismo patrón de la Fase 10, pero con la relación N:N.
    public function students($id)
    {
        $course = Course::find($id);

        if ($course == null) {
            return response()->json(['message' => 'Curso no encontrado'], 404);
        }

        // $course->students (sin paréntesis) ejecuta la relación belongsToMany
        // y devuelve los alumnos matriculados, con los datos de la pivote en "pivot".
        $students = $course->students;

        return response()->json($students, 200);
    }

    // Los dos valores de la URL llegan por posición: primero el curso, luego el alumno.
    public function enroll(Request $request, $id, $studentId)
    {
        // 1. ¿Existe el curso?
        $course = Course::find($id);

        if ($course == null) {
            return response()->json(['message' => 'Curso no encontrado'], 404);
        }

        // 2. ¿Existe el alumno? Ahora viene en la URL, así que se busca con find.
        $student = Student::find($studentId);

        if ($student == null) {
            return response()->json(['message' => 'Alumno no encontrado'], 404);
        }

        // 3. AUTORIZACIÓN: el dueño de la academia del curso debe ser el usuario del token.
        if ($course->academy->user_id != $request->user()->id) {
            return response()->json(['message' => 'No tienes permiso para matricular en este curso'], 403);
        }

        // 4. ¿Ya está matriculado? (409)
        $relacion = $course->students();
        $relacion = $relacion->where('student_id', $studentId);
        $yaMatriculado = $relacion->exists();

        if ($yaMatriculado == true) {
            return response()->json(['message' => 'El alumno ya está matriculado en este curso'], 409);
        }

        // 5. ¿Quedan plazas? (409)
        $totalMatriculados = $course->students()->count();

        if ($totalMatriculados >= $course->capacity) {
            return response()->json(['message' => 'El curso no tiene plazas disponibles'], 409);
        }

        // 6. Escribimos la línea en el "cuaderno de matrículas".
        $course->students()->attach($studentId, ['enrolled_at' => now()]);

        return response()->json(['message' => 'Alumno matriculado correctamente'], 201);
    }

    public function unenroll(Request $request, $id, $studentId)
    {
        // 1. ¿Existe el curso?
        $course = Course::find($id);

        if ($course == null) {
            return response()->json(['message' => 'Curso no encontrado'], 404);
        }

        // 2. ¿Existe el alumno?
        $student = Student::find($studentId);

        if ($student == null) {
            return response()->json(['message' => 'Alumno no encontrado'], 404);
        }

        // 3. AUTORIZACIÓN: solo el dueño de la academia del curso.
        if ($course->academy->user_id != $request->user()->id) {
            return response()->json(['message' => 'No tienes permiso para desmatricular en este curso'], 403);
        }

        // 4. ¿Está matriculado? Si no, no hay línea que tachar: 404.
        $relacion = $course->students();
        $relacion = $relacion->where('student_id', $studentId);
        $matriculado = $relacion->exists();

        if ($matriculado == false) {
            return response()->json(['message' => 'El alumno no está matriculado en este curso'], 404);
        }

        // 5. Tachamos la línea (solo la matrícula, no el alumno ni el curso).
        $course->students()->detach($studentId);

        return response()->json(['message' => 'Alumno desmatriculado'], 200);
    }
}
