<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contacto extends Model
{
    protected $table = 'contactos';

    protected $fillable = ['nombre', 'correo', 'mensaje'];

    const CREATED_AT = 'enviado_en';
    const UPDATED_AT = null;
}
