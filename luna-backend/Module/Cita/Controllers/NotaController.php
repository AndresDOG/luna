<?php
namespace Module\Cita\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Module\Cita\Services\NotaService;

class NotaController extends Controller
{
    protected $service;

    public function __construct(NotaService $service)
    {
        $this->service = $service;
    }

    public function show($id)
    {
        return $this->service->getAll($id);
    }

    public function store(Request $request)
    {
        return $this->service->setStore($request);
    }
}
