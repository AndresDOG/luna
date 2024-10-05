<?php
namespace Module\Base\Services;

use Module\Base\Services\Interfaces\TarifaServiceInterface;
use Module\Base\Repositories\Interfaces\TarifaRepositoryInterface;



class TarifaService implements TarifaServiceInterface
{
    protected $repository;

    public function __construct(TarifaRepositoryInterface $repository)
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
