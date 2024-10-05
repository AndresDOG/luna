<?php

namespace Module\Cita\Models;

use Illuminate\Database\Eloquent\Model;

use Module\Base\Models\Paciente;
use Module\Cita\Models\Tipo_Cita;
use Module\Base\Models\Medico;


class Cita extends Model
{
    protected $table = 'cita';
    protected $primaryKey = 'cita_id';    

    public function paciente()
    {
       return $this->hasOne(Paciente::Class,'pac_id','cita_paciente_id')->with('localidad');;
    }

    public function tipoCita()
    {
       return $this->hasOne(Tipo_Cita::Class,'tip_cita_id','cita_id_tipo_cita');
    }

     public function medico()
    {
        return $this->hasOne(Medico::Class,'med_id','cita_medico_id');
    }
}
