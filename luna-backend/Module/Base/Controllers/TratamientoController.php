<?php
namespace Module\Base\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Module\Base\Services\TratamientoService;

class TratamientoController extends Controller
{
    protected $service;

    public function __construct(TratamientoService $service)
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
