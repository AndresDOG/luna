<?php
namespace Module\Cita\Repositories;
use Illuminate\Support\Facades\DB;
use Module\Cita\Repositories\Interfaces\CitaRepositoryInterface;
use Module\Cita\Models\Cita;
use Module\Cita\Models\Nota;
use Module\Base\Models\Numeracion;


class CitaRepository implements CitaRepositoryInterface
{
    protected $model;

    public function __construct(Cita $model)
    {
        $this->model = $model;
    }

    public function pdoStore($request)
    {
        \DB::beginTransaction();
        try {
            $rs = Numeracion::find(1);
            $no_cita = $rs->doc_proximo;

            $cita = new Cita();
            $cita->cit_no_cita = $no_cita;
            $cita->cita_fecha = $request->keys['fecha'];
            $cita->cita_id_tipo_cita = $request->keys['idtipo']['tip_cita_id'];
            $cita->cita_paciente_id = $request->keys['idPaciente'];
            $cita->estado_id = 1;
            $cita->cita_medico_id = $request->keys['idMedico']['med_id'];
            $cita->cita_observacion = \Str::upper($request->keys['observacion']);
            $cita->cita_detalle = \Str::upper($request->keys['detalle']);
            $cita->cita_sintoma = \Str::upper($request->keys['sintoma']);
            $cita->save();

            Numeracion::find(1)->increment('doc_proximo');

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return $e;
        }

        return $no_cita;
    }

    public function pdoCita($id)
    {
        return $this->model->with(['paciente','tipoCita', 'medico','estado_cita'])->find($id);
    }

    public function pdoCitasAsig($request)
    {
        // Obtener el ID del rol del usuario y el ID del usuario
        $usuarioIdRol = auth()->user()->usu_id_rol; // Rol del usuario autenticado
        $usuarioId = auth()->user()->usu_id; // ID del usuario autenticado

        // Consulta base
        $citas = DB::table('cita as A')
            ->select(
                'A.cita_id',
                'A.cit_no_cita',
                'D.tip_cita_nombre',
                'A.cita_fecha',
                'B.med_nombre',
                'C.pac_nombre',
                'E.estado_nombre',
                'E.estado_id',
            )
            ->join('medico as B', 'B.med_id', '=', 'A.cita_medico_id')
            ->join('paciente as C', 'C.pac_id', '=', 'A.cita_paciente_id')
            ->join('tipo_cita as D', 'D.tip_cita_id', '=', 'A.cita_id_tipo_cita')
            ->join('estado_cita as E', 'E.estado_id', '=', 'A.estado_id')
            ->orderBy('A.cita_fecha');

        // Caso 1: Si el rol del usuario es 1, se muestran todas las citas
        if ($usuarioIdRol == 1) {
            $resultados = $citas->get();
        }
        // Caso 2: Si el rol del usuario es 4, se filtran las citas del médico asociado al usuario
        elseif ($usuarioIdRol == 4) 
        {
            // Filtrar por citas del médico asociado y estado_id 1 o 3
            $citas->where('A.cita_medico_id', function($query) use ($usuarioId) {
                $query->select('med_id')
                      ->from('medico')
                      ->where('med_usuario_id', $usuarioId);
            });
            $citas->whereIn('E.estado_id', [1, 3]); // Filtrar estado_id 1 o 3
            $resultados = $citas->get();
        }
        // Caso 3: Para todos los roles diferentes de 1, filtrar solo citas con estado_id 1 o 3
        else {
            $citas->whereIn('E.estado_id', [1, 3]);
            $resultados = $citas->get();
        }

        return response()->json($resultados);
    }


    public function pdoCitaPaci($id)
    {
         $citas = DB::table('cita as A')
            ->select(
                'A.cit_no_cita',
                'D.tip_cita_nombre',
                'A.cita_fecha',
                'B.med_nombre',
                'C.pac_nombre',
                'E.estado_nombre',
                'E.estado_id',
            )
            ->join('medico as B', 'B.med_id', '=', 'A.cita_medico_id')
            ->join('paciente as C', 'C.pac_id', '=', 'A.cita_paciente_id')
            ->join('tipo_cita as D', 'D.tip_cita_id', '=', 'A.cita_id_tipo_cita')
            ->join('estado_cita as E', 'E.estado_id', '=', 'A.estado_id')
            ->where('A.cita_paciente_id', '=', $id) // Filtro por paciente
            ->orderBy('A.cita_fecha')
            ->get();

        return response()->json($citas);
    }

    public function pdoCambiarEstado($request)
    {
        \DB::beginTransaction();
        try {
            $cita = $this->model->find($request->id_cita);
            $cita->estado_id = $request->id_estado;
            $cita->save();

            $nota = new Nota();
            $nota->not_id_cita = $request->id_cita;
            $nota->not_id_usuario  = \Auth::user()->usu_id;
            $nota->not_detalle = 'Cita pasa al estado: ' . $request->estado;
            $nota->save();

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return 0;
        }

        return 1;
    }

    public function pdoActualizarCita($request, $id)
    {
        \DB::beginTransaction();
        try 
        {
            // Buscar la cita por ID
            $cita = $this->model->find($id);
            
            if (!$cita) {
                throw new \Exception('Cita no encontrada');
            }
            
            // Actualizar los campos específicos directamente, aplicando strtoupper()
            $cita->cita_id_tipo_cita = $request->tipo;
            $cita->cita_observacion = strtoupper($request->observacion);
            $cita->cita_sintoma = strtoupper($request->sintoma);
            $cita->cita_detalle = strtoupper($request->detalle);
            
            $cita->save();
            
            \DB::commit();
            return 1;
        } 
        catch (\Exception $e) 
        {
            \DB::rollback();
            return 0;
        }
    }

    public function pdoConsultaSimple($request)
    {
        // Obtener los parámetros directamente desde el request
        $parametro = $request->parametro;
        $criterio = $request->criterio;

        // Iniciar la consulta en la tabla Cita
        $rs = DB::table('cita')
            ->leftJoin('paciente as a', 'cita.cita_paciente_id', '=', 'a.pac_id')
            ->leftJoin('medico as m', 'cita.cita_medico_id', '=', 'm.med_id') // Join con la tabla medico
            ->leftJoin('tipo_cita as t', 'cita.cita_id_tipo_cita', '=', 't.tip_cita_id') // Join con la tabla tipo_cita
            ->leftJoin('estado_cita as e', 'cita.estado_id', '=', 'e.estado_id') // Join con la tabla estado_cita
            ->select('cita.*', 'a.pac_nombre', 'a.pac_cedula', 'a.pac_telefono', 
                     'm.med_nombre', 't.tip_cita_nombre', 'e.estado_nombre'); // Seleccionar las columnas deseadas

        // Aplicar el filtro según el criterio
        if ($criterio == 1) {
            $rs->where('a.pac_cedula', $parametro);
        } elseif ($criterio == 2) {
            $rs->where('a.pac_nombre', 'like', '%' . strtoupper($parametro) . '%');
        } elseif ($criterio == 3) {
            $rs->where('a.pac_telefono', 'like', '%' . $parametro . '%');
        } elseif ($criterio == 4) {
            $rs->where('cita.cit_no_cita', $parametro); // Criterio 4 para buscar por no_cita
        }

        // Ejecutar la consulta y obtener los resultados
        $result = $rs->get();

        // Retornar 0 si no se encuentran resultados
        if ($result->isEmpty()) {
            return 0;
        }

        // Retornar los resultados encontrados
        return $result;
    }



}
