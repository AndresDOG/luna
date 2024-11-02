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

        public function pdoUpdate($request, $id)
        {
            \DB::beginTransaction();
            try
            {
                // Buscar el mantenimiento por ID
                $mantenimiento = $this->model->find($id);

                if (!$mantenimiento) {
                    // Registra el error si no se encuentra el mantenimiento
                    \Log::error('Mantenimiento no encontrado con ID: ' . $id);
                    return 0; // O puedes lanzar una excepción
                }

                // Actualizar los campos del mantenimiento
                $mantenimiento->mant_fecha = $request->mant_fecha ?? $mantenimiento->mant_fecha;
                $mantenimiento->mant_equi_id = $request->mant_equi_id ?? $mantenimiento->mant_equi_id;
                $mantenimiento->mant_detalle = $request->mant_detalle ?? $mantenimiento->mant_detalle;

                // Guardar los cambios
                $mantenimiento->save();

                \DB::commit();
                return 1; // Retorna 1 si la actualización fue exitosa

            } catch (\Exception $e) {
                \DB::rollback();
                // Manejo de errores
                \Log::error('Error al actualizar mantenimiento: ' . $e->getMessage());
                return 0; // Retorna 0 si hubo un error
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
