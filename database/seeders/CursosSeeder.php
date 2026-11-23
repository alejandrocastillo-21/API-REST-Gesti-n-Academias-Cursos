<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CursosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('courses')->insert([
            'academy_id' => 1, // Academia Mediterránea
            'name' => 'Inglés B2',
            'description' => 'Preparación para el examen B2.',
            'start_date' => '2026-01-15',
            'end_date' => '2026-06-30',
            'capacity' => 20,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('courses')->insert([
            'academy_id' => 1,
            'name' => 'Programación con PHP',
            'description' => 'Introducción a PHP y Laravel.',
            'start_date' => '2026-02-01',
            'end_date' => '2026-05-31',
            'capacity' => 15,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('courses')->insert([
            'academy_id' => 2, // Academia Sur
            'name' => 'Excel Avanzado',
            'description' => null,
            'start_date' => '2026-03-10',
            'end_date' => '2026-04-30',
            'capacity' => 12,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
