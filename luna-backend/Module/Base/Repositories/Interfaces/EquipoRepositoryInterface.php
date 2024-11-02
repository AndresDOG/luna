<?php

namespace Module\Base\Repositories\Interfaces;

interface EquipoRepositoryInterface
{
	public function pdoAll();
	public function pdoStore($request);
	public function pdoUpdate($request, $id);
	public function pdoDelete($id);
}
