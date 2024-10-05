<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../app/routes/web.php',
        api: __DIR__.'/../app/routes/api.php',
        health: '/up',
        then: function ()
        {
            Route::middleware('api')->prefix('api')->group(base_path('module/base/route.php'));
            Route::middleware('api')->prefix('api')->group(base_path('module/cita/route.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->statefulApi();

        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
