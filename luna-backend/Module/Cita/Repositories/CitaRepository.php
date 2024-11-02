<?php
namespace Module\Cita\Repositories;
use Illuminate\Support\Facades\DB;
use Module\Cita\Repositories\Interfaces\CitaRepositoryInterface;
use Module\Cita\Models\Cita;
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
        return $this->model->with(['paciente','tipoCita', 'medico'])->find($id);
    }

    public function pdoCitasAsig($request)
    {
        // Obtener el ID del rol del usuario y el ID del usuario
        $usuarioIdRol = auth()->user()->usu_id_rol; // Rol del usuario autenticado
        $usuarioId = auth()->user()->usu_id; // ID del usuario autenticado

        // Consulta base
        $citas = DB::table('cita as A')
            ->select(
                'A.cit_no_cita',
                'D.tip_cita_nombre',
                'A.cita_fecha',
                'B.med_nombre',
                'C.pac_nombre',
                'E.estado_nombre'
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
        elseif ($usuarioIdRol == 4) {
            $citas->where('A.cita_medico_id', function($query) use ($usuarioId) {
                $query->select('med_id')
                      ->from('medico')
                      ->where('med_usuario_id', $usuarioId);
            });

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
                'E.estado_nombre'
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

}
