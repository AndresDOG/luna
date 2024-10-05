<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
                ->middleware('guest')
                ->name('login');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth')
                ->name('logout');


// Autenticacion Manual
/*use App\Http\Controllers\AuthController;
Route::post('login', [AuthController::class, 'login'])
                ->middleware('guest')
                ->name('login');

Route::post('logout', [AuthController::class, 'logout'])
                ->middleware('auth')
                ->name('logout');*/
