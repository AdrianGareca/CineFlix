<?php
/**
 * Configuración global de la aplicación CineFlix
 * ------------------------------------------------
 * Editar aquí los datos de conexión a la base de datos (XAMPP).
 */

// ─── Base de datos (XAMPP por defecto) ───────────────────────
define('DB_HOST', 'localhost');
define('DB_NAME', 'cineboom');
define('DB_USER', 'root');
define('DB_PASS', '');          // En XAMPP la contraseña de root suele estar vacía
define('DB_CHARSET', 'utf8mb4');

// ─── Aplicación ──────────────────────────────────────────────
define('APP_NAME', 'CineFlix');

// Ruta base del proyecto en el navegador. Se calcula automáticamente
// para que funcione sin importar la carpeta donde esté instalado.
// Se codifica cada segmento por si la carpeta tiene espacios (ej: "cineboom 2").
// Ej: /cineboom%202/CineFlix
$dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$segmentos = array_map('rawurlencode', array_filter(explode('/', $dir), 'strlen'));
define('BASE_URL', '/' . implode('/', $segmentos));

// Ruta interna (sistema de archivos) a la carpeta del proyecto
define('ROOT_PATH', dirname(__DIR__, 2));   // .../CineFlix
define('APP_PATH', ROOT_PATH . '/app');

// Carpeta donde se guardan las imágenes subidas desde el admin
define('UPLOAD_DIR', ROOT_PATH . '/uploads');
define('UPLOAD_URL', BASE_URL . '/uploads');

// Zona horaria
date_default_timezone_set('America/La_Paz');
