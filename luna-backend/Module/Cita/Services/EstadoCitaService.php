<?php

namespace Module\Cita\Services;

use Module\Cita\Services\Interfaces\EstadoCitaServiceInterface;
use Module\Cita\Repositories\Interfaces\EstadoCitaRepositoryInterface;

class EstadoCitaService implements EstadoCitaServiceInterface
{
    protected $repository;

    public function __construct(EstadoCitaRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }   

    public function getEstadoCita()
    {
        return $this->repository->pdoAll();
    }

}
