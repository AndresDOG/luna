<?php
namespace Module\Base\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Module\Base\Services\ComponenteService;

class ComponenteController extends Controller
{
    protected $service;

    public function __construct(ComponenteService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->getAll();
    }

    public function traerMenu()
    {
        return $this->service->getMenu();
    }   

}