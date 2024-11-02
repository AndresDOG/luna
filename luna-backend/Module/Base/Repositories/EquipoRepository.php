<?php
namespace Module\Base\Repositories;

use Module\Base\Repositories\Interfaces\EquipoRepositoryInterface;
use Module\Base\Models\Equipo;

class EquipoRepository implements EquipoRepositoryInterface
{
    protected $model;

    public function __construct(Equipo $model)
    {
        $this->model = $model;
    }

    public function pdoAll()
    {
        // Traer solo los equipos activos (equi_estado = 1)
        $equipos = $this->model->where('equi_estado', 1)->get();
        return $equipos;
    }

     public function pdoStore($request)
    {
        try {
            $this->model->create([
                'equi_nombre' => \Str::upper($request->equi_nombre),
                'equi_tipo' => $request->equi_tipo, // Asegúrate de asignar el tipo desde el request
                'equi_estado' => 1 // Asignar estado activo por defecto al crear
            ]);
        } catch (\Exception $e) {
            // Puedes agregar un log para ver el error específico
            return 0;
        }

        return 1;
    }

    public function pdoUpdate($request, $id)
    {
        \DB::beginTransaction();
        try {
            $rs = $this->model->find($id);

            if (!$rs) {
                return 0; // Manejar caso donde el equipo no se encuentra
            }

            // Asignar el valor del request a equi_nombre
            $rs->equi_nombre = \Str::upper($request->equi_nombre); // Asegúrate de que el nombre sea en mayúsculas
            $rs->equi_tipo = $request->equi_tipo; // Asigna el tipo desde el request
            $rs->equi_estado = $request->equi_estado;
            

            $rs->save();
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            // También puedes agregar un log aquí
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
