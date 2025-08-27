<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class LoteEquipo extends Model
{
    use HasFactory;

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

    public function handleUploadImage($image)
    {
        $file = $image;

        $name = time() . $file->getClientOriginalName();

        $file->move(public_path() . '/img/equipos/', $name);

        return $name;
    }

    protected $fillable = ['modelo', 'contenido_etiqueta', 'detalle', 'marca_id', 'img_path', 'cantidad_total', 'cantidad_disponible'];
}
