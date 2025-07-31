<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categoria extends Model
{
    use HasFactory;

    public function caracteristica(){
        return $this->belongsTo(Caracteristica::class);
    }

    public function equipos(){
        return $this->belongsToMany(Equipo::class)->withTimestamps();
    }
    
    protected $fillable = ['caracteristica_id'];
}
