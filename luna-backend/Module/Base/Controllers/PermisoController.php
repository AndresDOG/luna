<?php

namespace Module\Base\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Module\Base\Services\PermisoService;

class PermisoController extends Controller
{
    protected $service;

    public function __construct(PermisoService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->getAll();
    }

    public function obtenerPermisos(Request $request)
    {
        return $this->service->getPermiso($request);
    }

    public function obtenerAccesos(Request $request)
    {
        return $this->service->getAcceso($request);
    }

    public function store(Request $request)
    {
        return $this->service->setStore($request);
    }

    public function destroy($id)
    {
        return $this->service->setDelete($id);
    }

    public function permisoComponente($id)
    {
        return $this->service->getPermisoComponente($id);
    }

    public function updatePermiso(Request $request)
    {
        return $this->service->setUpdate($request);
    }

}