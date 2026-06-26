<?php
/**
 * CarteleraController — Listado de películas y detalle.
 */
class CarteleraController extends Controller
{
    /** Página principal de cartelera (carrusel + grid). */
    public function index(): void
    {
        $modelo = $this->modelo('Pelicula');

        $this->vista('cartelera/index', [
            'carrusel'  => $modelo->recientes(4),
            'peliculas' => $modelo->todas(),
        ]);
    }

    /** Detalle de una película (?ruta=vermas&id=N). */
    public function detalle(): void
    {
        $id     = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $modelo = $this->modelo('Pelicula');
        $pelicula = $modelo->porId($id);

        if (!$pelicula) {
            redirigir('cartelera');
        }

        $this->vista('cartelera/detalle', ['p' => $pelicula]);
    }
}
