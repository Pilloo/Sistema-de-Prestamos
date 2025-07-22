<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Seccion extends Model
{
    use HasFactory;

    public function caracteristica(): HasOne {
        return $this->hasOne(Caracteristica::class);
    }
}
