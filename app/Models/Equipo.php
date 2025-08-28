<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Equipo extends Model
{
    use HasFactory;

    protected $fillable = ['numero_serie', 'lote_equipo_id', 'estado_equipo_id'];

    public function lote()
    {
        return $this->belongsTo(LoteEquipo::class, 'lote_equipo_id');
    }

    public function estado_equipo()
    {
        return $this->belongsTo(EstadoEquipo::class);
    }

    public function prestamos()
    {
        return $this->belongsToMany(SolicitudPrestamo::class, 'equipo_solicitud', 'id_equipo', 'id_solicitud');
    }

}
