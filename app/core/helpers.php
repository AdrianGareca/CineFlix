<?php
/**
 * helpers.php — Funciones de utilidad disponibles en toda la app
 */

/**
 * Genera una URL interna hacia el front controller.
 *   url('cartelera')               -> /BASE/index.php?ruta=cartelera
 *   url('vermas', ['id' => 3])     -> /BASE/index.php?ruta=vermas&id=3
 */
function url(string $ruta = '', array $params = []): string
{
    $qs = ['ruta' => $ruta] + $params;
    return BASE_URL . '/index.php?' . http_build_query($qs);
}

/** URL hacia un recurso estático (css, img, js, uploads...) */
function asset(string $ruta): string
{
    return BASE_URL . '/' . ltrim($ruta, '/');
}

/** Escapa texto para imprimirlo de forma segura en HTML */
function e(?string $texto): string
{
    return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
}

/** Redirige a una ruta interna y termina la ejecución */
function redirigir(string $ruta = 'cartelera', array $params = []): void
{
    header('Location: ' . url($ruta, $params));
    exit;
}

/** ¿Hay una sesión de usuario iniciada? */
function estaLogueado(): bool
{
    return isset($_SESSION['usuario_id']);
}

/** ¿El usuario actual es administrador? */
function esAdmin(): bool
{
    return isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin';
}

/** Exige sesión iniciada; si no, redirige al login */
function requerirLogin(): void
{
    if (!estaLogueado()) {
        redirigir('login');
    }
}

/** Exige rol admin; si no, redirige al login */
function requerirAdmin(): void
{
    if (!esAdmin()) {
        redirigir('login');
    }
}
