<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Academy extends Model
{
    public function user()
    {
        // Una academia PERTENECE A un usuario (lado "N" de la relación).
        // Usa la columna "user_id" de la tabla academies.
        return $this->belongsTo(User::class);
    }

    public function courses()
    {
        // Una academia tiene MUCHOS cursos.
        // Laravel busca en "courses" la columna "academy_id".
        return $this->hasMany(Course::class);
    }
}
