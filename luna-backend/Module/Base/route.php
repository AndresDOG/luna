<?php

use Illuminate\Support\Facades\Route;
use Module\Base\Controllers\InfoController;
use Module\Base\Controllers\MedicoController;
use Module\Base\Controllers\PermisoController;
use Module\Base\Controllers\TarifaController;
use Module\Base\Controllers\UsuarioController;
use Module\Base\Controllers\RolController;
use Module\Base\Controllers\ComponenteController;
use Module\Base\Controllers\EspecialidadController;
use Module\Base\Controllers\TratamientoController;
use Module\Base\Controllers\PacienteController;

/*
|--------------------------------------------------------------------------
| Common Routes
|--------------------------------------------------------------------------
| Routes Common Module!
|
*/

Route::prefix('base')->middleware(['auth:sanctum'])->group(function ()
{
    Route::resources([
        'medico' => MedicoController::class,
        'tarifa' => TarifaController::class,
        'usuario' => UsuarioController::class,
        'rol' => RolController::class,
        'componente' => ComponenteController::class,
        'permiso' => PermisoController::class,
        'especialidad' => EspecialidadController::class,
        'tratamiento' => TratamientoController::class,
        'paciente' => PacienteController::class,

      /*
        'empresa' => EmpresaController::Class,
        */
    ]);

    Route::prefix('info')->group(function ()
    {
        Route::get('usuarioActivo', [InfoController::class, 'usuarioActivo']);
        Route::get('menu', [InfoController::class, 'menu']);
        Route::get('permisoComponente/{id}', [PermisoController::class, 'permisoComponente']);
        Route::get('localidad/{id}', [InfoController::class, 'localidad']);
        Route::get('tipoIdentidad',  [InfoController::class, 'tipoIdentidad']);
        Route::get('departamento',   [InfoController::class, 'departamento']);
        Route::get('traerMenu', [ComponenteController::class, 'traerMenu']);
        Route::post('consultaSimple', [PacienteController::class, 'consultaSimple']);

    });

    Route::prefix('search')->group(function ()
    {
        Route::get('medico/{id}', [MedicoController::class, 'medico']);
        Route::get('medicov/{id}', [MedicoController::class, 'medicov']);
        Route::get('medicof/{criterio}', [MedicoController::class, 'medicof'])->where('criterio', '.*');;
        Route::get('paciente/{id}', [PacienteController::class, 'paciente']);
        Route::get('paciente/{id}', [PacienteController::class, 'paciente']);
        Route::get('obtenerTratamientos/{pac_id}', [PacienteController::class, 'obtenerTratamientos']);
        Route::get('pacientev/{id}', [PacienteController::class, 'pacientev']);
        Route::post('obtenerPermisos', [PermisoController::class, 'obtenerPermisos']);
        Route::post('permisoComponente', [PermisoController::class, 'permisoComponente']);
        Route::post('obtenerAccesos', [PermisoController::class, 'obtenerAccesos']);

    });

    Route::prefix('process')->group(function ()
    {
        Route::post('updatePermiso', [PermisoController::class, 'updatePermiso']);
        Route::post('asignarTratamiento', [PacienteController::class, 'asignarTratamiento']);
    });
});
