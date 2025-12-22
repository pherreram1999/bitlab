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
}
