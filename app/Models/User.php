<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table      = 'usuarios';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre', 'correo', 'usuario', 'contrasena', 'rol',
    ];

    protected $hidden = ['contrasena'];

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = null;

    // Disable remember-token: usuarios table has no remember_token column
    public function getRememberTokenName(): string
    {
        return '';
    }

    // No automatic casting — hashing is done explicitly in AuthController
    protected function casts(): array
    {
        return [];
    }
}
