<?php
namespace Module\Base\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Module\Base\Services\EquipoService;

class EquipoController extends Controller
{
    protected $service;

    public function __construct(EquipoService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->getAll();
    }

    public function store(Request $request)
    {
        return $this->service->setStore($request);
    }

    public function update(Request $request, $id)
    {
        return $this->service->setUpdate($request, $id);
    }

    public function destroy($id)
    {
        return $this->service->setDelete($id);
    }
}
