<?php

namespace Module\Cita\Services;

use Module\Cita\Services\Interfaces\NotaServiceInterface;
use Module\Cita\Repositories\Interfaces\NotaRepositoryInterface;

class NotaService implements NotaServiceInterface
{
    protected $repository;

    public function __construct(NotaRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll($id)
    {
        return $this->repository->pdoAll($id);
    }

    public function setStore($request)
    {
        return $this->repository->pdoStore($request);
    }
} 