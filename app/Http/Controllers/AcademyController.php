<?php

namespace App\Http\Controllers;

use App\Models\Academy; // Importamos el modelo para poder usarlo
use Illuminate\Http\Request;

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

    // Request $request contiene todo lo que ha enviado el cliente.
    public function store(Request $request)
    {
        // validate() comprueba los datos que llegaron en la petición.
        // Cada campo tiene sus reglas escritas en un texto, separadas por "|".
        // Si alguna regla falla, Laravel DETIENE el método aquí mismo
        // y devuelve un error 422 con los problemas encontrados.
        // Si todo está bien, el código continúa hacia abajo.
        $request->validate([

            // exists:users,id = el valor debe existir en la columna "id" de la tabla "users".
            // Evita el error de la clave foránea que vimos antes.
            'user_id' => 'required|exists:users,id',

            // required = obligatorio. string = debe ser texto. max:255 = máximo 255 caracteres.
            'name' => 'required|string|max:255',

            // nullable = puede venir vacío o no venir. Si viene, debe ser texto.
            'description' => 'nullable|string',

            'address' => 'required|string|max:255',

            // Mismo máximo que pusimos en la migración (20).
            'phone' => 'required|string|max:20',

            // email = debe tener formato de correo (algo@dominio.com).
            'email' => 'required|email|max:255',
        ]);
        // Creamos un objeto Academy vacío (todavía no está en la base de datos).
        $academy = new Academy();

        // $request->name lee el campo "name" del JSON que envió Postman.
        // Rellenamos cada columna una por una.
        $academy->user_id = $request->user_id;
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
        // 1. Buscamos la academia que se quiere modificar.
        $academy = Academy::find($id);

        // 2. Si no existe, cortamos con un 404 (igual que en show).
        if ($academy == null) {
            return response()->json(['message' => 'Academia no encontrada'], 404);
        }

        // 3. Validamos los datos nuevos (mismas reglas que en store).
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ]);

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
    public function destroy($id)
    {
        // 1. Buscamos la academia (igual que en show y update).
        $academy = Academy::find($id);

        // 2. Si no existe, 404.
        if ($academy == null) {
            return response()->json(['message' => 'Academia no encontrada'], 404);
        }

        // 3. delete() elimina la fila de la base de datos.
        $academy->delete();

        // 4. Devolvemos un mensaje de confirmación con 200.
        return response()->json(['message' => 'Academia eliminada'], 200);
    }
}