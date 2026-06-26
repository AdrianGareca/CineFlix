<?php
/**
 * Layout: cabecera común (<head> + apertura de <body>).
 * Variables esperadas:
 *   $titulo     -> título de la pestaña
 *   $estilos    -> array de hojas de estilo (nombres sin .css), ej: ['estilo']
 *   $mostrarNav -> bool, si se incluye la barra de navegación pública
 */
$titulo     = $titulo     ?? APP_NAME;
$estilos    = $estilos    ?? [];
$mostrarNav = $mostrarNav ?? false;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo e($titulo); ?> — <?php echo APP_NAME; ?></title>
  <link rel="stylesheet" href="<?php echo asset('css/design-system.css'); ?>">
  <?php foreach ($estilos as $hoja): ?>
  <link rel="stylesheet" href="<?php echo asset('css/' . $hoja . '.css'); ?>">
  <?php endforeach; ?>
</head>
<body>
<?php if ($mostrarNav) $this->parcial('nav'); ?>
