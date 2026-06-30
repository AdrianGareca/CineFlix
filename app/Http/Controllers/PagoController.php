<?php

namespace App\Http\Controllers;

class PagoController extends Controller
{
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
}
