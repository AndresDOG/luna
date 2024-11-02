<?php

namespace Module\Base\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Equipo extends Model
{
    use HasFactory;

    protected $table = 'equipo'; // Nombre de la tabla
    protected $primaryKey = 'equi_id'; // Clave primaria
    public $timestamps = true; // Manejo de timestamps (created_at y updated_at)

    protected $fillable = [
        'equi_nombre',
        'equi_tipo',
        'equi_estado'
    ];

    /**
     * Relación uno a muchos con el modelo Mantenimiento.
     */
    public function mantenimientos()
    {
        return $this->hasMany(Mantenimiento::class, 'mant_equipo_id', 'equi_id');
    }
}