<?php

namespace Module\Cita\Services\Interfaces;

interface CitaServiceInterface
{
	public function store($request);
    public function getCita($id);
    public function getCitasAsig($request);
    public function setCambiarEstado($request);
    public function setActualizarCita($request, $id);
    public function getConsultaSimple($request);
    public function getCitaPaci($id);
}
