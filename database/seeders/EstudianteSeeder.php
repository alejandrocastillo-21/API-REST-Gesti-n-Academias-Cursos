<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstudianteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    DB::table('students')->insert([
        'name' => 'Lucía',
        'surname' => 'García',
        'email' => 'lucia@example.com',
        'phone' => '+34 600 111 222',
        'date_of_birth' => '2000-05-14',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('students')->insert([
        'name' => 'Marcos',
        'surname' => 'Pérez',
        'email' => 'marcos@example.com',
        'phone' => null, // El teléfono es opcional
        'date_of_birth' => '1998-11-02',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}
}
