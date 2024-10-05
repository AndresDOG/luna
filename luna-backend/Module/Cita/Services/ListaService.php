<?php

namespace Module\Cita\Services;

use Module\Cita\Services\Interfaces\ListaServiceInterface;
use Module\Cita\Repositories\Interfaces\ListaRepositoryInterface;

class ListaService implements ListaServiceInterface
{
    protected $repository;

    public function __construct(ListaRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }   

    public function getTipoCita()
    {
        return $this->repository->pdoTipoCita();
    }

}
