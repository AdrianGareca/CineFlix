<?php

namespace App\Http\Controllers;

class FacturaController extends Controller
{
    public function index()
    {
        $booking = session('booking', []);

        if (empty($booking['pelicula_id']) || empty($booking['asientos'])) {
            return redirect()->route('cartelera')
                ->with('error', 'No tienes una reserva activa.');
        }

        $precioEntradas  = $booking['precio_entradas']  ?? 0;
        $precioGolosinas = $booking['precio_golosinas'] ?? 0;
        $total           = $precioEntradas + $precioGolosinas;

        // Store total for payment pages
        session(['booking.total' => $total]);

        return view('factura.index', [
            'pelicula_titulo'  => $booking['pelicula_titulo']  ?? '—',
            'asientos'         => $booking['asientos'],
            'golosinas'        => $booking['golosinas']        ?? [],
            'precio_entradas'  => $precioEntradas,
            'precio_golosinas' => $precioGolosinas,
            'total'            => $total,
        ]);
    }
}
