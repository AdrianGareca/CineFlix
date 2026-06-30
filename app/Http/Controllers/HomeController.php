<?php

namespace App\Http\Controllers;

use App\Models\Confiteria;
use App\Models\Pelicula;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function cartelera()
    {
        $carrusel  = Pelicula::orderBy('creado_en', 'desc')->take(4)->get();
        $peliculas = Pelicula::orderBy('creado_en', 'desc')->get();

        return view('home.cartelera', compact('carrusel', 'peliculas'));
    }

    public function golosinas()
    {
        if (empty(session('booking.pelicula_id'))) {
            return redirect()->route('cartelera');
        }

        $productos = Confiteria::orderBy('creado_en', 'asc')->get();
        return view('home.golosinas', compact('productos'));
    }

    public function guardarGolosinas(Request $request)
    {
        $cantidades = $request->input('cantidades', []);
        $productos  = Confiteria::orderBy('creado_en', 'asc')->get();

        $golosinas       = [];
        $precioGolosinas = 0;

        foreach ($productos as $prod) {
            $qty = (int) ($cantidades[$prod->id] ?? 0);
            if ($qty > 0) {
                $subtotal         = $qty * (float) $prod->precio;
                $precioGolosinas += $subtotal;
                $golosinas[]      = [
                    'titulo'      => $prod->titulo,
                    'cantidad'    => $qty,
                    'precio_unit' => $prod->precio,
                    'subtotal'    => $subtotal,
                ];
            }
        }

        $precioEntradas = session('booking.precio_entradas', 0);

        session([
            'booking.golosinas'        => $golosinas,
            'booking.precio_golosinas' => $precioGolosinas,
            'booking.total'            => $precioEntradas + $precioGolosinas,
        ]);

        return redirect()->route('factura.index');
    }
}
