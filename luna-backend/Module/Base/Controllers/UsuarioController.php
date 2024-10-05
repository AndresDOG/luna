<?php
namespace Module\Base\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Base\Services\UsuarioService;

class UsuarioController extends Controller
{
    protected $service;

    public function __construct(UsuarioService $service)
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
