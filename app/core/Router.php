<?php
/**
 * Router — Despacha la petición al controlador y acción correctos
 * según el parámetro ?ruta= del front controller.
 *
 * Tabla de rutas:  'ruta' => ['Controlador', 'metodo']
 */
class Router
{
    private array $rutas = [
        // Autenticación
        'login'     => ['AuthController',      'login'],
        'registro'  => ['AuthController',      'registro'],
        'logout'    => ['AuthController',      'logout'],

        // Cartelera / películas
        'cartelera' => ['CarteleraController', 'index'],
        'vermas'    => ['CarteleraController', 'detalle'],

        // Confitería
        'golosinas' => ['GolosinaController',  'index'],

        // Panel de administración
        'admin'     => ['AdminController',     'index'],

        // Proceso de compra (estáticas integradas)
        'asientos'  => ['CheckoutController',  'asientos'],
        'factura'   => ['CheckoutController',  'factura'],
        'pago'      => ['CheckoutController',  'pago'],
    ];

    public function despachar(string $ruta): void
    {
        // Ruta por defecto
        if ($ruta === '') {
            $ruta = estaLogueado() ? 'cartelera' : 'login';
        }

        if (!isset($this->rutas[$ruta])) {
            http_response_code(404);
            require APP_PATH . '/views/404.php';
            return;
        }

        [$controlador, $metodo] = $this->rutas[$ruta];

        require APP_PATH . '/controllers/' . $controlador . '.php';
        $instancia = new $controlador();
        $instancia->$metodo();
    }
}
