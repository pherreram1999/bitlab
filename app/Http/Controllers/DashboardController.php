<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke(Request $request){
        /** @var User $user */
        $user = Auth::user();
        // dependiendo el ROL

        return Inertia::render('Grupos', [
            'grupos' => $user->grupos
        ]);
    }
}
