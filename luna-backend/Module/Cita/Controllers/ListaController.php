<?php

namespace Module\Cita\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Module\Cita\Services\ListaService;

class ListaController extends Controller
{
    protected $service;

    public function __construct(ListaService $service)
    {
        $this->service = $service;
    }   

    public function tipoCita()
    {
        return $this->service->getTipoCita();
    }

}
