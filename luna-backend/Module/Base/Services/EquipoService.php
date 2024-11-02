<?php
namespace Module\Base\Services;

use Illuminate\Http\Request;
use Module\Base\Services\Interfaces\EquipoServiceInterface;
use Module\Base\Repositories\Interfaces\EquipoRepositoryInterface;

class EquipoService implements EquipoServiceInterface
{
    protected $repository;

    public function __construct(EquipoRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->pdoAll();
    }

    public function setStore($request)
    {
        return $this->repository->pdoStore($request);
    }

    public function setUpdate($request, $id)
    {
        return $this->repository->pdoUpdate($request, $id);
    }

    public function setDelete($id)
    {
        return $this->repository->pdoDelete($id);
    }
}
