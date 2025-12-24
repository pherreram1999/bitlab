<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Carbon\Carbon;

class RetoController extends Controller
{
    public function create($id)
    {
        /** @var Grupo $grupo */
        $grupo = Grupo::findOrFail($id);
        return Inertia::render('RetoCrear',['grupo'=>$grupo]);
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
            'ayuda' => 'nullable|string'
        ]);
        $grupo = $request->user()->gruposImpartidos()->first();

        if (!$grupo) {
            return back()->withErrors(['error' => 'No tienes grupos creados.']);
        }

        // Creamos el reto
        $grupo->retos()->create([
            'titulo' => $validated['titulo'],
            'descripcion' => $validated['descripcion'],
            'puntaje' => $validated['puntaje'],
            'opciones' => $validated['opciones'],
            'max_intentos' => $validated['max_intentos'],
            'ayuda' => $validated['ayuda'],
            'fecha_limite' => Carbon::parse($validated['fecha_limite']),
        ]);
        return Redirect::route('profesor.retos')->with('success', 'Reto creado correctamente');
    }
}
