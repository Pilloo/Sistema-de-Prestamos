<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    public function caracteristica(){
        return $this->belongsTo(Caracteristica::class);
    }

    public function equipos(){
        return $this->hasMany(LoteEquipo::class);
    }

    protected $fillable = ['caracteristica_id'];
}
