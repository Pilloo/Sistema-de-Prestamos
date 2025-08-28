<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstadoPrestamo extends Model
{
    protected $table = 'estado_prestamos';
    use HasFactory;

    public function Prestamo(){
        return $this->hasMany(Prestamo::class);
    }

    protected $fillable = ['nombre'];
}
