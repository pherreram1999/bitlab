<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GrupoController;
Route::inertia("/retos/crear", "Retos/Crear");
Route::get('/grupos/crear', [GrupoController::class, 'create'])->name('grupos.create');
Route::post('/grupos', [GrupoController::class, 'store'])->name('grupos.store');

