<?php
namespace Module\Base\Services;

use Illuminate\Http\Request;
use Module\Base\Services\Interfaces\RolServiceInterface;
use Module\Base\Repositories\Interfaces\RolRepositoryInterface;

class RolService implements RolServiceInterface
{
    protected $repository;

    public function __construct(RolRepositoryInterface $repository)
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
