<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public function academy()
    {
        // Un curso PERTENECE A una academia (usa "academy_id").
        return $this->belongsTo(Academy::class);
    }

    public function students()
    {
        // Relación N:N: un curso tiene MUCHOS alumnos.
        // El segundo parámetro es el nombre de la tabla pivote.
        // withPivot('enrolled_at'): también queremos leer esa columna de la pivote.
        // withTimestamps(): rellena created_at y updated_at de la pivote automáticamente.
        $relacion = $this->belongsToMany(Student::class, 'course_student');
        $relacion = $relacion->withPivot('enrolled_at');
        $relacion = $relacion->withTimestamps();

        return $relacion;
    }
}
