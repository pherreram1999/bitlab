<?php

namespace Database\Seeders;

use App\Models\Grupo;
use App\Models\RealizacionReto;
use App\Models\Reto;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RealizacionSeeder extends Seeder
{
    public function run(): void
    {
        // Obtenemos todos los retos
        $retos = Reto::with('grupo')->get();

        foreach ($retos as $reto) {
            // Obtenemos los alumnos inscritos en el grupo de este reto
            // (Asumiendo que tienes la relación definida, si no, lo hacemos manual)
            $alumnosIds = \DB::table('inscripciones')
                ->where('grupo_id', $reto->grupo_id)
                ->pluck('usuario_id');

            if ($alumnosIds->isEmpty()) continue;

            // Hacemos que el 80% de los alumnos haya contestado
            foreach ($alumnosIds as $userId) {
                if (rand(0, 10) > 2) {

                    // Calculamos datos simulados
                    $totalPreguntas = count($reto->opciones ?? []);
                    $aciertos = rand(0, $totalPreguntas); // Aciertos aleatorios
                    $calificacion = ($totalPreguntas > 0)
                        ? ($aciertos / $totalPreguntas) * $reto->puntaje
                        : 0;

                    // Simular tiempo tomado (entre 5 y 25 minutos)
                    $minutos = rand(5, 25);
                    $segundos = rand(0, 59);
                    $tiempoTomado = sprintf("00:%02d:%02d", $minutos, $segundos);

                    RealizacionReto::create([
                        'usuario_id' => $userId,
                        'reto_id' => $reto->id,
                        'calificacion' => $calificacion,
                        'puntaje_max' => $reto->puntaje,
                        'aciertos' => $aciertos,
                        'total_reactivos' => $totalPreguntas,
                        'es_mejor_intento' => true, // Simplificamos asumiendo 1 intento
                        'no_intentos' => 1,
                        'fecha_realizacion' => Carbon::now()->subHours(rand(1, 48)),
                        'respuesta' => [], // Array vacío por simplicidad
                        'calificado' => true,
                        'tiempo_tomado' => $tiempoTomado, // ¡Probando tu nuevo campo!
                    ]);
                }
            }
        }
    }
}
