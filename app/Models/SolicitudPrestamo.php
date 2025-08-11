<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SolicitudPrestamo extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha_prestamo',
        'fecha_limite_asignada',
        'fecha_devolucion_efectiva',
        'descripcion',
        'detalle_entrega',
    ];

    public function prestamo(){
        return $this->hasOne(Prestamo::class);
    }

    public function estadoSolicitud(){
        return $this->belongsTo(EstadoSolicitud::class);
    }
}
