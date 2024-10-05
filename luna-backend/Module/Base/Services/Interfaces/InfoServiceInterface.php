<?php
namespace Module\Base\Services\Interfaces;

interface InfoServiceInterface
{
    public function getUsuarioActivo();	
    public function getMenu();
    public function getLocalidad($id);
    public function getTipoIdentidad();
    public function getDepartamento();
}