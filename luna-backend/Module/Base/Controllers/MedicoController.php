<?php

namespace Module\Base\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Module\Base\Services\MedicoService;;

class MedicoController extends Controller
{
    protected $service;

    public function __construct(MedicoService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->getAll();
    }

    public function medico($id)
    {
        return $this->service->getOnly($id);
    }

    public function medicov($id)
    {
        return $this->service->getSome($id);
    }

    public function medicof(Request $request)
    {
        return $this->service->getFilter($request->criterio);
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
}
