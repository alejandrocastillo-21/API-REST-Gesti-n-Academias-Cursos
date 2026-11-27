<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public function courses()
    {
        // Relación N:N vista desde el otro lado: un alumno tiene MUCHOS cursos.
        // Se define igual, cambiando el modelo y manteniendo la misma tabla pivote.
        $relacion = $this->belongsToMany(Course::class, 'course_student');
        $relacion = $relacion->withPivot('enrolled_at');
        $relacion = $relacion->withTimestamps();

        return $relacion;
    }
    
}
