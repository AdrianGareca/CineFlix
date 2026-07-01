<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagoController extends Controller
{
    // ─── Vistas de pago (GET) ─────────────────────────────────

    public function qr()
    {
        $total = session('booking.total', 0);
        return view('pago.qr', compact('total'));
    }

    public function tarjeta()
    {
        $total = session('booking.total', 0);
        return view('pago.tarjeta', compact('total'));
    }

    public function tigo()
    {
        $total = session('booking.total', 0);
        return view('pago.tigo', compact('total'));
    }

    // ─── Confirmación de pago (POST) ──────────────────────────

    public function procesarQr(Request $request)
    {
        $this->registrarComprobante('QR');
        session()->forget('booking');
        return redirect()->route('factura.comprobante');
    }

    public function procesarTarjeta(Request $request)
    {
        $this->registrarComprobante('Tarjeta de Crédito');
        session()->forget('booking');
        return redirect()->route('factura.comprobante');
    }

    public function procesarTigo(Request $request)
    {
        $this->registrarComprobante('Tigo Money');
        session()->forget('booking');
        return redirect()->route('factura.comprobante');
    }

    // ─── Lógica interna ───────────────────────────────────────

    /**
     * Captura los datos de reserva activos y los almacena como
     * dato flash de sesión para que el comprobante pueda leerlos
     * inmediatamente después de que se limpie la sesión de compra.
     */
    private function registrarComprobante(string $metodoPago): void
    {
        $reserva = session('booking', []);

        session()->flash('comprobante', [
            'numero'           => 'CF-' . strtoupper(substr(md5(uniqid('', true)), 0, 6)),
            'fecha'            => now()->format('d/m/Y'),
            'hora'             => now()->format('H:i'),
            'pelicula_titulo'  => $reserva['pelicula_titulo']  ?? '—',
            'asientos'         => $reserva['asientos']         ?? [],
            'golosinas'        => $reserva['golosinas']        ?? [],
            'precio_entradas'  => $reserva['precio_entradas']  ?? 0,
            'precio_golosinas' => $reserva['precio_golosinas'] ?? 0,
            'total'            => $reserva['total']            ?? 0,
            'metodo_pago'      => $metodoPago,
        ]);
    }
}
