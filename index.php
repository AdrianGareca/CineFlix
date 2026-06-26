<?php
/**
 * index.php — FRONT CONTROLLER de CineFlix
 * =========================================
 * Único punto de entrada de la aplicación. Toda petición pasa por aquí:
 *   index.php?ruta=cartelera
 *   index.php?ruta=vermas&id=3
 *   index.php?ruta=admin&seccion=peliculas
 *
 * Arquitectura MVC:
 *   app/config   -> configuración
 *   app/core     -> Database, Router, Controller, helpers
 *   app/models   -> acceso a datos (PDO)
 *   app/controllers -> lógica
 *   app/views    -> presentación (HTML)
 */

session_start();

// 1) Configuración y utilidades
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/core/helpers.php';

// 2) Núcleo
require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/app/core/Controller.php';
require_once __DIR__ . '/app/core/Router.php';

// 3) Despachar la petición
$ruta = isset($_GET['ruta']) ? trim($_GET['ruta']) : '';

$router = new Router();
$router->despachar($ruta);
