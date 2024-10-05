<?php

namespace Module\Base\Services\Interfaces;

interface PermisoServiceInterface
{
	public function getPermisoComponente($id);
	public function getPermiso($request);
	public function getAcceso($request);
    public function setStore($request);
    public function setDelete($id);
    public function setUpdate($request);
}
