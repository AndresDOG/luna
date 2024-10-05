<?php

namespace Module\Base\Services;

use Module\Base\Services\Interfaces\PacienteServiceInterface;
use Module\Base\Repositories\Interfaces\PacienteRepositoryInterface;

class PacienteService implements PacienteServiceInterface
{
    protected $repository;

    public function __construct(PacienteRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll($tipo)
    {
        return $this->repository->pdoAll($tipo);
    }

    public function getOnly($id)
    {
        return $this->repository->pdoOnly($id);
    }

    public function getSome($id)
    {
        return $this->repository->pdoSome($id);
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

    public function getConsultaSimple($request)
    {
        return $this->repository->pdoConsultaSimple($request);
    }

    public function storeAsignarTratamiento($request)
    {
        return $this->repository->pdoAsignarTratamiento($request);
    }

    public function getTratamientos($pac_id)
    {
        return $this->repository->pdoGetTratamientos($pac_id);
    }
}
