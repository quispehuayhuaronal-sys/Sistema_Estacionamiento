<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\EspacioController;
use App\Http\Controllers\EstacionamientoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UsuarioController;

Route::get('/', fn () => redirect()->route('login'));

Route::get('/login', [AuthController::class, 'mostrarLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.store');

Route::get('/panel', [
    \App\Http\Controllers\DashboardController::class,
    'index'
])->name('panel');
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');


    Route::middleware('rol:administrador,operador,supervisor')->group(function () {
        Route::get('/clientes', [ClienteController::class, 'index'])
            ->name('clientes.index');

        Route::get('/vehiculos', [VehiculoController::class, 'index'])
            ->name('vehiculos.index');

        Route::get('/espacios', [EspacioController::class, 'index'])
            ->name('espacios.index');

        Route::get('/estacionamientos', [EstacionamientoController::class, 'index'])
            ->name('estacionamientos.index');

            Route::middleware('rol:administrador,operador')->group(function () {
    Route::get('/membresias', [
        \App\Http\Controllers\MembresiaController::class,
        'index'
    ])->name('membresias.index');

    Route::post('/membresias', [
        \App\Http\Controllers\MembresiaController::class,
        'store'
    ])->name('membresias.store');

    Route::put('/membresias/{membresia}', [
        \App\Http\Controllers\MembresiaController::class,
        'update'
    ])->name('membresias.update');

    Route::patch('/membresias/{membresia}/estado', [
        \App\Http\Controllers\MembresiaController::class,
        'cambiarEstado'
    ])->name('membresias.estado');
});
    });

    Route::middleware('rol:administrador,operador')->group(function () {
        Route::post('/clientes', [ClienteController::class, 'store'])
            ->name('clientes.store');
        Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])
            ->name('clientes.update');
        Route::patch('/clientes/{cliente}/estado', [ClienteController::class, 'cambiarEstado'])
            ->name('clientes.estado');

        Route::post('/vehiculos', [VehiculoController::class, 'store'])
            ->name('vehiculos.store');
        Route::put('/vehiculos/{vehiculo}', [VehiculoController::class, 'update'])
            ->name('vehiculos.update');
        Route::patch('/vehiculos/{vehiculo}/estado', [VehiculoController::class, 'cambiarEstado'])
            ->name('vehiculos.estado');

        Route::post('/estacionamientos', [EstacionamientoController::class, 'store'])
            ->name('estacionamientos.store');
        Route::patch('/estacionamientos/{estacionamiento}/salida', [EstacionamientoController::class, 'salida'])
            ->name('estacionamientos.salida');

        Route::get('/pagos', [PagoController::class, 'index'])
            ->name('pagos.index');
        Route::post('/pagos', [PagoController::class, 'store'])
            ->name('pagos.store');
    });

    
    Route::middleware('rol:administrador')->group(function () {
        Route::post('/espacios', [EspacioController::class, 'store'])
            ->name('espacios.store');
        Route::put('/espacios/{espacio}', [EspacioController::class, 'update'])
            ->name('espacios.update');
        Route::patch('/espacios/{espacio}/estado', [EspacioController::class, 'cambiarEstado'])
            ->name('espacios.estado');

        Route::get('/usuarios', [UsuarioController::class, 'index'])
            ->name('usuarios.index');
        Route::post('/usuarios', [UsuarioController::class, 'store'])
            ->name('usuarios.store');
        Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])
            ->name('usuarios.update');
        Route::patch('/usuarios/{usuario}/estado', [UsuarioController::class, 'cambiarEstado'])
            ->name('usuarios.estado');
    });

    Route::middleware('rol:administrador,supervisor')->group(function () {
        Route::get('/reportes', [ReporteController::class, 'index'])
            ->name('reportes.index');
        Route::get('/reportes/exportar-excel', [ReporteController::class, 'exportarExcel'])
            ->name('reportes.excel');
    });
