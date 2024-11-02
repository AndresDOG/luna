<?php

namespace Module\Base\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mantenimiento extends Model
{
    use HasFactory;

    protected $table = 'mantenimiento'; // Nombre de la tabla
    protected $primaryKey = 'mant_id'; // Clave primaria
    public $timestamps = true; // Manejo de timestamps (created_at y updated_at)

    protected $fillable = [
        'mant_fecha',
        'mant_equi_id',
        'mant_detalle',
    ];

    /**
     * Relación inversa con el modelo Equipo.
     */
    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'mant_equipo_id', 'equi_id');
    }
}