<?php
namespace Module\Base\Repositories\Interfaces;

interface InfoRepositoryInterface
{
    public function pdoUsuarioActivo();	
    public function pdoEmpresa();
    public function pdoMenu();
    public function pdoLocalidad($id);
    public function pdoTipoIdentidad();
    public function pdoDepartamento();
}