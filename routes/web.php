<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;

Route::get('/',[HomeController::class, 'index'])->name('home');
Route::view('/home','home.index')->name('home');
Route::get('/login', [loginController::class, 'index'])->name('login');
Route::post('/login', [loginController::class, 'login']);

Route::get('/login', function () {
    return view('auth.login');
});

Route::resources(['categorias' => CategoriaController::class]);
Route::resources(['marcas' => MarcaController::class]);
Route::resources(['equipos' => EquipoController::class]);
