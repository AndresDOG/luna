<?php

namespace Module\Base\Services\Interfaces;

interface MedicoServiceInterface
{
	public function getAll($tipo);
	public function getOnly($id);
	public function getSome($id);
	public function getFilter($request);
	public function getTercero($id);
	public function getProveedor($id);
	public function store($request);
	public function update($request, $id);
	public function setDestroy($id);
}
