<?php

namespace App\Http\Controllers;

use App\Models\Confiteria;
use App\Models\Pelicula;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Guard: only authenticated users with rol === 'admin' may proceed.
     * Returns a redirect when access is denied, or null when allowed.
     */
    private function guard(): ?RedirectResponse
    {
        if (!Auth::check() || Auth::user()->rol !== 'admin') {
            return redirect()->route('cartelera')
                ->with('error', 'Acceso restringido: necesitas una cuenta de administrador.');
        }
        return null;
    }

    /**
     * Move an uploaded image into public/img/ and return its stored path
     * (e.g. "img/1700000000_poster.jpg") to keep it compatible with asset().
     */
    private function guardarImagen(Request $request, string $campo = 'imagen'): ?string
    {
        if (!$request->hasFile($campo)) {
            return null;
        }

        $file   = $request->file($campo);
        $limpio = preg_replace('/[^A-Za-z0-9._-]/', '_', $file->getClientOriginalName());
        $nombre = time() . '_' . $limpio;

        $file->move(public_path('img'), $nombre);

        return 'img/' . $nombre;
    }

    // ─────────────────────────────────────────────────────────────
    //  Dashboard
    // ─────────────────────────────────────────────────────────────
    public function index()
    {
        if ($r = $this->guard()) return $r;

        return view('admin.dashboard', [
            'totalPeliculas'   => Pelicula::count(),
            'totalConfiteria'  => Confiteria::count(),
            'ultimasPeliculas' => Pelicula::orderBy('creado_en', 'desc')->take(5)->get(),
            'ultimaConfiteria' => Confiteria::orderBy('creado_en', 'desc')->take(5)->get(),
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  Películas — CRUD
    // ─────────────────────────────────────────────────────────────
    public function peliculasIndex()
    {
        if ($r = $this->guard()) return $r;

        $peliculas = Pelicula::orderBy('creado_en', 'desc')->get();
        return view('admin.peliculas.index', compact('peliculas'));
    }

    public function peliculasCreate()
    {
        if ($r = $this->guard()) return $r;

        return view('admin.peliculas.create');
    }

    public function peliculasStore(Request $request)
    {
        if ($r = $this->guard()) return $r;

        $datos = $request->validate([
            'titulo'       => 'required|string|max:150',
            'descripcion'  => 'nullable|string',
            'duracion'     => 'nullable|string|max:50',
            'genero'       => 'nullable|string|max:80',
            'calificacion' => 'nullable|integer|min:0|max:5',
            'imagen'       => 'required|image|mimes:jpeg,jpg,png,webp,gif|max:4096',
        ]);

        $datos['imagen'] = $this->guardarImagen($request);

        Pelicula::create($datos);

        return redirect()->route('admin.peliculas.index')
            ->with('exito', 'Película «' . $datos['titulo'] . '» creada correctamente.');
    }

    public function peliculasEdit(int $id)
    {
        if ($r = $this->guard()) return $r;

        $pelicula = Pelicula::findOrFail($id);
        return view('admin.peliculas.edit', compact('pelicula'));
    }

    public function peliculasUpdate(Request $request, int $id)
    {
        if ($r = $this->guard()) return $r;

        $pelicula = Pelicula::findOrFail($id);

        $datos = $request->validate([
            'titulo'       => 'required|string|max:150',
            'descripcion'  => 'nullable|string',
            'duracion'     => 'nullable|string|max:50',
            'genero'       => 'nullable|string|max:80',
            'calificacion' => 'nullable|integer|min:0|max:5',
            'imagen'       => 'nullable|image|mimes:jpeg,jpg,png,webp,gif|max:4096',
        ]);

        // Keep the existing image unless a new file was uploaded.
        if ($nueva = $this->guardarImagen($request)) {
            $datos['imagen'] = $nueva;
        } else {
            unset($datos['imagen']);
        }

        $pelicula->update($datos);

        return redirect()->route('admin.peliculas.index')
            ->with('exito', 'Película «' . $pelicula->titulo . '» actualizada.');
    }

    public function peliculasDestroy(int $id)
    {
        if ($r = $this->guard()) return $r;

        $pelicula = Pelicula::findOrFail($id);
        $titulo   = $pelicula->titulo;
        $pelicula->delete();

        return redirect()->route('admin.peliculas.index')
            ->with('exito', 'Película «' . $titulo . '» eliminada.');
    }

    // ─────────────────────────────────────────────────────────────
    //  Confitería — CRUD
    // ─────────────────────────────────────────────────────────────
    public function confiteriaIndex()
    {
        if ($r = $this->guard()) return $r;

        $productos = Confiteria::orderBy('creado_en', 'desc')->get();
        return view('admin.confiteria.index', compact('productos'));
    }

    public function confiteriaCreate()
    {
        if ($r = $this->guard()) return $r;

        return view('admin.confiteria.create');
    }

    public function confiteriaStore(Request $request)
    {
        if ($r = $this->guard()) return $r;

        $datos = $request->validate([
            'titulo'      => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'precio'      => 'required|numeric|min:0',
            'imagen'      => 'required|image|mimes:jpeg,jpg,png,webp,gif|max:4096',
        ]);

        $datos['imagen'] = $this->guardarImagen($request);

        Confiteria::create($datos);

        return redirect()->route('admin.confiteria.index')
            ->with('exito', 'Producto «' . $datos['titulo'] . '» creado correctamente.');
    }

    public function confiteriaEdit(int $id)
    {
        if ($r = $this->guard()) return $r;

        $producto = Confiteria::findOrFail($id);
        return view('admin.confiteria.edit', compact('producto'));
    }

    public function confiteriaUpdate(Request $request, int $id)
    {
        if ($r = $this->guard()) return $r;

        $producto = Confiteria::findOrFail($id);

        $datos = $request->validate([
            'titulo'      => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'precio'      => 'required|numeric|min:0',
            'imagen'      => 'nullable|image|mimes:jpeg,jpg,png,webp,gif|max:4096',
        ]);

        if ($nueva = $this->guardarImagen($request)) {
            $datos['imagen'] = $nueva;
        } else {
            unset($datos['imagen']);
        }

        $producto->update($datos);

        return redirect()->route('admin.confiteria.index')
            ->with('exito', 'Producto «' . $producto->titulo . '» actualizado.');
    }

    public function confiteriaDestroy(int $id)
    {
        if ($r = $this->guard()) return $r;

        $producto = Confiteria::findOrFail($id);
        $titulo   = $producto->titulo;
        $producto->delete();

        return redirect()->route('admin.confiteria.index')
            ->with('exito', 'Producto «' . $titulo . '» eliminado.');
    }
}
