<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudPrestamo extends Model
{
    use HasFactory;

    protected $table = 'solicitud_prestamos';

    protected $fillable = [
        'fecha_solicitud',
        'fecha_limite_solicitada',
        'detalle',
        'id_solicitante',
        'id_estado_solicitud'
    ];

    protected $casts = [
        'fecha_solicitud' => 'datetime',
        'fecha_limite_solicitada' => 'datetime',
    ];

    /**
     * Get the user who made the request
     */
    public function solicitante()
    {
        return $this->belongsTo(User::class, 'id_solicitante');
    }

    /**
     * Get the technician who approved the request
     */
    public function tecnicoAprobador()
    {
        return $this->belongsTo(User::class, 'id_tecnico_aprobador');
    }

    /**
     * Get the request status
     */
    public function estadoSolicitud()
    {
        return $this->belongsTo(EstadoSolicitud::class, 'id_estado_solicitud');
    }

    /**
     * Get the equipment lots associated with this request
     */
    public function equipos()
    {
        return $this->belongsToMany(Equipo::class, 'equipo_solicitud', 'id_solicitud', 'id_equipo')->withTimestamps();
    }

    /**
     * Get the loan associated with this request
     */
    public function prestamo()
    {
        return $this->hasOne(Prestamo::class, 'id_solicitud');
    }
}
