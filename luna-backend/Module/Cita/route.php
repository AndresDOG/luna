<?php

use Illuminate\Support\Facades\Route;
use Module\Cita\Controllers\CitaController;
use Module\Cita\Controllers\ListaController;

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
    ]);

    Route::prefix('process')->group(function ()
    {
        Route::get('obtenerCitasAsign', [CitaController::class, 'obtenerCitasAsign']);
    });

    Route::prefix('info')->group(function ()
    {
        Route::get('tipoCita', [ListaController::class, 'tipoCita']);
    });    

    Route::prefix('search')->group(function ()
    {
        Route::get('cita/{id}', [CitaController::Class, 'cita']);
        Route::get('obtenerCitaPaciente/{id}', [CitaController::class, 'obtenerCitaPaciente']);
    });
});
