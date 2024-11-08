<?php
namespace Module\Cita\Models;

use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    protected $table = 'nota';
    protected $primaryKey = 'not_id';

    public function usuario()
    {
       return $this->hasOne(User::Class,'usu_id','not_id_usuario');
    }
}
