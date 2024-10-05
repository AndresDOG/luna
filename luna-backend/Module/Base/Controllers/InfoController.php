<?php

namespace Module\Base\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Module\Base\Services\InfoService;

class InfoController extends Controller
{
    protected $service;

    public function __construct(InfoService $service)
    {
        $this->service = $service;
    }

    public function UsuarioActivo()
    {
        return $this->service->getUsuarioActivo();
    }  

    public function Menu()
    {
        return $this->service->getMenu();
    }

    public function localidad($id)
    {
        return $this->service->getLocalidad($id);
    }

    public function tipoIdentidad()
    {
        return $this->service->getTipoIdentidad();
    }

    public function departamento()
    {
        return $this->service->getDepartamento();
    }

}