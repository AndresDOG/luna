<?php

use Illuminate\Support\Facades\Route;
use Module\Cita\Controllers\CitaController;
use Module\Cita\Controllers\ListaController;
use Module\Cita\Controllers\NotaController;
use Module\Cita\Controllers\EstadoCitaController;

/*
|--------------------------------------------------------------------------
| Service Routes
|--------------------------------------------------------------------------
| namespace Module\Cita
| Routes Service Module!
|
*/

Route::prefix('cita')->middleware(['auth:sanctum'])->group(function ()
{
    Route::resources([
        'cita' => CitaController::class,
        'nota' => NotaController::class,
        'estadoCita' => EstadoCitaController::class,
    ]);

    Route::prefix('process')->group(function ()
    {
        Route::get('obtenerCitasAsign', [CitaController::class, 'obtenerCitasAsign']);
        Route::post('cambiarEstado', [CitaController::class, 'cambiarEstado']);
        Route::post('actualizarCita/{id}', [CitaController::class, 'actualizarCita']);
    });

    Route::prefix('info')->group(function ()
    {
        Route::get('tipoCita', [ListaController::class, 'tipoCita']);
        Route::post('consultaSimple', [CitaController::class, 'consultaSimple']);
    });    

    Route::prefix('search')->group(function ()
    {
        Route::get('cita/{id}', [CitaController::Class, 'cita']);
        Route::get('obtenerCitaPaciente/{id}', [CitaController::class, 'obtenerCitaPaciente']);
    });
});
