<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GrupoController extends Controller
{
    public function create()
    {
        return Inertia::render('Grupos/CrearGrupos');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:500',
        ]);
        do {
            $claveGenerada = mt_rand(10000, 99999);
        } while (Grupo::where('clave', $claveGenerada)->exists());
        $request->user()->gruposImpartidos()->create([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
            'clave' => $claveGenerada,
            'concluido' => false,
            'portada' => 'https://plus.unsplash.com/premium_photo-1664304160128-ca5a08ac46ce?q=80&w=1153&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'
        ]);
        return redirect()->route('dashboard');
    }
}
