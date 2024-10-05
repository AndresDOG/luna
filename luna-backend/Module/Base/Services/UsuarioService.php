<?php
namespace Module\Base\Services;

use Module\Base\Services\Interfaces\UsuarioServiceInterface;
use Module\Base\Repositories\Interfaces\UsuarioRepositoryInterface;

class UsuarioService implements UsuarioServiceInterface
{
    protected $repository;

    public function __construct(UsuarioRepositoryInterface $repository)
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
