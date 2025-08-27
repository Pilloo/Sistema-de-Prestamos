<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SolicitudPrestamo extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha_solicitud',
        'fecha_limite_solicitada',
        'detalle',
        'id_solicitante',
        'id_tecnico_aprobador',
        'id_estado_solicitud'
    ];

    protected $casts = [
        'fecha_solicitud' => 'datetime',
        'fecha_limite_solicitada' => 'datetime',
    ];

    public function solicitante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_solicitante');
    }

    public function tecnicoAprobador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_tecnico_aprobador');
    }

    public function estadoSolicitud(): BelongsTo
    {
        return $this->belongsTo(EstadoSolicitud::class, 'id_estado_solicitud');
    }

    public function prestamo(): HasOne
    {
        return $this->hasOne(Prestamo::class, 'id_solicitud');
    }

    public function equipos()
    {
        return $this->belongsToMany(Equipo::class, 'equipo_solicitud', 'id_solicitud', 'id_equipo');
    }
}
