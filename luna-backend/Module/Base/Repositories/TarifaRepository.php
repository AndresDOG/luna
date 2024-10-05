<?php
namespace Module\Base\Repositories;

use Module\Base\Repositories\Interfaces\TarifaRepositoryInterface;
use Module\Base\Models\Tarifa;

class TarifaRepository implements TarifaRepositoryInterface
{
    protected $model;

    public function __construct(Tarifa $model)
    {
        $this->model = $model;
    }

    public function pdoAll()
    {
        return $this->model::all();
      //  return $this->model->select('pag_tar_id',\DB::Raw('initcap(pag_tar_nombre) as pag_tar_nombre'),'pag_tar_valor')->get();

    }
    public function pdoStore($request)
    {         
        try 
        {
            /*$this->model->create([
                'mod_id_marca' => $request->marca,
                'mod_id_producto' => $request->producto,
                'mod_nombre' => \Str::upper($request->modelo) ]);           */
            $tarifa = new Tarifa();
            $tarifa->pag_tar_valor = $request->tarifaValor;
            $tarifa->pag_tar_nombre  = \Str::upper($request->tarifaNombre);
            $tarifa->save();


        } catch (\Exception $e) {            
            return $e;
        }   
        
        return $tarifa->pag_tar_id;
    }

    public function pdoUpdate($request, $id)
    {        
        \DB::beginTransaction();
        try 
        {            
            $tarifa = $this->model->find($id);
            
            
            $tarifa->pag_tar_nombre = \Str::upper($request->keys['nombre']);
            $tarifa->pag_tar_valor = $request->keys['valor'];
            $tarifa->save();                                                

            \DB::commit();

        } catch (\Exception $e) {
            \DB::rollback();
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
