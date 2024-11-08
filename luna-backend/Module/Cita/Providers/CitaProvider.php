<?php

namespace Module\Cita\Providers;

use Illuminate\Support\ServiceProvider;

# Repositories
use Module\Cita\Repositories\CitaRepository;
use Module\Cita\Repositories\Interfaces\CitaRepositoryInterface;
use Module\Cita\Repositories\ListaRepository;
use Module\Cita\Repositories\Interfaces\ListaRepositoryInterface;
use Module\Cita\Repositories\NotaRepository;
use Module\Cita\Repositories\Interfaces\NotaRepositoryInterface;
use Module\Cita\Repositories\EstadoCitaRepository;
use Module\Cita\Repositories\Interfaces\EstadoCitaRepositoryInterface;
# Services
use Module\Cita\Services\CitaService;
use Module\Cita\Services\Interfaces\CitaServiceInterface;
use Modules\Cita\Services\ListaService;
use Modules\Cita\Services\Interfaces\ListaServiceInterface;
use Modules\Cita\Services\NotaService;
use Modules\Cita\Services\Interfaces\NotaServiceInterface;
use Modules\Cita\Services\EstadoCitaService;
use Modules\Cita\Services\Interfaces\EstadoCitaServiceInterface;

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
        $this->app->bind(NotaRepositoryInterface::class,NotaRepository::class);
        $this->app->bind(EstadoCitaRepositoryInterface::class,EstadoCitaRepository::class);

        # Services
        $this->app->bind(CitaServiceInterface::class,CitaService::class);      
        $this->app->bind(ListaServiceInterface::class,ListaService::class);
        $this->app->bind(NotaServiceInterface::class,NotaService::class);
        $this->app->bind(EstadoCitaServiceInterface::class,EstadoCitaService::class);

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
