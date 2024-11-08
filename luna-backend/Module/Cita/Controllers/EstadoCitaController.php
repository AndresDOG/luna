<?php

namespace Module\Cita\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Module\Cita\Services\EstadoCitaService;

class EstadoCitaController extends Controller
{
    protected $service;

    public function __construct(EstadoCitaService $service)
    {
        $this->service = $service;
    }   

    public function index()
    {
        return $this->service->getEstadoCita();
    }

}
