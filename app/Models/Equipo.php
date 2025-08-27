<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Equipo extends Model
{
    use HasFactory;

    public function lote()
    {
        return $this->belongsTo(LoteEquipo::class);
    }

    public function estado_equipo()
    {
        return $this->belongsTo(EstadoEquipo::class);
    }

    protected $fillable = ['numero_serie', 'lote_equipo_id', 'estado_equipo_id'];
}
