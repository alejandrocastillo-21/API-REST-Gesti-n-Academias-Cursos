<?php

namespace App\Http\Controllers;

use App\Models\Academy; // Importamos el modelo para poder usarlo
use Illuminate\Http\Request;
use App\Http\Requests\AcademyRequest;

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

    // Recibe $id: el valor que venía en la URL en lugar de {id}.
    public function show($id)
    {
        // find() busca en la tabla academies la fila cuyo id sea $id.
        // Si no la encuentra, devuelve null (que significa "nada").
        $academy = Academy::find($id);

        // Si no existe, devolvemos un mensaje de error con código 404.
        // 404 significa "Not Found": el recurso pedido no existe.
        if ($academy == null) {
            return response()->json(['message' => 'Academia no encontrada'], 404);
        }

        // Si existe, la devolvemos con código 200 ("todo correcto").
        return response()->json($academy, 200);
    }

    // AcademyRequest $request contiene todo lo que ha enviado el cliente.
    public function store(AcademyRequest $request)
    {
        /** Hemos dejado de usar request->validation[()] porque repetimos el codigo, por lo que optamos a crear con artisan un AcademyRequest.
         * Al recibir el codigo, lo primero de todo comorueba que el request sea valido, ve que funciona y continua, si no salta su excepcion correspondiente.
         * Esto tiene un fallo a la hora de lanzar errores porque el orden correcto en este ejemplo PUT /academies/999 seria primero 404 y luego 422, por lo que
         * tenemos que llamar al metodo despues del find y el metodo recibiria Request request, lo vemos en el update.
         */

        // Creamos un objeto Academy vacío (todavía no está en la base de datos).
        $academy = new Academy();

        // $request->user() devuelve el usuario dueño del token enviado.
// ->id es su id. Así la academia queda siempre a nombre de quien la crea.
        $academy->user_id = $request->user()->id;
        $academy->name = $request->name;
        $academy->description = $request->description;
        $academy->address = $request->address;
        $academy->phone = $request->phone;
        $academy->email = $request->email;

        // save() inserta la fila en la base de datos.
        // Laravel rellena solo el id, created_at y updated_at.
        $academy->save();

        // 201 significa "Created": se ha creado un recurso nuevo.
        return response()->json($academy, 201);
    }

    // Recibe dos cosas: el $request (los datos nuevos) y el $id (de la URL).
    public function update(Request $request, $id)
    {
        $academy = Academy::find($id);

        if ($academy == null) {
            return response()->json(['message' => 'Academia no encontrada'], 404);
        }

        // AUTORIZACIÓN: comparamos el dueño de la academia con el usuario del token.
        // Si no coinciden, sabemos quién es (no es 401) pero no tiene permiso: 403.
        if ($academy->user_id != $request->user()->id) {
            return response()->json(['message' => 'No tienes permiso para modificar esta academia'], 403);
        }

        $request->validate(AcademyRequest::rules());


        // 4. Cambiamos los valores del objeto que ya existe.
        // Ojo: NO usamos "new Academy()", porque no queremos crear otra,
        // sino modificar la que encontramos con find().
        $academy->user_id = $request->user_id;
        $academy->name = $request->name;
        $academy->description = $request->description;
        $academy->address = $request->address;
        $academy->phone = $request->phone;
        $academy->email = $request->email;

        // 5. save() sobre un objeto que ya existe hace un UPDATE, no un INSERT.
        // Laravel actualiza solo updated_at; created_at se queda como estaba.
        $academy->save();

        // 6. Devolvemos la academia ya modificada con 200 (no 201: no se ha creado nada).
        return response()->json($academy, 200);
    }

    // Solo necesita el $id: para borrar no hay que enviar datos en el cuerpo.
    public function destroy(Request $request, $id)
    {
        $academy = Academy::find($id);

        if ($academy == null) {
            return response()->json(['message' => 'Academia no encontrada'], 404);
        }

        if ($academy->user_id != $request->user()->id) {
        return response()->json(['message' => 'No tienes permiso para eliminar esta academia'], 403);
    }

        // $academy->courses usa la relación hasMany que definimos en la Fase 3.
        // count() devuelve cuántos cursos tiene esta academia.
        $totalCursos = $academy->courses->count();

        // Si tiene cursos, no borramos y avisamos con un 409.
        if ($totalCursos > 0) {
            return response()->json(
                ['message' => 'No se puede eliminar: la academia tiene cursos'],
                409
            );
        }

        $academy->delete();

        return response()->json(['message' => 'Academia eliminada'], 200);
    }

    // Recibe el $id de la academia que viene en la URL.
    public function courses($id)
    {
        // 1. Buscamos la academia (igual que en show).
        $academy = Academy::find($id);

        // 2. Si la academia no existe, 404.
        if ($academy == null) {
            return response()->json(['message' => 'Academia no encontrada'], 404);
        }

        // 3. $academy->courses (SIN paréntesis) ejecuta la relación hasMany
        // y devuelve los cursos cuya columna academy_id sea el id de esta academia.
        $courses = $academy->courses;

        // 4. Devolvemos la lista con 200.
        return response()->json($courses, 200);
    }
}