<?php

namespace Module\Cita\Providers;

use Illuminate\Support\ServiceProvider;

# Repositories
use Module\Cita\Repositories\CitaRepository;
use Module\Cita\Repositories\Interfaces\CitaRepositoryInterface;
use Module\Cita\Repositories\ListaRepository;
use Module\Cita\Repositories\Interfaces\ListaRepositoryInterface;

# Services
use Module\Cita\Services\CitaService;
use Module\Cita\Services\Interfaces\CitaServiceInterface;
use Modules\Cita\Services\ListaService;
use Modules\Cita\Services\Interfaces\ListaServiceInterface;

class CitaProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        # Repositories
        $this->app->bind(CitaRepositoryInterface::class,CitaRepository::class);  
        $this->app->bind(ListaRepositoryInterface::class,ListaRepository::class);

        # Services
        $this->app->bind(CitaServiceInterface::class,CitaService::class);      
        $this->app->bind(ListaServiceInterface::class,ListaService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
