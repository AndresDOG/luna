<?php
namespace Module\Cita\Repositories;

use Module\Cita\Repositories\Interfaces\ListaRepositoryInterface;
use App\Models\User;
use Module\Cita\Models\Tipo_Cita;
use Module\Cita\Models\Lugar;
use Illuminate\Support\Facades\DB;

class ListaRepository implements ListaRepositoryInterface
{
    
    public function pdoTipoCita()
    {
        return Tipo_Cita::all();
    }

}
