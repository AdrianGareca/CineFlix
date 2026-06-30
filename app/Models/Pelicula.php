<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelicula extends Model
{
    protected $table = 'peliculas';

    protected $fillable = [
        'titulo',
        'descripcion',
        'duracion',
        'genero',
        'calificacion',
        'imagen',
    ];

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = null;
}
