<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    use HasFactory;

    protected $table = 'prestamo';

    protected $fillable = [
        'fecha_prestamo',
        'fecha_limite_asignada',
        'fecha_devolucion_efectiva',
        'descripcion',
        'detalle_entrega',
        'id_estado_prestamo',
        'id_solicitud'
    ];

    protected $casts = [
        'fecha_prestamo' => 'datetime',
        'fecha_limite_asignada' => 'datetime',
        'fecha_devolucion_efectiva' => 'datetime',
    ];

    /**
     * Get the loan status
     */
    public function estadoPrestamo()
    {
        return $this->belongsTo(EstadoPrestamo::class, 'id_estado_prestamo');
    }

    /**
     * Get the request associated with this loan
     */
    public function solicitud()
    {
        return $this->belongsTo(SolicitudPrestamo::class, 'id_solicitud');
    }
}
