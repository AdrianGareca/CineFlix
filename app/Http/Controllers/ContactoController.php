<?php

namespace App\Http\Controllers;

use App\Models\Contacto;
use Illuminate\Http\Request;

class ContactoController extends Controller
{
    public function index()
    {
        return view('contacto.index');
    }

    public function enviar(Request $request)
    {
        $request->validate([
            'nombre'  => 'required|string|max:100',
            'correo'  => 'required|email|max:100',
            'mensaje' => 'required|string|min:10|max:1000',
        ]);

        Contacto::create($request->only('nombre', 'correo', 'mensaje'));

        return back()->with('exito', '¡Tu mensaje fue enviado! Te responderemos pronto.');
    }
}
