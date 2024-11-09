<?php

namespace Module\Cita\Repositories\Interfaces;

interface CitaRepositoryInterface
{
    public function pdoStore($request);
    public function pdoCita($id);
    public function pdoCitasAsig($request);
    public function pdoCambiarEstado($request);
    public function pdoCitaPaci($id);
    public function pdoActualizarCita($request,$id);
    public function pdoConsultaSimple($request);
}
