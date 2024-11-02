<?php

namespace Module\Base\Repositories\Interfaces;

interface MantenimientoRepositoryInterface
{
	public function pdoAll();
	public function pdoStore($request);
	public function pdoUpdate($request, $id);
	public function pdoDelete($id);
}
