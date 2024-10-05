<?php

namespace Module\Base\Services\Interfaces;

interface TratamientoServiceInterface
{
	public function getAll();
	public function setStore($request);
	public function setUpdate($request, $id);
	public function setDelete($id);
}
