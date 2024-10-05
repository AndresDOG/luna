<?php
namespace Module\Base\Repositories;

use Module\Base\Repositories\Interfaces\PacienteRepositoryInterface;
use Module\Base\Models\Paciente;
use Module\Base\Models\Paciente_Tratamiento;
use Illuminate\Support\Facades\DB;

class PacienteRepository implements PacienteRepositoryInterface
{
    protected $model;

    public function __construct(Paciente $model)
    {
        $this->model = $model;
    }

    public function pdoAll($tipo)
    {
        return $this->model->with(['tipo_identidad','localidad'])
                           ->where('pac_tipo_contacto', $tipo)
                           ->where('pac_estado', 1)  // Condición para estado activo
                           ->get();
    }

    public function pdoOnly($id)
    {
        return $this->model->where('pac_cedula', '=', $id)
                           ->where('pac_estado', 1)  // Condición para estado activo
                           ->with(['localidad'])
                           ->get();
    }

    public function pdoSome($id)
    {
        return $this->model->where(function($query) use ($id) {
                                $query->where('pac_cedula', 'like', '%'.$id.'%')
                                      ->orWhere('pac_nombre', 'like', '%'.$id.'%');
                            })
                           ->where('pac_estado', 1)  // Condición para estado activo
                           ->with(['tipo_identidad', 'localidad'])
                           ->get();
    }

    public function pdoTercero($id)
    {
        return $this->model->where('pac_nombre', 'like', '%'.$id.'%')
                           ->where('pac_estado', 1)  // Condición para estado activo
                           ->select('pac_id', 'pac_cedula', 'pac_nombre')
                           ->get();
    }

    public function pdoProveedor($id)
    {
        return $this->model->where('pac_nombre', 'like', '%'.$id.'%')
                           ->whereIn('pac_tipo_contacto', [1,2])
                           ->where('pac_estado', 1)  // Condición para estado activo
                           ->select('pac_id', 'pac_cedula', 'pac_nombre')
                           ->get();
    }

    public function pdoStore($request)
    {
        try
        {
            $paciente = $this->model->create([
                'pac_id_tipo_identidad' => $request->keys['tipo_identidad'],
                'pac_tipo_contacto' => $request->keys['tipo_contacto']-1,
                'pac_cedula' => $request->keys['cedula'],
                'pac_nombre' => \Str::upper($request->keys['nombre']),
                'pac_direccion' => \Str::upper($request->keys['direccion']),
                'pac_telefono' => $request->keys['telefono'],
                'pac_id_localidad' => $request->keys['localidad'],
                'pac_id_tratamiento' => $request->keys['tratamiento'],
                'pac_email' => $request->keys['mail'],
                'pac_estado' => 1]);

        } catch (\Exception $e) {
            return $e;
        }

        return $paciente->med_id;
    }

    public function pdoUpdate($request, $id)
    {
        try
        {
            $this->model->where('pac_id',$id)->update([
                'pac_tipo_contacto' => $request->keys['tipo_contacto']-1,
                'pac_id_tipo_identidad' => $request->keys['tipo_identidad'],
                'pac_cedula' => $request->keys['cedula'],
                'pac_nombre' => \Str::upper($request->keys['nombre']),
                'pac_direccion' => \Str::upper($request->keys['direccion']),
                'pac_telefono' => $request->keys['telefono'],
                'pac_id_localidad' => $request->keys['localidad'],
                'pac_id_tratamiento' => $request->keys['tratamiento'],
                'pac_email' => $request->keys['mail'] ]);

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
                $rs->pac_estado = 5;
                $rs->save();
            }

        } catch (\Exception $e) {
            return 0;
        }

        return 1;
    }

    public function pdoConsultaSimple($request)
    {
        $parametro = $request->parametro;
        $criterio = $request->criterio;

        $rs = Paciente::query();

        if ($criterio == 1)
        {
            $rs->where('pac_cedula', $parametro);
        }
        if ($criterio == 2)
        {
            $rs->where('pac_nombre', 'like', '%' . strtoupper($parametro) . '%');
        }
        if ($criterio == 3)
        {
            $rs->where('pac_telefono', 'like', '%' . $parametro . '%');
        }

        return $rs->get();

        if ($rs->count() == 0) {
            return 0;
        }
    }

    public function pdoAsignarTratamiento($request)
    {
        try {
            DB::beginTransaction();

            $pacienteTratamiento = new Paciente_Tratamiento();
            $pacienteTratamiento->pac_id = $request->paciente;
            $pacienteTratamiento->tra_id = $request->tratamiento;
            $pacienteTratamiento->fec_inicio = $request->fechaInicio;
            $pacienteTratamiento->fec_fin = $request->fechaFin;
            $pacienteTratamiento->estado = 1;
            $pacienteTratamiento->save();

            DB::commit();

        } catch (\Exception $e) {
            return 0;
        }

        return 1;
    }

    public function pdoGetTratamientos($pac_id)
    {
        $tratamientos = DB::table('paciente_tratamiento as a')
            ->join('tratamiento as c', 'c.tra_id', '=', 'a.tra_id')
            ->select('c.tra_nombre','c.tra_id', 'a.fec_inicio', 'a.fec_fin', 'a.estado')
            ->where('a.pac_id', $pac_id)
            ->get();

        return $tratamientos;
    }


}
