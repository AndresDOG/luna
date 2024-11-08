<?php
namespace Module\Cita\Repositories;

use Module\Cita\Repositories\Interfaces\EstadoCitaRepositoryInterface;
use App\Models\User;
use Module\Cita\Models\Estado_Cita;
use Illuminate\Support\Facades\DB;

class EstadoCitaRepository implements EstadoCitaRepositoryInterface
{
    
    public function pdoAll()
    {
        return Estado_Cita::all();
    }

}
