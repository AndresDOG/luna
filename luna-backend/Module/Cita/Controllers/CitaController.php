<?php

namespace Module\Cita\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Module\Cita\Services\CitaService;

class CitaController extends Controller
{
    protected $service;

    public function __construct(CitaService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
        return $this->service->store($request);
    }

    public function cita($id)
    {
        return $this->service->getCita($id);
    }

    public function obtenerCitasAsign(Request $request)
    {
        return $this->service->getCitasAsig($request);
    } 

    public function obtenerCitaPaciente($id)
    {
        return $this->service->getCitaPaci($id);
    }

    public function cambiarEstado(Request $request)
    {
        return $this->service->setCambiarEstado($request);
    }
}
