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
}