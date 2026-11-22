<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Primera academia
        DB::table('academies')->insert([
            'user_id' => 1, // El usuario Admin tiene id 1
            'name' => 'Academia Mediterránea',
            'description' => 'Cursos de idiomas y tecnología.',
            'address' => 'Calle Mayor 10, Almería',
            'phone' => '+34 950 123 456',
            'email' => 'info@mediterranea.test',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Segunda academia
        DB::table('academies')->insert([
            'user_id' => 1,
            'name' => 'Academia Sur',
            'description' => null, // null = sin descripción
            'address' => 'Avenida del Mar 5, Almería',
            'phone' => '+34 950 654 321',
            'email' => 'contacto@academiasur.test',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
