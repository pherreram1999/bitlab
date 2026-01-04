<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class FeedbackController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Validación estricta del rol ADMIN
        if (!$user->rol || $user->rol->clave !== 'ADMIN') {
            abort(403, 'Acceso no autorizado.');
        }

        $feedbacks = Feedback::with('user')
            ->latest()
            ->paginate(12);


        return Inertia::render('Feedback/Index', [
            'feedbacks' => $feedbacks
        ]);
    }

    public function create()
    {
        return Inertia::render('Feedback/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'comentarios' => 'required|string|max:1000',
        ]);

        Feedback::create([
            'usuario_id' => auth()->id(),
            'comentarios' => $validated['comentarios'],
        ]);

        return back()->with('success', '¡Gracias por tus comentarios! Son muy valiosos para nosotros.');
    }
}
