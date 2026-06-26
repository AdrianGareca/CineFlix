<?php
/**
 * Controller — Controlador base del que heredan todos los demás.
 * Provee utilidades para cargar modelos y renderizar vistas.
 */
abstract class Controller
{
    /**
     * Carga e instancia un modelo.
     *   $this->modelo('Pelicula')  -> new Pelicula()
     */
    protected function modelo(string $nombre)
    {
        $ruta = APP_PATH . '/models/' . $nombre . '.php';
        require_once $ruta;
        return new $nombre();
    }

    /**
     * Renderiza una vista pasándole datos.
     *   $this->vista('cartelera/index', ['peliculas' => $lista])
     *
     * Las claves del arreglo $datos se convierten en variables dentro de la vista.
     */
    protected function vista(string $vista, array $datos = []): void
    {
        extract($datos);   // $datos['titulo'] -> $titulo

        $archivo = APP_PATH . '/views/' . $vista . '.php';

        if (!file_exists($archivo)) {
            die('Vista no encontrada: ' . e($vista));
        }

        require $archivo;
    }

    /** Atajo para incluir un fragmento de layout (header/footer/nav) */
    protected function parcial(string $nombre, array $datos = []): void
    {
        extract($datos);
        require APP_PATH . '/views/layouts/' . $nombre . '.php';
    }
}
