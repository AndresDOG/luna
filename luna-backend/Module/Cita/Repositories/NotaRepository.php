<?php
namespace Module\Cita\Repositories;

use Module\Cita\Repositories\Interfaces\NotaRepositoryInterface;
use Module\Cita\Models\Nota;

class NotaRepository implements NotaRepositoryInterface
{
    protected $model;

    public function __construct(Nota $model)
    {
        $this->model = $model;
    }

        public function pdoAll($id)
    {
        $user = auth()->user();
        $rol_tipo = $user->rol->rol_tipo;
        $criterio = $rol_tipo <= 2 ? '%' : 1;

        $rs = Nota::select('not_id', 'not_detalle', 'not_fecha', 'not_id_usuario','usu_nombre')
            ->join('usuario', 'usu_id', 'not_id_usuario')
            ->where('not_id_cita', $id)
            ->where('not_status', 'like', $criterio)
            ->orderBy('not_fecha', 'DESC')
            ->get();

        return $rs;
    }


    public function pdoStore($request)
    {
        \DB::beginTransaction();
        try
        {
            $nota = new Nota();
            $nota->not_id_cita = $request->id;
            $nota->not_id_usuario  = \Auth::user()->usu_id;
            $nota->not_detalle = \Str::upper($request->nota);
            $nota->not_status = $request->status;
            $nota->save();

            \DB::commit();

        } catch (\Exception $e) {
            \DB::rollback();
            return 0;
        }

        return 1;
    }
}
