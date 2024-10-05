<?php

namespace Module\Base\Services\Interfaces;

interface RolServiceInterface
{
	public function getAll();
	public function setStore($request);
	public function setUpdate($request, $id);
	public function setDelete($id);
}
