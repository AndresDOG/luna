<?php

namespace Module\Cita\Services;

use Module\Cita\Services\Interfaces\CitaServiceInterface;
use Module\Cita\Repositories\Interfaces\CitaRepositoryInterface;

class CitaService implements CitaServiceInterface
{
    protected $repository;

    public function __construct(CitaRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function store($request)
    {
        return $this->repository->pdoStore($request);
    }

    public function getCita($id)
    {
        return $this->repository->pdoCita($id);
    }

    public function getCitasAsig($request)
    {
        return $this->repository->pdoCitasAsig($request);
    } 
}    