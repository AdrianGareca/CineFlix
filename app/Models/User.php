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

    // Se deshabilita el "remember token": la tabla 'usuarios' no tiene columna remember_token.
    public function getRememberTokenName(): string
    {
        return '';
    }

    // Sin casteo automático: el hasheo de la contraseña se hace explícitamente en AuthController.
    protected function casts(): array
    {
        return [];
    }
}
