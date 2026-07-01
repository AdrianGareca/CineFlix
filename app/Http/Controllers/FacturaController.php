<?php

namespace App\Http\Controllers;

class FacturaController extends Controller
{
    // ─── Resumen de compra previo al pago ─────────────────────

    public function index()
    {
        $reserva = session('booking', []);

        if (empty($reserva['pelicula_id']) || empty($reserva['asientos'])) {
            return redirect()->route('cartelera')
                ->with('error', 'No tienes una reserva activa.');
        }

        $precioEntradas  = $reserva['precio_entradas']  ?? 0;
        $precioGolosinas = $reserva['precio_golosinas'] ?? 0;
        $total           = $precioEntradas + $precioGolosinas;

        // Guardar el total actualizado para las pantallas de pago
        session(['booking.total' => $total]);

        return view('factura.index', [
            'pelicula_titulo'  => $reserva['pelicula_titulo']  ?? '—',
            'asientos'         => $reserva['asientos'],
            'golosinas'        => $reserva['golosinas']        ?? [],
            'precio_entradas'  => $precioEntradas,
            'precio_golosinas' => $precioGolosinas,
            'total'            => $total,
        ]);
    }

    // ─── Comprobante de pago (post-transacción) ───────────────

    public function comprobante()
    {
        $comprobante = session('comprobante');

        if (empty($comprobante)) {
            return redirect()->route('cartelera')
                ->with('error', 'No hay comprobante disponible. Por favor inicia una nueva compra.');
        }

        return view('factura.comprobante', compact('comprobante'));
    }
}
