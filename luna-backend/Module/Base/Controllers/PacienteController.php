<?php

namespace Module\Base\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Module\Base\Services\PacienteService;;

class PacienteController extends Controller
{
    protected $service;

    public function __construct(PacienteService $service)
    {
        $this->service = $service;
    }

    public function show($tipo)
    {
        return $this->service->getAll($tipo);
    }

    public function paciente($id)
    {
        return $this->service->getOnly($id);
    }

    public function pacientev($id)
    {
        return $this->service->getSome($id);
    }

    public function tercero($id)
    {
        return $this->service->getTercero($id);
    }

    public function proveedor($id)
    {
        return $this->service->getProveedor($id);
    }

    public function store(Request $request)
    {
        return $this->service->store($request);
    }

    public function update(Request $request, $id)
    {
        return $this->service->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->service->setDestroy($id);
    }

    public function consultaSimple(Request $request)
    {
        return $this->service->getConsultaSimple($request);
    }

    public function asignarTratamiento(Request $request)
    {
        return $this->service->storeAsignarTratamiento($request);
    }

    public function obtenerTratamientos($pac_id)
    {
        return $this->service->getTratamientos($pac_id);
    }
}
