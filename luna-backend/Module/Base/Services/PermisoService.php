<?php
namespace Module\Base\Services;

use Illuminate\Http\Request;
use Module\Base\Services\Interfaces\PermisoServiceInterface;
use Module\Base\Repositories\Interfaces\PermisoRepositoryInterface;


class PermisoService implements PermisoServiceInterface
{
    protected $repository;

    public function __construct(PermisoRepositoryInterface $repository)
    {
       $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function getPermiso($request)
    {
        return $this->repository->pdoPermiso($request);
    }

    public function getAcceso($request)
    {
        return $this->repository->pdoAcceso($request);
    }

    public function setStore($request)
    {
        return $this->repository->pdoStore($request);
    }
    public function setDelete($id)
    {
        return $this->repository->pdoDelete($id);
    }

    public function getPermisoComponente($id)
    {
        return $this->repository->pdoPermisoComponente($id);
    }

    public function setUpdate($request)
    {
        return $this->repository->pdoUpdate($request);
    }


}