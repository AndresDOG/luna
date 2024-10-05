<?php

namespace Module\Base\Services\Interfaces;

interface EspecialidadServiceInterface
{
	public function getAll();
	public function setStore($request);
	public function setUpdate($request, $id);
	public function setDelete($id);
}
