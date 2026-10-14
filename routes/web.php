<?php

use App\Http\Controllers\LoteriaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/crear', [LoteriaController::class, 'create'])->name('loteria.create');
Route::post('/registro', [LoteriaController::class, 'registro'])->name('loteria.registro');
Route::post('/crear', [LoteriaController::class, 'store'])->name('loteria.store');
Route::get('/gracias', [LoteriaController::class, 'gracias'])->name('loteria.gracias');
