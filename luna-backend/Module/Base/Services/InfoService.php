<?php

namespace Module\Base\Services;

use Module\Base\Services\Interfaces\InfoServiceInterface;
use Module\Base\Repositories\Interfaces\InfoRepositoryInterface;

class InfoService implements InfoServiceInterface
{
    protected $repository;

    public function __construct(InfoRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getUsuarioActivo()
    {
        return $this->repository->pdoUsuarioActivo();
    }

    public function getMenu()
    {
        return $this->repository->pdoMenu();
    }

    public function getLocalidad($id)
    {
        return $this->repository->pdoLocalidad($id);
    }

    public function getTipoIdentidad()
    {
        return $this->repository->pdoTipoIdentidad();
    }

    public function getDepartamento()
    {
        return $this->repository->pdoDepartamento();
    }
} 