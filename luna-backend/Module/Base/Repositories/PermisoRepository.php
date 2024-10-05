<?php
namespace Module\Base\Repositories;

use Module\Base\Repositories\Interfaces\PermisoRepositoryInterface;
use Module\Base\Models\Permiso;
use Module\Base\Models\Componente;

class PermisoRepository implements PermisoRepositoryInterface
{
    protected $model;

    public function __construct(Permiso $model)
    {
        $this->model = $model;
    }
    
    public function pdoPermisoComponente($id) 
    {
        return Permiso::select('per_id_componente','per_acceso')->where('per_id_rol', $id)->get();
    }   

    public function pdoAll()
    {
        return $this->model->orderBy('per_id')->get();
    }

    public function pdoPermiso($request) 
    {
        try {
            $idRol = $request->id_rol;
            $permisos = Permiso::where('per_id_rol', $idRol)->get();

            return response()->json($permisos);
        } catch (\Exception $e) {
            
            return response()->json(['error' => 'Hubo un error al obtener los permisos por rol'], 500);
        }
    }

    public function pdoStore($request)
    {  

        try 
        {

            \DB::beginTransaction();

            $componente = Componente::find($request->per_id_componente);

            $permiso = new Permiso();
            $permiso->per_id_rol = $request->per_id_rol;
            $permiso->per_id_componente = $request->per_id_componente;
            $permiso->per_acceso = $componente->com_criterio;            
            $permiso->save();

           /* $permiso = Permiso::create([
                'per_id_rol' => $per_id_rol,
                'per_id_componente' => $per_id_componente
            ]);*/

            \DB::commit();
            
        } catch (\Exception $e) {
            \DB::rollback();
            return 0;
        }

        return $permiso->per_id;
    }

    

    public function pdoDelete($id)
    {
        try
        {
            $rs = $this->model->find($id);
            $rs->delete();

        } catch (\Exception $e) {
            return $e;
        }

        return 1;
    }

    public function pdoUpdate($request)
    {
        try {
            
            \DB::beginTransaction();
            $permiso = Permiso::where('per_id_rol', $request->id_rol)
                               ->where('per_id_componente', $request->id_componente)
                               ->update(['per_acceso' => $request->per_acceso]);

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return $e;
        }
        return 1;
    }

    public function pdoAcceso($request) 
    {
        $rs = Permiso::where('per_id_rol', $request->id_rol)
                           ->whereIn('per_id_componente', $request->id_componente)->get();

        //return response()->json($rs);
        return $rs;        
    }
}