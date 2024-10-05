<?php
namespace Module\Base\Repositories;

use Module\Base\Repositories\Interfaces\TratamientoRepositoryInterface;
use Module\Base\Models\Tratamiento;

class TratamientoRepository implements TratamientoRepositoryInterface
{
    protected $model;

    public function __construct(Tratamiento $model)
    {
        $this->model = $model;
    }

    public function pdoAll()
    {
        $tratamientos = $this->model->all();
        return $tratamientos;
    }

    public function pdoStore($request)
    {
        try
        {
            $this->model->create(['tra_nombre' => \Str::upper($request->nombre)]);

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

            $rs->tra_nombre = \Str::upper($request->keys['nombre']);
            $rs->save();

            \DB::commit();

        } catch (\Exception $e) {
            \DB::rollback();
            return $e;
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
