<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Grupo;
use App\Models\User;
use App\Models\Rol;

class AlumnoGruposSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Aseguramos que existe el rol de alumno
        $rolAlumno = Rol::firstOrCreate(
            ['nombre' => 'ALUMNO'],
            ['clave' => 'ALUMNO', 'descripcion' => 'Rol de estudiante']
        );

        // 2. Obtenemos TODOS los grupos existentes (creados por GrupoSeeder)
        $grupos = Grupo::all();

        if ($grupos->isEmpty()) {
            $this->command->warn('⚠️ No hay grupos para inscribir alumnos. Ejecuta GrupoSeeder primero.');
            return;
        }

        // 3. Crear o buscar Alumnos
        $alumnosIds = [];

        // Creamos 15 alumnos de prueba
        for ($i = 1; $i <= 15; $i++) {
            $matricula = 20240000 + $i;
            $email = "alumno{$i}@bitlab.test";

            $alumno = User::firstOrCreate(
                ['email' => $email],
                [
                    'rol_id' => $rolAlumno->id,
                    'nombre' => "Alumno {$i}",
                    'apellido_paterno' => "Paterno",
                    'apellido_materno' => "Materno",
                    'matricula' => $matricula,
                    'estado' => 1,
                    'password' => Hash::make('12345'), // Contraseña fácil para pruebas
                ]
            );
            $alumnosIds[] = $alumno->id;
        }

        // 4. Inscribir alumnos en los grupos existentes
        // Haremos que cada alumno se inscriba en 1 o 2 grupos al azar
        $this->command->info("📝 Inscribiendo alumnos en " . $grupos->count() . " grupos existentes...");

        foreach ($alumnosIds as $alumnoId) {
            // Seleccionamos grupos aleatorios para este alumno (entre 1 y 3 grupos)
            $gruposAleatorios = $grupos->random(rand(1, min(3, $grupos->count())));

            foreach ($gruposAleatorios as $grupo) {
                // Verificar si ya está inscrito para no duplicar
                $existe = DB::table('inscripciones')
                    ->where('grupo_id', $grupo->id)
                    ->where('usuario_id', $alumnoId)
                    ->exists();

                if (!$existe) {
                    DB::table('inscripciones')->insert([
                        'usuario_id' => $alumnoId,
                        'grupo_id' => $grupo->id,
                        'puntos_obtenidos' => rand(0, 100),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        $this->command->info('✅ Alumnos creados e inscritos correctamente.');
    }
}
