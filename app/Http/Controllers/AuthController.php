<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // REGISTER: crea un usuario y devuelve su token.
    public function register(Request $request)
    {
        // 1. Validamos los datos.
        // unique:users,email = no puede existir otro usuario con ese email.
        // min:8 = la contraseña debe tener al menos 8 caracteres.
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        // 2. Creamos el usuario (igual que en store de academias).
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;

        // La contraseña NUNCA se guarda en texto normal: se guarda cifrada.
        $user->password = bcrypt($request->password);
        $user->save();

        // 3. Creamos un token para este usuario.
        // 'auth_token' es solo un nombre para reconocerlo.
        // plainTextToken es el texto del token, el que usará el cliente.
        $token = $user->createToken('auth_token')->plainTextToken;

        // 4. 201 = se ha creado un usuario nuevo.
        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    // LOGIN: comprueba las credenciales y devuelve un token.
    public function login(Request $request)
    {
        // 1. Validamos que lleguen los dos campos.
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // 2. Buscamos al usuario por su email. Si no existe, devuelve null.
        $user = User::where('email', $request->email)->first();

        // 3. Si no existe o la contraseña no coincide, 401 (no autenticado).
        // Hash::check compara la contraseña escrita con la versión cifrada de la base de datos.
        // Usamos el mismo mensaje en los dos casos para no revelar si el email existe.
        if ($user == null || Hash::check($request->password, $user->password) == false) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        // 4. Credenciales correctas: creamos un token nuevo y lo devolvemos con 200.
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 200);
    }

    // LOGOUT: destruye el token con el que se hizo la petición.
    public function logout(Request $request)
    {
        // $request->user() es el usuario dueño del token enviado.
        // currentAccessToken() es el token concreto de esta petición.
        // delete() lo borra de la tabla personal_access_tokens: ya no sirve.
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada'], 200);
    }
}