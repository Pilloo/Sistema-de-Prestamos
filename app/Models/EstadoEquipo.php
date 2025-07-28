<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstadoEquipo extends Model
{
    use HasFactory;

    public function equipos(){
        return $this->hasMany(Equipo::class);
    }

    protected $fillable = ['nombre'];
}
