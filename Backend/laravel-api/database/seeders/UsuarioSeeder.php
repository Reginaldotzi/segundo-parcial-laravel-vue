<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario admin
        Usuario::create([
            'nombre' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'rol' => 'admin'
        ]);

        // Crear usuario normal
        Usuario::create([
            'nombre' => 'Usuario Test',
            'email' => 'usuario@example.com',
            'password' => Hash::make('password123'),
            'rol' => 'usuario'
        ]);

        // Crear más usuarios para pruebas
        Usuario::create([
            'nombre' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'password' => Hash::make('password123'),
            'rol' => 'usuario'
        ]);

        Usuario::create([
            'nombre' => 'María García',
            'email' => 'maria@example.com',
            'password' => Hash::make('password123'),
            'rol' => 'admin'
        ]);
    }
}
