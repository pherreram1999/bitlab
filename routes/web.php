<?php

use App\Http\Controllers\GrupoController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\RetoController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('dashboard', GrupoController::class)
        ->name('dashboard');
    Route::post('grupos/inscribir',[GrupoController::class,'inscribir'])
    //Route::get('/dashboard', function () {
    //    return Inertia::render('Dashboard');
    // })->name('dashboard');
    require "web/emilio.php";
    require "web/angel.php";
});
