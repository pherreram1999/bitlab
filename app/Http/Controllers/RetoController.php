<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class RetoController extends Controller
{
    public function create()
    {
        return Inertia::render('Retos/Crear');
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
            'fecha_limite' => 'nullable|datetime',
        ]);
        // asigna el reto al usuario actual
        $request->user()->gruposImpartidos()->first()->retos()->create([
            'titulo' => $validated['titulo'],
            'descripcion' => $validated['descripcion'],
            'puntaje' => $validated['puntaje'],
            'opciones' => $validated['opciones'],
            'fecha_limite' => now(),
        ]);
        return Redirect::route('dashboard')->with('success', 'Reto creado correctamente');
    }
}
