<?php

use App\Http\Controllers\SolicitudPrestamoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SeccionController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoteEquipoController;

Route::get('/', [HomeController::class, 'index'])->name('home')->middleware('auth');

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Registro de usuario
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::middleware('auth')->group(function () {
    Route::prefix('lotes')->name('lotes.')->group(function () {
        Route::get('{loteEquipo}/seriales', [LoteEquipoController::class, 'showSerialForm'])->name('seriales.create');
        Route::post('{loteEquipo}/seriales', [LoteEquipoController::class, 'saveSerials'])->name('seriales.store');
        Route::resource('/', LoteEquipoController::class)->parameters(['' => 'loteEquipo']);
    });

    Route::get('equipos/inventario', [EquipoController::class, 'inventario'])->name('equipos.inventario');
    Route::resources([
        'categorias' => CategoriaController::class,
        'marcas' => MarcaController::class,
        'equipos' => EquipoController::class,
        'roles' => RoleController::class,
        'users' => UserController::class,
        'secciones' => SeccionController::class,
        'departamentos' => DepartamentoController::class,
    ]);
});

Route::middleware(['auth'])->group(function () {
    // Equipment selection and cart routes
    Route::get('/solicitud/create', [SolicitudPrestamoController::class, 'create'])->name('solicitud.create');
    Route::get('/solicitud/cart', [SolicitudPrestamoController::class, 'cart'])->name('solicitud.cart');
    Route::post('/solicitud/add-to-cart', [SolicitudPrestamoController::class, 'addToCart'])->name('solicitud.addToCart');
    Route::delete('/solicitud/remove-from-cart/{index}', [SolicitudPrestamoController::class, 'removeFromCart'])->name('solicitud.removeFromCart');
    Route::patch('/solicitud/update-cart/{index}', [SolicitudPrestamoController::class, 'updateCart'])->name('solicitud.updateCart');
    Route::post('/solicitud/store', [SolicitudPrestamoController::class, 'store'])->name('solicitud.store');
    Route::get('/solicitud/clear-cart', [SolicitudPrestamoController::class, 'clearCart'])->name('solicitud.clearCart');
});
