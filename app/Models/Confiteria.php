<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Confiteria extends Model
{
    protected $table = 'confiteria';

    protected $fillable = [
        'titulo',
        'descripcion',
        'precio',
        'imagen',
    ];

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = null;
}
