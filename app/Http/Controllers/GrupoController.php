<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class GrupoController extends Controller
{
    public function __invoke(Request $request){
        /** @var User $user */
        $user = Auth::user();
        // dependiendo el ROL


        return Inertia::render('Grupos', [
            'grupos' => $user->grupos
        ]);
    }

    public function inscribir(Request $request){
        ['codigo' => $clave] = $request->validate([
            'codigo' => 'required|exists:grupos,clave',
        ]);
        // buscamos el grupo
        $grupo = Grupo::query()->find($clave);
        /** @var User $user */
        $user = Auth::user();
        $user->grupos()->attach($grupo);
        return response()->json($grupo);
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
        $request->user()->grupos()->create([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
            'clave' => $claveGenerada,
            'concluido' => false,
        ]);
        return redirect()->route('dashboard');
    }
}
