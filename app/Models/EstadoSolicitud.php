<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstadoSolicitud extends Model
{
    use HasFactory;

    public function caracteristica(){
        return $this->belongsTo(Caracteristica::class);
    }

    public function solicitudPrestamo(){
        return $this->hasMany(SolicitudPrestamo::class);
    }

    protected $fillable = ['idCaracteristica'];
}
