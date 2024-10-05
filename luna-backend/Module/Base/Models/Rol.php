<?php

namespace Module\Base\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'rol';
    protected $primaryKey = 'rol_id';

    protected $fillable = [
        'rol_nombre', 'rol_tipo'
    ];
}