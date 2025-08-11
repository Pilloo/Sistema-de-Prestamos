<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;

    public function caracteristica(){
        return $this->belongsTo(Caracteristica::class);
    }

    public function usuarios(){
        return $this->hasMany(User::class);
    }

    protected $fillable = ['caracteristica_id'];
}
