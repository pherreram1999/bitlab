<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
            'portada' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
        ]);

        do {
            $claveGenerada = mt_rand(10000, 99999);
        } while (Grupo::where('clave', $claveGenerada)->exists());

        $rutaPortada = null;
        $colorAleatorio = null;

        if ($request->hasFile('portada')) {
            $rutaPortada = Storage::disk('public')->putFile('grupos', $request->file('portada'));
        } else {
            $colores = [
                '#F97316', '#3B82F6', '#10B981', '#8B5CF6', '#EF4444', '#EC4899'
            ];
            $colorAleatorio = $colores[array_rand($colores)];
        }

        $request->user()->grupos()->create([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
            'clave' => $claveGenerada,
            'concluido' => false,
            'usuario_id' => Auth::id(),
            'portada' => $rutaPortada,
            'color' => $colorAleatorio
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

    //para eliminar la imagen si se borra el grupo
    public function destroy(Grupo $grupo)
    {
        if ($grupo->usuario_id !== Auth::id()) {
            abort(403);
        }

        if ($grupo->portada) {
            if (Storage::disk('public')->exists($grupo->portada)) {
                Storage::disk('public')->delete($grupo->portada);
            }
        }

        $grupo->delete();

        return redirect()->route('dashboard');
    }


}
