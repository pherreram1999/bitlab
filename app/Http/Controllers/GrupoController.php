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


        return Inertia::render('GruposDashboard');
    }

    public function create()
    {
        return Inertia::render('Grupos/CrearGrupos');
    }

    public function inscribir(Request $request){
        ['codigo' => $clave] = $request->validate([
            'codigo' => 'required|exists:grupos,clave',
        ]);
        // buscamos el grupo
        $grupo = Grupo::query()->where('clave', $clave)
            ->first();
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
            'usuario_id' => Auth::id() // para el usuario creador del grupo
        ]);
        return redirect()->route('dashboard');
    }

    function show($id){
        // mostramos los datos
        $grupo = Grupo::query()->findOrFail($id);
        return Inertia::render('GrupoManage', ['grupo' => $grupo]);
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
