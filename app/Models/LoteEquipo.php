<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class LoteEquipo extends Model
{
    use HasFactory;

    protected $fillable = [
        'modelo',
        'contenido_etiqueta',
        'detalle',
        'marca_id',
        'img_path',
        'cantidad_total',
        'cantidad_disponible'
    ];

    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }

    public function categorias()
    {
        return $this->belongsToMany(Categoria::class)->withTimestamps();
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function solicitudes()
    {
        return $this->belongsToMany(SolicitudPrestamo::class, 'equipo_solicitud', 'id_equipo', 'id_solicitud')
            ->withPivot('cantidad_solicitada')
            ->withTimestamps();
    }

    public function handleUploadImage($image)
    {
        $file = $image;

        $name = time() . $file->getClientOriginalName();

        $file->move(public_path() . '/img/equipos/', $name);

        return $name;
    }

    /**
     * Decrease available quantity
     */
    public function disminuirCantidad(int $cantidad): bool
    {
        if ($this->cantidad_disponible < $cantidad) {
            return false;
        }

        $this->cantidad_disponible -= $cantidad;
        return $this->save();
    }

    /**
     * Increase available quantity
     */
    public function aumentarCantidad(int $cantidad): bool
    {
        $this->cantidad_disponible += $cantidad;
        return $this->save();
    }

    /**
     * Check if there's enough quantity available
     */
    public function tieneSuficienteCantidad(int $cantidad): bool
    {
        return $this->cantidad_disponible >= $cantidad;
    }
}
