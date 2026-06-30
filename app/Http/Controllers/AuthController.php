<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('cartelera');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'usuario'    => 'required|string',
            'contrasena' => 'required|string',
        ]);

        // Faithful to the legacy auth (Usuario::autenticar): authenticate by the
        // 'usuario' column, then verify the bcrypt hash that the native PHP
        // password_hash(PASSWORD_DEFAULT) stored in 'contrasena' — Hash::check is
        // bcrypt-compatible, so the existing seeded accounts keep working.
        $user = User::where('usuario', $request->usuario)->first();

        if (!$user || !Hash::check($request->contrasena, $user->contrasena)) {
            return back()
                ->withErrors(['usuario' => 'Usuario o contraseña incorrectos.'])
                ->withInput(['usuario' => $request->usuario]);
        }

        Auth::loginUsingId($user->id);
        $request->session()->regenerate();

        // Legacy behaviour: admins land on the panel, everyone else on the billboard.
        if ($user->rol === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('cartelera'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('cartelera');
    }

    public function showRegistro()
    {
        if (Auth::check()) {
            return redirect()->route('cartelera');
        }
        return view('auth.registro');
    }

    public function registro(Request $request)
    {
        $request->validate([
            'nombre'     => 'required|string|max:100',
            'correo'     => 'required|email|max:100|unique:usuarios,correo',
            'usuario'    => 'required|string|max:50|unique:usuarios,usuario',
            'contrasena' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'nombre'     => $request->nombre,
            'correo'     => $request->correo,
            'usuario'    => $request->usuario,
            'contrasena' => Hash::make($request->contrasena),
            'rol'        => 'usuario',
        ]);

        Auth::loginUsingId($user->id);
        return redirect()->route('cartelera');
    }
}
