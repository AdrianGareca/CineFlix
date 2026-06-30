<?php

/*
|--------------------------------------------------------------------------
| Líneas de idioma para la validación (español)
|--------------------------------------------------------------------------
| Mensajes en español para las reglas de validación usadas por CineFlix.
| El nombre legible de cada campo se define en 'attributes'.
*/

return [

    'required'  => 'El campo :attribute es obligatorio.',
    'string'    => 'El campo :attribute debe ser texto.',
    'integer'   => 'El campo :attribute debe ser un número entero.',
    'numeric'   => 'El campo :attribute debe ser un número.',
    'email'     => 'El campo :attribute debe ser un correo electrónico válido.',
    'confirmed' => 'La confirmación de :attribute no coincide.',
    'image'     => 'El campo :attribute debe ser una imagen.',
    'mimes'     => 'El campo :attribute debe ser un archivo de tipo: :values.',
    'unique'    => 'El valor de :attribute ya está registrado.',
    'array'     => 'El campo :attribute debe ser una lista.',

    'min' => [
        'numeric' => 'El campo :attribute debe ser como mínimo :min.',
        'string'  => 'El campo :attribute debe tener al menos :min caracteres.',
        'file'    => 'El campo :attribute debe pesar al menos :min kilobytes.',
        'array'   => 'El campo :attribute debe tener al menos :min elementos.',
    ],

    'max' => [
        'numeric' => 'El campo :attribute no debe ser mayor que :max.',
        'string'  => 'El campo :attribute no debe tener más de :max caracteres.',
        'file'    => 'El campo :attribute no debe pesar más de :max kilobytes.',
        'array'   => 'El campo :attribute no debe tener más de :max elementos.',
    ],

    /*
    | Nombres legibles de los campos, para que los mensajes suenen naturales.
    */
    'attributes' => [
        'titulo'                  => 'título',
        'descripcion'             => 'descripción',
        'duracion'                => 'duración',
        'genero'                  => 'género',
        'calificacion'            => 'calificación',
        'imagen'                  => 'imagen',
        'precio'                  => 'precio',
        'nombre'                  => 'nombre',
        'correo'                  => 'correo electrónico',
        'usuario'                 => 'usuario',
        'contrasena'              => 'contraseña',
        'contrasena_confirmation' => 'confirmación de contraseña',
        'mensaje'                 => 'mensaje',
        'asientos'                => 'butacas',
    ],

    /*
    | Mensajes personalizados por campo + regla (opcional).
    */
    'custom' => [
        'asientos' => [
            'required' => 'Debes seleccionar al menos una butaca.',
            'min'      => 'Debes seleccionar al menos una butaca.',
            'max'      => 'No puedes seleccionar más de 8 butacas.',
        ],
        'contrasena' => [
            'confirmed' => 'Las contraseñas no coinciden.',
        ],
    ],

];
