<?php

namespace Module\Base\Services;

use Module\Base\Services\Interfaces\MedicoServiceInterface;
use Module\Base\Repositories\Interfaces\MedicoRepositoryInterface;

class MedicoService implements MedicoServiceInterface
{
    protected $repository;

    public function __construct(MedicoRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->pdoAll();
    }

    public function getOnly($id)
    {
        return $this->repository->pdoOnly($id);
    }

    public function getSome($id)
    {
        return $this->repository->pdoSome($id);
    }

    public function getFilter($request)
    {
        return $this->repository->pdoFilter($request);
    }

    public function getTercero($id)
    {
        return $this->repository->pdoTercero($id);
    }

    public function getProveedor($id)
    {
        return $this->repository->pdoProveedor($id);
    }

    public function store($request)
    {
        return $this->repository->pdoStore($request);
    }

    public function update($request, $id)
    {
        return $this->repository->pdoUpdate($request, $id);
    }

    public function setDestroy($id)
    {
        return $this->repository->pdoDestroy($id);
    }
}
