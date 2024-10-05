<?php
namespace Module\Base\Repositories;

use Module\Base\Repositories\Interfaces\EspecialidadRepositoryInterface;
use Module\Base\Models\Especialidad;

class EspecialidadRepository implements EspecialidadRepositoryInterface
{
    protected $model;

    public function __construct(Especialidad $model)
    {
        $this->model = $model;
    }

    public function pdoAll()
    {
        $especialidades = $this->model->all();
        return $especialidades;
    }

    public function pdoStore($request)
    {
        try
        {
            $this->model->create(['esp_nombre' => \Str::upper($request->nombre)]);

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

            $rs->esp_nombre = \Str::upper($request->keys['nombre']);
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
