<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // Lista todos los estudiantes.
    public function index()
    {
        $students = Student::all();

        return response()->json($students, 200);
    }

    // Busca un estudiante por su ID.
    public function show($id)
    {
        $student = Student::find($id);

        if ($student == null) {
            return response()->json(['message' => 'Estudiante no encontrado'], 404);
        }

        return response()->json($student, 200);
    }

    // Crea un estudiante nuevo con los datos validados.
    public function store(StudentRequest $request)
    {
        $student = new Student();
        $student->name = $request->name;
        $student->surname = $request->surname;
        $student->email = $request->email;
        $student->phone = $request->phone;
        $student->date_of_birth = $request->date_of_birth;
        $student->save();

        return response()->json($student, 201);
    }

    // Busca el estudiante antes de validar y actualizar sus datos.
    public function update(Request $request, $id)
    {
        $student = Student::find($id);

        if ($student == null) {
            return response()->json(['message' => 'Estudiante no encontrado'], 404);
        }
        if ($student->user_id != $request->user()->id) {
            return response()->json(['message' => 'No tienes permiso para modificar este estudiante'], 403);
        }
        //recibe el id para que excluya al usuario que se esta editando para no tener problemas con el email
        $request->validate(StudentRequest::rules($id));

        $student->name = $request->name;
        $student->surname = $request->surname;
        $student->email = $request->email;
        $student->phone = $request->phone;
        $student->date_of_birth = $request->date_of_birth;
        $student->save();

        return response()->json($student, 200);
    }

    // Elimina un estudiante existente.
    public function destroy(Request $request, $id)
    {
        $student = Student::find($id);

        if ($student == null) {
            return response()->json(['message' => 'Estudiante no encontrado'], 404);
        }
        // Primero el permiso (403)...
        if ($student->user_id != $request->user()->id) {
            return response()->json(['message' => 'No tienes permiso para eliminar este estudiante'], 403);
        }

        $student->delete();

        return response()->json(['message' => 'Estudiante eliminado'], 200);
    }
}
