<?php

namespace App\Http\Controllers;

use App\Models\Reto;
use App\Models\RealizacionReto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Grupo;
use Illuminate\Support\Facades\Auth;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\Carbon;

class RetoController extends Controller
{
    public function create($id)
    {
        /** @var Grupo $grupo */
        $grupo = Grupo::findOrFail($id);
        return Inertia::render('RetoCrear', ['grupo' => $grupo]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string',
            'descripcion' => 'nullable|string',
            'puntaje' => 'required|integer',
            'opciones' => 'required|array',
            'opciones.*.texto' => 'required|string',
            'opciones.*.tipo' => 'required|in:libre,multiple',
            'opciones.*.alternativas' => 'nullable|array',
            'fecha_limite' => 'required|date',
            'max_intentos' => 'required|integer',
            'tiempo_limite' => 'required|integer|min:3',
            'ayuda' => 'nullable|string',
            'grupo_id' => 'required|exists:grupos,id',
        ]);

        /** @var Grupo $grupo */
        $grupo = Grupo::findOrFail($validated['grupo_id']);
        // Creamos el reto
        $grupo->retos()->create([
            'titulo' => $validated['titulo'],
            'descripcion' => $validated['descripcion'],
            'puntaje' => $validated['puntaje'],
            'opciones' => $validated['opciones'],
            'max_intentos' => $validated['max_intentos'],
            'tiempo_limite' => $validated['tiempo_limite'],
            'ayuda' => $validated['ayuda'],
            'fecha_limite' => Carbon::parse($validated['fecha_limite']),
        ]);

