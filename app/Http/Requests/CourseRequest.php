<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        // La academia debe existir en la tabla academies.
        'academy_id' => 'required|exists:academies,id',

        'name' => 'required|string|max:255',
        'description' => 'nullable|string',

        // date = debe ser una fecha válida (por ejemplo 2026-01-15).
        'start_date' => 'required|date',

        // after_or_equal:start_date = no puede ser anterior a la fecha de inicio.
        // Un curso que acaba antes de empezar no tiene sentido.
        'end_date' => 'required|date|after_or_equal:start_date',

        // integer = número entero. min:1 = al menos una plaza.
        // Refleja el unsignedInteger de la migración, y además evita capacidad 0.
        'capacity' => 'required|integer|min:1',
    ];
    }
}
