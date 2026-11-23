<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            // bcrypt() convierte la contraseña en un código ilegible.
            // Nunca se guarda una contraseña en texto normal.
            'password' => bcrypt('password'),
            'created_at' => now(), // now() = la fecha y hora de este momento
            'updated_at' => now(),
        ]);
    }
}
