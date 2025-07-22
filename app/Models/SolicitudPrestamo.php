<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SolicitudPrestamo extends Model
{
    use HasFactory;

    public function prestamo(){
        return $this->hasOne(Prestamo::class);
    }

    public function estadoSolicitud(){
        return $this->belongsTo(EstadoSolicitud::class);
    }

    protected $fillable = [];

}
