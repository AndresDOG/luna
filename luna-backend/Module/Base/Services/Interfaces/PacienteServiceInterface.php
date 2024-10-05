<?php

namespace Module\Base\Services\Interfaces;

interface PacienteServiceInterface
{
	public function getAll($tipo);
	public function getOnly($id);
	public function getSome($id);
	public function getTercero($id);
	public function getProveedor($id);
	public function store($request);
	public function update($request, $id);
	public function setDestroy($id);
	public function getConsultaSimple($request);
	public function storeAsignarTratamiento($request);
	public function getTratamientos($pac_id);
}
