<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    public function seccion()
    {
        return $this->belongsTo(Seccione::class);
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'primer_apellido',
        'segundo_apellido',
        'seccion_id',
        'departamento_id',
        'img_path'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function handleUploadImage($image)
    {
        $file = $image;

        $name = time() . $file->getClientOriginalName();

        $file->move(public_path() . '/img/users/', $name);

        return $name;
    }

}
