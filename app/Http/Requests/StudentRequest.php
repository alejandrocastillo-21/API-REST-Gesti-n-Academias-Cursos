<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public static function rules($id = null): array
    {
        // Regla base del email, igual que la tuya.
        $reglaEmail = 'required|email|max:255|unique:students,email';

        // Si nos pasan un id, lo añadimos al final
        // para que Laravel ignore a ese alumno al comprobar la unicidad.
        //único en students, columna email, ignorando la fila con id 5
        if ($id != null) {
            $reglaEmail = $reglaEmail . ',' . $id;
        }
        return [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => $reglaEmail,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'required|date',
        ];
    }
}
