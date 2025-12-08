<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'clave' => 100,
                'nombre' => 'Administrador',
                'descripcion' => 'Acceso total al sistema'
            ],
            [
                'clave' => 200,
                'nombre' => 'Profesor',
                'descripcion' => 'Puede crear grupos y retos'
            ],
            [
                'clave' => 300,
                'nombre' => 'Estudiante',
                'descripcion' => 'Puede inscribirse y resolver retos'
            ]
        ];

        foreach ($roles as $rol) {
            Rol::create($rol);
        }
    }
}
