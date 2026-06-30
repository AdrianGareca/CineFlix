<?php

namespace App\Http\Controllers;

use App\Models\Pelicula;

class PeliculaController extends Controller
{
    public function show(int $id)
    {
        $pelicula = Pelicula::findOrFail($id);
        return view('peliculas.detalle', compact('pelicula'));
    }
}