        return Redirect::route('grupo.show', [$grupo->id])
            ->with('success', 'Reto creado correctamente');
    }


        public function show($id){
            $reto = Reto::findOrFail($id);
            $ahora = Carbon::now();
            $fechaLimite = Carbon::parse($reto->fecha_limite);
            $estaVencido =$ahora->greaterThan($fechaLimite);

            $intentosPreviosData = RealizacionReto::where('usuario_id', auth()->id())
                ->where('reto_id', $id)
                ->get();

            $intentosPrevios = $intentosPreviosData->count();
            $mejorCalificacion = $intentosPreviosData->max('calificacion') ?? 0;
            $yaTerminado = $intentosPreviosData->where('calificacion', '>=', $reto->puntaje)->isNotEmpty();
            return Inertia::render('RetoShow', [
                'reto' => $reto,
                'intentos_previos' => $intentosPrevios,
                'mejor_calificacion' => $mejorCalificacion,
                'ya_terminado' => $yaTerminado,
                'esta_vencido' => $estaVencido
            ]);
        }

    public function guardarRealizacionReto(Request $request)
    {
        $validated = $request->validate([
            'reto_id' => 'required|exists:retos,id',
            'aciertos' => 'required|integer|min:0',
            'respuestas' => 'required|array',
            'tiempo_tomado' => 'required',
        ]);

        $reto = Reto::findOrFail($validated['reto_id']);
        $ahora = Carbon::now();
        $fechaLimite = Carbon::parse($reto->fecha_limite);
        if ($ahora->greaterThan($fechaLimite->addMinutes(1))) {
            return back()->withErrors(['error' => 'El tiempo límite para enviar este reto ha expirado.']);
        }

        $userId = auth()->id();

        // 1. Obtener intentos previos para calcular el número de intento actual
        $intentosPrevios = RealizacionReto::query()
            ->where('usuario_id', $userId)
            ->where('reto_id', $reto->id)
            ->get();

        $numeroIntentoActual = $intentosPrevios->count() + 1;

        // 2. Calcular calificación
        $totalReactivos = count($reto->opciones ?? []);
        $calificacion = 0;
        if ($totalReactivos > 0)
            $calificacion = ($validated['aciertos'] / $totalReactivos) * $reto->puntaje;


        // 3. Determinar si es el mejor intento (Estrictamente mayor que el máximo anterior)
        $maxCalificacionAnterior = $intentosPrevios->max('calificacion') ?? 0;

        // Si no hay intentos previos, este es el mejor por defecto
        if ($intentosPrevios->isEmpty()) {
            $esMejorIntento = true;
        } else {
            $esMejorIntento = $calificacion > $maxCalificacionAnterior;
        }

        if ($esMejorIntento) {
            // quitamos al anterior que es mejor reto
            RealizacionReto::where('usuario_id', $userId)
                ->where('reto_id', $validated['reto_id'])
                ->where('es_mejor_intento', true)
                ->update(['es_mejor_intento' => false]);
        }

                RealizacionReto::create([

                    'usuario_id' => $userId,

                    'reto_id' => $validated['reto_id'],

                    'calificacion' => $calificacion,

                    'puntaje_max' => $reto->puntaje,

                    'aciertos' => $validated['aciertos'],

                    'total_reactivos' => $totalReactivos,
            'es_mejor_intento' => $esMejorIntento,
            'no_intentos' => $numeroIntentoActual,
            'fecha_realizacion' => Carbon::now(),
            'respuesta' => $validated['respuestas'],
            'calificado' => true,
            'tiemṕo_tomado' => $validated['tiempo_tomado'],
        ]);

        return back()->with('success', 'Reto guardado correctamente');
    }
    private function calcularEstadisticas($retoId)
    {
        // 1. Necesitamos el reto para saber el puntaje total
        $reto = Reto::findOrFail($retoId);

        $realizaciones = RealizacionReto::with('user')
            ->where('reto_id', $retoId)
            ->where('es_mejor_intento', true)
            ->get();

        if ($realizaciones->isEmpty()) {
            return null;
        }

        // 2. Promedio de Calificación
        $promedioCalificacion = $realizaciones->avg('calificacion');

        // 3. Promedio de Tiempo
        // Convertimos cada tiempo (HH:MM:SS) a segundos para promediar
        $promedioSegundos = $realizaciones->map(function ($r) {
            if (!$r->tiempo_tomado) return 0;
            // Parseamos el tiempo. Asumiendo formato H:i:s
            return Carbon::parse($r->tiempo_tomado)->secondsSinceMidnight();
        })->avg();

        // Convertimos el promedio de segundos de vuelta a H:i:s
        $promedioTiempo = gmdate('H:i:s', (int)$promedioSegundos);
        $totalAlumnos = $realizaciones->count();
        // CALCULO DINÁMICO: El 60% del puntaje total del reto
        $puntajeMinimoAprobatorio = $reto->puntaje * 0.60;
        // Contamos cuántos tienen una calificación mayor o igual al mínimo
        $aprobados = $realizaciones->where('calificacion', '>=', $puntajeMinimoAprobatorio)->count();
        $reprobados = $totalAlumnos - $aprobados;

        return [
            'total_alumnos' => $totalAlumnos,
            'promedio_calificacion' => round($promedioCalificacion, 2),
            'promedio_tiempo' => $promedioTiempo,
            'aprobados' => $aprobados,
            'reprobados' => $reprobados,
            'detalle_alumnos' => $realizaciones
        ];
    }

    public function reporte($id)
    {
        $reto = Reto::with('grupo')->findOrFail($id);

        if ($reto->grupo->usuario_id !== Auth::id()) {
            abort(403);
        }
        /** @var User $user */
        $user = Auth::user();
        $gruposCreados = Grupo::where('usuario_id', $user->id)->orderBy('created_at', 'desc')->get();
        $gruposInscritos = $user->grupos()->orderBy('created_at', 'desc')->get();
        // Fusionamos para el sidebar
        $grupos = $gruposCreados->merge($gruposInscritos)->unique('id')->values();

        $estadisticas = $this->calcularEstadisticas($id);

        return Inertia::render('Retos/ReporteReto', [
            'reto' => $reto,
            'stats' => $estadisticas,
            'grupos' => $grupos
        ]);
    }
    public function descargarPdf($id)
    {
        $reto = Reto::with('grupo')->findOrFail($id);

        if ($reto->grupo->usuario_id !== Auth::id()) {
            abort(403);
        }

        $estadisticas = $this->calcularEstadisticas($id);
        $pdf = SnappyPdf::loadView('pdf.reporte_reto', [
            'reto' => $reto,
            'stats' => $estadisticas,
            'fecha' => now()->format('d-m-Y')
        ]);

        return $pdf->download('reporte_reto_{$reto->clave}.pdf');
    }
}
