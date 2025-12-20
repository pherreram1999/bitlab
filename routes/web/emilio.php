<?php

use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
//Route::inertia("/alumnos/retos", "AlumnosRetos");
//Route::inertia('/alumnos/miembros', 'AlumnoMiembros');

Route::get('alumnos/retos',[AlumnoController::class,'verRetos'])->name('alumnos.retos');
Route::get('alumnos/miembros',[AlumnoController::class,'index'])->name('alumnos.miembros');
Route::get('profesor/miembros',[\App\Http\Controllers\ProfesorController::class,'view'])->name('profesor.miembros');
Route::get('profesor/retos',[\App\Http\Controllers\ProfesorController::class,'index'])->name('profesor.retos');
Route::get('welcome',[WelcomeController::class,'index'])->name('welcome');