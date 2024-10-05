<?php

namespace Module\Base\Repositories\Interfaces;

interface PacienteRepositoryInterface
{
	public function pdoAll($tipo);
	public function pdoOnly($id);
	public function pdoSome($id);
	public function pdoTercero($id);
	public function pdoProveedor($id);
	public function pdoStore($request);
	public function pdoUpdate($request, $id);
	public function pdoDestroy($id);
	public function pdoAsignarTratamiento($request);
	public function pdoGetTratamientos($pac_id);
}
