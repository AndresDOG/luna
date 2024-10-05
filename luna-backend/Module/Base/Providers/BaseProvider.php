<?php

namespace Module\Base\Providers;

use Illuminate\Support\ServiceProvider;

# Repositories
use Module\Base\Repositories\InfoRepository;
use Module\Base\Repositories\PermisoRepository;
use Module\Base\Repositories\MedicoRepository;
use Module\Base\Repositories\TarifaRepository;
use Module\Base\Repositories\UsuarioRepository;
use Module\Base\Repositories\RolRepository;
use Module\Base\Repositories\ComponenteRepository;
use Module\Base\Repositories\EspecialidadRepository;
use Module\Base\Repositories\TratamientoRepository;
use Module\Base\Repositories\PacienteRepository;

use Module\Base\Repositories\Interfaces\InfoRepositoryInterface;
use Module\Base\Repositories\Interfaces\PermisoRepositoryInterface;
use Module\Base\Repositories\Interfaces\MedicoRepositoryInterface;
use Module\Base\Repositories\Interfaces\TarifaRepositoryInterface;
use Module\Base\Repositories\Interfaces\UsuarioRepositoryInterface;
use Module\Base\Repositories\Interfaces\RolRepositoryInterface;
use Module\Base\Repositories\Interfaces\ComponenteRepositoryInterface;
use Module\Base\Repositories\Interfaces\EspecialidadRepositoryInterface;
use Module\Base\Repositories\Interfaces\TratamientoRepositoryInterface;
use Module\Base\Repositories\Interfaces\PacienteRepositoryInterface;


# Services
use Module\Base\Services\InfoService;
use Module\Base\Services\PermisoService;
use Module\Base\Services\MedicoService;
use Module\Base\Services\TarifaService;
use Module\Base\Services\UsuarioService;
use Module\Base\Services\RolService;
use Module\Base\Services\ComponenteService;
use Module\Base\Services\EspecialidadService;
use Module\Base\Services\TratamientoService;
use Module\Base\Services\PacienteService;


use Module\Base\Services\Interfaces\InfoServiceInterface;
use Module\Base\Services\Interfaces\PermisoServiceInterface;
use Module\Base\Services\Interfaces\MedicoServiceInterface;
use Module\Base\Services\Interfaces\TarifaServiceInterface;
use Module\Base\Services\Interfaces\UsuarioServiceInterface;
use Module\Base\Services\Interfaces\RolServiceInterface;
use Module\Base\Services\Interfaces\ComponenteServiceInterface;
use Module\Base\Services\Interfaces\EspecialidadServiceInterface;
use Module\Base\Services\Interfaces\TratamientoServiceInterface;
use Module\Base\Services\Interfaces\PacienteServiceInterface;


class BaseProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        # Repositories
        $this->app->bind(InfoRepositoryInterface::class,InfoRepository::class);
        $this->app->bind(PermisoRepositoryInterface::class,PermisoRepository::class);
        $this->app->bind(MedicoRepositoryInterface::class,MedicoRepository::class);
        $this->app->bind(TarifaRepositoryInterface::class,TarifaRepository::class);
        $this->app->bind(UsuarioRepositoryInterface::class,UsuarioRepository::class);
        $this->app->bind(RolRepositoryInterface::class,RolRepository::class);
        $this->app->bind(ComponenteRepositoryInterface::class,ComponenteRepository::class);
        $this->app->bind(EspecialidadRepositoryInterface::class,EspecialidadRepository::class);
        $this->app->bind(TratamientoRepositoryInterface::class,TratamientoRepository::class);
        $this->app->bind(PacienteRepositoryInterface::class,PacienteRepository::class);


        # Services
        $this->app->bind(InfoServiceInterface::class,InfoService::class);
        $this->app->bind(PermisoServiceInterface::class,PermisoService::class);
        $this->app->bind(MedicoServiceInterface::class,MedicoService::class);
        $this->app->bind(TarifaServiceInterface::class,TarifaService::class);
        $this->app->bind(UsuarioServiceInterface::class,UsuarioService::class);
        $this->app->bind(RolServiceInterface::class,RolService::class);
        $this->app->bind(ComponenteServiceInterface::class,ComponenteService::class);
        $this->app->bind(EspecialidadServiceInterface::class,EspecialidadService::class);
        $this->app->bind(TratamientoServiceInterface::class,TratamientoService::class);
        $this->app->bind(PacienteServiceInterface::class,PacienteService::class);



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
