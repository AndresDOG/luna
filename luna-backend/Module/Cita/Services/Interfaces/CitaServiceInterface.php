<?php

namespace Module\Cita\Services\Interfaces;

interface CitaServiceInterface
{
	public function store($request);
    public function getCita($id);
    public function getCitasAsig($request);
    public function getCitaPaci($id);
}
