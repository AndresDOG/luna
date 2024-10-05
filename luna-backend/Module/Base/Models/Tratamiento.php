<?php

namespace Module\Base\Models;

use Illuminate\Database\Eloquent\Model;

class Tratamiento extends Model
{
    protected $table = 'tratamiento';
    protected $primaryKey = 'tra_id';

    protected $fillable = [
        'tra_nombre'
    ];
}
