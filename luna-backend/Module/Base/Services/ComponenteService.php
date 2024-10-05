<?php
namespace Module\Base\Services;

use Illuminate\Http\Request;
use Module\Base\Repositories\Interfaces\ComponenteRepositoryInterface;
use Module\Base\Services\Interfaces\ComponenteServiceInterface;


class ComponenteService implements ComponenteServiceInterface
{
    protected $repository;

    public function __construct(ComponenteRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->pdoAll();
    }

    public function getMenu()
    {
        return $this->repository->pdoMenu();
    }   

}
