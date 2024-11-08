<?php
namespace Module\Base\Repositories;

use Module\Base\Repositories\Interfaces\MedicoRepositoryInterface;
use Module\Base\Models\Medico;

class MedicoRepository implements MedicoRepositoryInterface
{
    protected $model;

    public function __construct(Medico $model)
    {
        $this->model = $model;
    }

    public function pdoAll()
    {
        return $this->model->where('med_estado', 1) ->get();  // Condición para estado activo
    }

    public function pdoOnly($id)
    {
        return $this->model->where('med_cedula', '=', $id)
                           ->where('med_estado', 1)  // Condición para estado activo
                           ->with(['localidad'])
                           ->get();
    }

    public function pdoSome($id)
    {
        return $this->model->where(function($query) use ($id) {
                                $query->where('med_cedula', 'like', '%'.$id.'%')
                                      ->orWhere('med_nombre', 'like', '%'.$id.'%');
                            })
                           ->where('med_estado', 1)  // Condición para estado activo
                           ->with(['tipo_identidad', 'localidad', 'especialidad'])
                           ->get();
    }

    public function pdoFilter($request)
    {
        $criterio = str_replace("'", "", $request);
        return $this->model->where(function($query) use ($criterio) {
                                $query->where('med_cedula', 'like', '%'.$criterio.'%')
                                      ->orWhere('med_nombre', 'like', '%'.$criterio.'%');
                            })
                           ->where('med_estado', 1)  // Condición para estado activo
                           ->with(['tipo_identidad', 'localidad', 'especialidad'])
                           ->get();
    }

    public function pdoTercero($id)
    {
        return $this->model->where('med_nombre', 'like', '%'.$id.'%')
                           ->where('med_estado', 1)  // Condición para estado activo
                           ->select('med_id', 'med_cedula', 'med_nombre')
                           ->get();
    }

    public function pdoProveedor($id)
    {
        return $this->model->where('med_nombre', 'like', '%'.$id.'%')
                           ->whereIn('med_tipo_contacto', [1,2])
                           ->where('med_estado', 1)  // Condición para estado activo
                           ->select('med_id', 'med_cedula', 'med_nombre')
                           ->get();
    }

    public function pdoStore($request)
    {
        try
        {
            $medico = $this->model->create([
                'med_id_tipo_identidad' => $request->keys['tipo_identidad'],
                'med_tipo_contacto' => $request->keys['tipo_contacto']-1,
                'med_cedula' => $request->keys['cedula'],
                'med_nombre' => \Str::upper($request->keys['nombre']),
                'med_direccion' => \Str::upper($request->keys['direccion']),
                'med_telefono' => $request->keys['telefono'],
                'med_id_localidad' => $request->keys['localidad'],
                'med_id_especializacion' => $request->keys['especialidad'],
                'med_email' => $request->keys['mail'],
                'med_estado' => 1]);

        } catch (\Exception $e) {
            return $e;
        }

        return $medico->med_id;
    }

    public function pdoUpdate($request, $id)
    {
        try
        {
            $this->model->where('med_id',$id)->update([
                'med_tipo_contacto' => $request->keys['tipo_contacto']-1,
                'med_id_tipo_identidad' => $request->keys['tipo_identidad'],
                'med_cedula' => $request->keys['cedula'],
                'med_nombre' => \Str::upper($request->keys['nombre']),
                'med_direccion' => \Str::upper($request->keys['direccion']),
                'med_telefono' => $request->keys['telefono'],
                'med_id_localidad' => $request->keys['localidad'],
                'med_id_especializacion' => $request->keys['especialidad'],
                'med_email' => $request->keys['mail'] ]);

        } catch (\Exception $e) {
            return $e;
        }

        return 1;

    }

    public function pdoDestroy($id)
    {
        try
        {
            $rs = $this->model->find($id);

            if ($rs) {
                $rs->med_estado = 5;
                $rs->save();
            }

        } catch (\Exception $e) {
            return 0;  
        }

        return 1;  
    }

}
