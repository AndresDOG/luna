<?php

namespace Module\Cita\Repositories\Interfaces;

interface NotaRepositoryInterface
{
	public function pdoAll($id);
	public function pdoStore($request);    
}