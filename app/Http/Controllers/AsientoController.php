<?php

namespace App\Http\Controllers;

use App\Models\Pelicula;
use Illuminate\Http\Request;

class AsientoController extends Controller
{
    // Butacas ya ocupadas (demo). En un sistema real se consultaría una tabla de reservas.
    private const OCUPADOS = ['A3','A7','B1','B5','C2','C6','C9','D4','D8','E3','E7'];

    // Butacas especiales (VIP) — precio más alto
    private const ESPECIALES = ['E4','E5','E6','E7'];

    public function index(Request $request)
    {
        $peliculaId = $request->query('pelicula', session('booking.pelicula_id'));
        $pelicula   = $peliculaId ? Pelicula::find($peliculaId) : null;

        if (!$pelicula) {
            return redirect()->route('cartelera')
                ->with('error', 'Selecciona una película primero.');
        }

        session([
            'booking.pelicula_id'    => $pelicula->id,
            'booking.pelicula_titulo' => $pelicula->titulo,
        ]);

        return view('asientos.index', [
            'pelicula'   => $pelicula,
            'ocupados'   => self::OCUPADOS,
            'especiales' => self::ESPECIALES,
        ]);
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'asientos' => 'required|array|min:1|max:8',
        ]);

        $asientos = $request->input('asientos');

        $precio = 0;
        foreach ($asientos as $asiento) {
            $precio += in_array($asiento, self::ESPECIALES) ? 55 : 35;
        }

        session([
            'booking.asientos'        => $asientos,
            'booking.precio_entradas' => $precio,
        ]);

        return redirect()->route('golosinas');
    }
}
