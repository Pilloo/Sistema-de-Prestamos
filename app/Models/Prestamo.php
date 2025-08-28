<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    use HasFactory;

    protected $table = 'prestamos';

    protected $fillable = [
        'id_solicitante',
        'id_aprobador',
        'id_estado_prestamo',
        'id_solicitud'
    ];

    protected $casts = [
        
    ];

    public function estadoPrestamo()
    {
        return $this->belongsTo(EstadoPrestamo::class, 'id_estado_prestamo');
    }

    public function solicitud()
    {
        return $this->belongsTo(SolicitudPrestamo::class, 'id_solicitud');
    }

    public function solicitante()
    {
        return $this->belongsTo(User::class, 'id_solicitante');
    }

    public function aprobador()
    {
        return $this->belongsTo(User::class, 'id_aprobador');
    }
}
