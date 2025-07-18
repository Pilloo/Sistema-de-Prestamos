<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caracteristica extends Model
{
    use HasFactory;

    public function categoria(){
        return $this->hasOne(Seccion::class);
    }

    public function marca(){
        return $this->hasOne(Seccion::class);
    }

    protected $fillable = ['nombre','estado'];
}
