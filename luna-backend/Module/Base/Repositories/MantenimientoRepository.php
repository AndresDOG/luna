<?php

namespace Module\Base\Repositories;

use Module\Base\Repositories\Interfaces\MantenimientoRepositoryInterface;
use Module\Base\Models\Mantenimiento;
use Module\Base\Models\Equipo;
use Illuminate\Support\Facades\DB; // Asegúrate de importar el facade DB

class MantenimientoRepository implements MantenimientoRepositoryInterface
{
    protected $model;

    public function __construct(Mantenimiento $model)
    {
        $this->model = $model;
    }

    // Obtener todos los mantenimientos
    public function pdoAll()
    {
        return DB::select("
            SELECT M.mant_equi_id, M.mant_id,M.mant_fecha, M.mant_detalle, E.equi_nombre 
            FROM mantenimiento M 
            INNER JOIN equipo E ON E.equi_id = M.mant_equi_id
        ");
    }

        public function pdoStore($request)
        {
        try {
            return $this->model->create([
                'mant_fecha' => $request->mant_fecha,
                'mant_equi_id' => $request->mant_equi_id,
                'mant_detalle' => $request->mant_detalle,
            ]);
        } catch (\Exception $e) {
            // Manejo de errores: puedes registrar el error o lanzar una excepción
            \Log::error('Error al crear mantenimiento: ' . $e->getMessage());
            return null;
        }
        }

        // Actualizar un mantenimiento existente
        public function pdoUpdate($id, $request)
        {
        $mantenimiento = $this->model->find($id);

        if (!$mantenimiento) {
            return null; // O lanzar una excepción si prefieres
        }

        try {
            $mantenimiento->update([
                'mant_fecha' => $request->mant_fecha,
                'mant_equi_id' => $request->mant_equi_id,
                'mant_detalle' => $request->mant_detalle,
            ]);
            return $mantenimiento;
        } catch (\Exception $e) {
            // Manejo de errores: puedes registrar el error o lanzar una excepción
            \Log::error('Error al actualizar mantenimiento: ' . $e->getMessage());
            return null;
        }
        }


    // Eliminar un mantenimiento
    public function pdoDelete($id)
    {
        $mantenimiento = $this->model->find($id);

        if (!$mantenimiento) {
            return false; // O lanzar una excepción si prefieres
        }

        try {
            return $mantenimiento->delete();
        } catch (\Exception $e) {
            // Manejo de errores
            return false;
        }
    }
}
