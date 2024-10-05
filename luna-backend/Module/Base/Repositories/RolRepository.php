<?php
namespace Module\Base\Repositories;

use Module\Base\Repositories\Interfaces\RolRepositoryInterface;
use Module\Base\Models\Rol;

class RolRepository implements RolRepositoryInterface
{
    protected $model;

    public function __construct(Rol $model)
    {
        $this->model = $model;
    }

    public function pdoAll()
    {
        $rol_tipo = \Auth::user()->rol->rol_tipo;
        if ($rol_tipo === 1) // Acceso SuperUsuario
        {
            return $this->model->orderBy('rol_nombre')->get();
        }
        if ($rol_tipo >= 2) 
        {
            return $this->model->where('rol_tipo','>=',2)->orderBy('rol_nombre')->get();
        }
    }

    public function pdoStore($request)
    {
        try
        {
            $this->model->create(['rol_nombre' => \Str::upper($request->nombre),
                                  'rol_tipo' => $request->tipo]);

        } catch (\Exception $e) {
            return 0;
        }

        return 1;
    }

    public function pdoUpdate($request, $id)
    {
        \DB::beginTransaction();
        try
        {
            $rs = $this->model->find($id);

            $rs->rol_nombre = \Str::upper($request->keys['nombre']);
            $rs->rol_tipo = $request->keys['tipo'];
            $rs->save();

            \DB::commit();

        } catch (\Exception $e) {
            \DB::rollback();
            return 0;
        }

        return 1;
    }

    public function pdoDelete($id)
    {
        try
        {
            $rs = $this->model->find($id);
            $rs->delete();

        } catch (\Exception $e) {
            return 0;
        }

        return 1;
    }

}
