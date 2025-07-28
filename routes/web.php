<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\EquipoController;

Route::get('/', function () {
    return view('template');
});

Route::resources(['categorias' => CategoriaController::class]);
Route::resources(['marcas' => MarcaController::class]);
Route::resources(['equipos' => EquipoController::class]);