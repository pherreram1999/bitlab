<?php

namespace Database\Seeders;

use App\Models\Grupo;
use App\Models\Reto;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RetoSeeder extends Seeder
{
    public function run(): void
    {
        $grupos = Grupo::all();

        if ($grupos->isEmpty()) {
            $this->command->warn('No hay grupos creados. Ejecuta GrupoSeeder primero.');
            return;
        }

        // Estructura de preguntas JSON (Simulada)
        $preguntasBase = [
            [
                'texto' => '¿Cuál es la sintaxis correcta para imprimir en PHP?',
                'tipo' => 'multiple',
                'alternativas' => [
                    ['inciso' => 'a', 'texto' => 'echo "Hola";', 'correcta' => true],
                    ['inciso' => 'b', 'texto' => 'print_ln("Hola");', 'correcta' => false],
                    ['inciso' => 'c', 'texto' => 'Console.Write("Hola");', 'correcta' => false],
                ]
            ],
            [
                'texto' => '¿Qué significa MVC?',
                'tipo' => 'multiple',
                'alternativas' => [
                    ['inciso' => 'a', 'texto' => 'Model View Controller', 'correcta' => true],
                    ['inciso' => 'b', 'texto' => 'Max Video Control', 'correcta' => false],
                    ['inciso' => 'c', 'texto' => 'Master View Class', 'correcta' => false],
                ]
            ],
            [
                'texto' => 'Laravel es un framework de...',
                'tipo' => 'multiple',
                'alternativas' => [
                    ['inciso' => 'a', 'texto' => 'Python', 'correcta' => false],
                    ['inciso' => 'b', 'texto' => 'PHP', 'correcta' => true],
                    ['inciso' => 'c', 'texto' => 'Java', 'correcta' => false],
                ]
            ]
        ];

        foreach ($grupos as $grupo) {
            // 1. Crear un Reto ACTIVO (Fecha límite en futuro)
            Reto::create([
                'grupo_id' => $grupo->id,
                'titulo' => 'Examen Parcial - ' . $grupo->nombre,
                'descripcion' => 'Evaluación de conocimientos generales.',
                'puntaje' => 100,
                'opciones' => $preguntasBase, // JSON automático por el cast del modelo
                'max_intentos' => 2,
                'tiempo_limite' => 30, // minutos
                'ayuda' => 'Repasa la documentación oficial.',
                'fecha_limite' => Carbon::now()->addDays(7), // Vence en una semana
            ]);

            // 2. Crear un Reto VENCIDO (Fecha límite en pasado)
            // Esto servirá para probar que NO te deje entrar
            Reto::create([
                'grupo_id' => $grupo->id,
                'titulo' => 'Quiz Rápido (VENCIDO)',
                'descripcion' => 'Este reto ya no debería estar disponible.',
                'puntaje' => 10,
                'opciones' => $preguntasBase,
                'max_intentos' => 1,
                'tiempo_limite' => 5,
                'ayuda' => 'Llegaste tarde.',
                'fecha_limite' => Carbon::now()->subDays(2), // Venció hace 2 días
            ]);
        }
    }
}
