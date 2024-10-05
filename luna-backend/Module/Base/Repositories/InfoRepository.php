<?php
namespace Module\Base\Repositories;

use Module\Base\Repositories\Interfaces\InfoRepositoryInterface;
use Module\Base\Models\Empresa;
use Module\Base\Models\Localidad;
use Module\Base\Models\Tipo_Identidad;
use Module\Base\Models\Departamento;

class InfoRepository implements InfoRepositoryInterface
{
    public function pdoUsuarioActivo()
    {
        auth()->user()->rol;
        return response()->json([        
            'user' => auth()->user(),
            'empresa'  => $this->pdoEmpresa()
        ]); 
    }

    public function pdoEmpresa()
    {
        return Empresa::where('emp_id',1)->first();
    }

    public function pdoMenu()
    {
        $rs = \DB::select("WITH RECURSIVE menu AS (SELECT a.com_id,a.com_nombre,a.com_path,a.com_padre_id,a.com_icono
                            FROM  componente a
                            WHERE a.com_path IS NOT null and a.com_id IN (SELECT per_id_componente FROM permiso WHERE per_id_componente IN (SELECT per_id_componente FROM permiso WHERE per_id_rol = ".auth()->user()->rol->rol_id."))
                            UNION ALL
                            SELECT a.com_id,a.com_nombre,a.com_path,a.com_padre_id,a.com_icono           
                            FROM componente a JOIN menu b ON a.com_id = b.com_padre_id
                    )
                    SELECT DISTINCT com_id,com_nombre,com_path,com_icono,com_padre_id FROM menu ORDER BY com_id");

        return $rs;
    }

    public function pdoLocalidad($id)
    {
        return Localidad::where('loc_id_departamento',$id)->get();
    }

    public function pdoTipoIdentidad()
    {
        return Tipo_Identidad::all();
    }

    public function pdoDepartamento()
    {
        return Departamento::all();
    }
	
}
