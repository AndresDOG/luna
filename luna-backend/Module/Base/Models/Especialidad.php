<?php

namespace Module\Base\Models;

use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    protected $table = 'especializacion';
    protected $primaryKey = 'esp_id';

    protected $fillable = [
        'esp_nombre'
    ];
}
