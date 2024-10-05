<?php
namespace Module\Base\Repositories;

use Module\Base\Repositories\Interfaces\ComponenteRepositoryInterface;
use Module\Base\Models\Componente;

class ComponenteRepository implements ComponenteRepositoryInterface
{
    protected $model;

    public function __construct(Componente $model)
    {
        $this->model = $model;
    }

    public function pdoAll()
    {
        return $this->model::where('com_activo', 1)->orderBy('com_id')->get();
    }


    public function pdoMenu()
    {
        return $this->model::whereNotNull('com_path')->where('com_activo', 1)->get();
    }

}