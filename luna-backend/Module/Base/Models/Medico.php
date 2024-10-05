<?php

namespace Module\Base\Models;

use Illuminate\Database\Eloquent\Model;
use Module\Base\Models\Tipo_Identidad;
use Module\Base\Models\Localidad;
use Module\Base\Models\Especialidad;


class Medico extends Model
{
    protected $table = 'medico';
    protected $primaryKey = 'med_id';

    protected $fillable = [
        'med_tipo_contacto','med_id_tipo_identidad','med_cedula','med_nombre','med_direccion','med_telefono','med_email','med_id_localidad','med_id_especializacion','med_estado'
    ];

    public function tipo_identidad()
    {
        return $this->hasOne(Tipo_Identidad::class,'tip_ide_id','med_id_tipo_identidad');
    }

    public function localidad()
    {
        return $this->hasOne(Localidad::class,'loc_id','med_id_localidad');
    }

    public function especialidad()
    {
        return $this->hasOne(Especialidad::class,'esp_id','med_id_especializacion');
    }

}
