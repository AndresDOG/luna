<?php

namespace Module\Base\Models;

use Illuminate\Database\Eloquent\Model;
use Module\Base\Models\Tipo_Identidad;
use Module\Base\Models\Localidad;
use Module\Base\Models\Especialidad;


class Paciente extends Model
{
    protected $table = 'paciente';
    protected $primaryKey = 'pac_id';

    protected $fillable = [
        'pac_tipo_contacto','pac_id_tipo_identidad','pac_cedula','pac_nombre','pac_fecha_nacimiento','pac_direccion','pac_telefono','pac_email','pac_id_localidad','pac_id_tratamiento','pac_estado'
    ];

    public function tipo_identidad()
    {
        return $this->hasOne(Tipo_Identidad::class,'tip_ide_id','pac_id_tipo_identidad');
    }

    public function localidad()
    {
        return $this->hasOne(Localidad::class,'loc_id','pac_id_localidad');
    }

    
}
