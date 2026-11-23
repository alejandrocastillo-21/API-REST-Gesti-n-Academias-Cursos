<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Cada línea ejecuta un seeder. Se ejecutan de arriba abajo,
        // y el orden importa: un curso necesita que su academia ya exista.
        // "UserSeeder::class" es simplemente "el nombre de la clase UserSeeder".
        $this->call(UsuarioSeeder::class);
        $this->call(AcademiaSeeder::class);
        $this->call(CursosSeeder::class);
        $this->call(EstudianteSeeder::class);
        $this->call(EstudianteCursoSeeder::class);
    }
}
