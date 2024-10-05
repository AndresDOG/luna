<?php

namespace Module\Base\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'permiso';
    protected $primaryKey = 'per_id';
    protected $fillable = ['per_id_rol', 'per_id_componente','per_acceso'];
}
