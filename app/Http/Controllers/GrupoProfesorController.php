<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class GrupoProfesorController extends Controller
{
    function show($id){
        // mostramos los datos
        $grupo = Grupo::query()->findOrFail($id);
        return Inertia::render('GrupoProfesor', ['grupo' => $grupo]);
    }

    public function getMembers(Request $request,$id){
        // recuperamos los miembros que tiene el usuario actual
        /** @var User $user */
        $user = Auth::user();


        //dd(['user_id' => $user->id, 'grupo_id' => $id]);
        $alumnos = User::query()
            ->join('inscripciones', 'users.id', '=', 'inscripciones.usuario_id')
            ->where('inscripciones.grupo_id', $id)
            ->where('users.id', '!=', $user->id)
            ->where('estado', 1)
            ->select(
                'users.nombre',
                'users.apellido_paterno',
                'users.apellido_materno',
                'users.matricula',
                'users.email'
            )
            ->get();

         return response()->json($alumnos);
    }
}
