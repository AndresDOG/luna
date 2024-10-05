<?php
namespace Module\Base\Repositories;

use Module\Base\Repositories\Interfaces\UsuarioRepositoryInterface;
use App\Models\User;

class UsuarioRepository implements UsuarioRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function pdoAll()
    {
        $rol_tipo = \Auth::user()->rol->rol_tipo;

        $query = $this->model
            ->join('rol', 'usu_id_rol', '=', 'rol_id')
            ->where('usu_show', 1);

        if ($rol_tipo === 1) // Acceso SuperUsuario
        {
            $query->where('usu_activo', 1);
        }

        if ($rol_tipo === 2) // Acceso Admin
        {
            $query->where(function ($q) {
                $q->where('usu_id', \Auth::user()->usu_id)
                    ->orWhereIn('rol_tipo', [3,4]);
            })->where('usu_activo', 1);
        }

        if ($rol_tipo === 3) // Acceso Operativo
        {
            $query->where(function ($q) {
                $q->where('usu_id', \Auth::user()->usu_id)
                    ->orWhere('rol_tipo', 4);
            })->where('usu_activo', 1);
        }

        $usuarios = $query->get(['*', 'rol_nombre']);

        return $usuarios;
    }

    public function pdoStore($request)
    {
        try
        {
            $usuario = $this->model->create([
                'usu_nombre' => \Str::upper($request->keys['nombre']),
                'usu_cedula' => $request->keys['cedula'],
                'usu_id_rol' => $request->keys['rol'],
                'usu_cita' => $request->keys['servicio'] ? 1 : 0,
                'usu_nickname' => $request->keys['nickname'],
                'usu_show'   => 1,
                'usu_activo' => 1,
                'password' => \Hash::make($request->keys['password']),
                'email' => $request->keys['email'] ]);

        } catch (\Exception $e) {
            return $e;
        }

        return 1;
    }

    public function pdoUpdate($request, $id)
    {
        try
        {
            $this->model->where('usu_id',$id)->update([
                'usu_nombre' => \Str::upper($request->keys['nombre']),
                'usu_cedula' => $request->keys['cedula'],
                'usu_id_rol' => $request->keys['rol'],
                'usu_cita' => $request->keys['servicio'] ? 1 : 0,
                'usu_activo' => $request->keys['activo'] ? 1 : 0,
                'usu_nickname' => $request->keys['nickname'],
                'email' => $request->keys['email'] ]);

            if ($request->keys['password'] != '********')
            {
                $this->model->where('usu_id',$id)->update([
                    'password' => \Hash::make($request->keys['password']) ]);
            }

            if ($request->keys['activo']==0)
            {
                $this->model->where('usu_id',$id)->update([
                    'password' => \Hash::make($id+1542),
                    'usu_nickname' => '-1*$'.$id,
                    'email' => $id.'@mail' ]);
            }

        } catch (\Exception $e) {
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
