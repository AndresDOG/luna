<?php

namespace Module\Base\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente_Tratamiento extends Model
{
    protected $table = 'paciente_tratamiento';

    protected $primaryKey = ['pac_id', 'tra_id'];

    public $incrementing = false;

    protected $fillable = [
        'pac_id',
        'tra_id',
        'fec_inicio',
        'fec_fin',
        'estado'
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'pac_id');
    }

    public function tratamiento()
    {
        return $this->belongsTo(Tratamiento::class, 'tra_id');
    }
}