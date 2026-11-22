<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstudianteCursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    // Lucía (alumno 1) matriculada en Inglés B2 (curso 1)
    DB::table('course_student')->insert([
        'course_id' => 1,
        'student_id' => 1,
        'enrolled_at' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Lucía también en Programación con PHP (curso 2)
    DB::table('course_student')->insert([
        'course_id' => 2,
        'student_id' => 1,
        'enrolled_at' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Marcos en Inglés B2 (curso 1)
    DB::table('course_student')->insert([
        'course_id' => 1,
        'student_id' => 2,
        'enrolled_at' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}
}
