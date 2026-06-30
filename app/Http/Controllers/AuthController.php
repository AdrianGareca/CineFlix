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

        // Fiel a la autenticación heredada (Usuario::autenticar): se autentica por la
        // columna 'usuario' y se verifica el hash bcrypt que el password_hash(PASSWORD_DEFAULT)
        // original guardó en 'contrasena'. Hash::check es compatible con bcrypt, por lo que
        // las cuentas ya sembradas siguen funcionando sin necesidad de re-encriptarlas.
        $user = User::where('usuario', $request->usuario)->first();

        if (!$user || !Hash::check($request->contrasena, $user->contrasena)) {
            return back()
                ->withErrors(['usuario' => 'Usuario o contraseña incorrectos.'])
                ->withInput(['usuario' => $request->usuario]);
        }

        Auth::loginUsingId($user->id);
        $request->session()->regenerate();

        // Comportamiento heredado: los administradores entran al panel; el resto, a la cartelera.
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
