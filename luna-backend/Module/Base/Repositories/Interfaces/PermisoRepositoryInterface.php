<?php

namespace Module\Base\Repositories\Interfaces;

interface PermisoRepositoryInterface
{
	public function pdoPermisoComponente($id);
	public function pdoPermiso($request);
    public function pdoAcceso($request);
    public function pdoStore($request);
    public function pdoDelete($id);
    public function pdoUpdate($request);
}
