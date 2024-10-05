<?php

namespace Module\Base\Repositories\Interfaces;

interface UsuarioRepositoryInterface
{
	public function pdoAll();
	public function pdoStore($request);
	public function pdoUpdate($request, $id);
	public function pdoDelete($id);
}
