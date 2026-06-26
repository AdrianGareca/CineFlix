<?php
/**
 * Layout: cabecera del panel de administración (sidebar incluido).
 * Variables: $seccion ('peliculas' | 'confiteria')
 */
$seccion = $seccion ?? 'peliculas';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Admin — <?php echo APP_NAME; ?></title>
  <link rel="stylesheet" href="<?php echo asset('css/design-system.css'); ?>">
  <link rel="stylesheet" href="<?php echo asset('css/admin.css'); ?>">
</head>
<body>

<div class="admin-wrapper">

  <aside class="sidebar">
    <div class="sidebar-logo">
      <svg width="40" height="40">
        <rect x="2" y="14" width="36" height="23" style="fill:#1c1c1c; stroke:rgb(158,7,7); stroke-width:3"/>
        <line x1="3" y1="13" x2="36" y2="3" style="stroke:rgb(175,12,12); stroke-width:3"/>
        <line x1="13" y1="7" x2="11" y2="14" style="stroke:#fff; stroke-width:1.5"/>
        <line x1="20" y1="5" x2="18" y2="12" style="stroke:#fff; stroke-width:1.5"/>
        <line x1="28" y1="3" x2="26" y2="10" style="stroke:#fff; stroke-width:1.5"/>
      </svg>
      <span><?php echo APP_NAME; ?></span>
    </div>

    <p class="sidebar-bienvenida">Hola, <?php echo e($_SESSION['usuario_nombre'] ?? ''); ?></p>

    <nav class="sidebar-nav">
      <a href="<?php echo url('admin', ['seccion' => 'peliculas']); ?>"
         class="nav-item <?php echo $seccion === 'peliculas' ? 'activo' : ''; ?>">
        <svg viewBox="0 0 24 24" width="20" height="20">
          <path d="M3 6 H15 V18 H3 Z M15 10 L21 6 V18 L15 14 Z" fill="currentColor"/>
        </svg>
        Películas
      </a>
      <a href="<?php echo url('admin', ['seccion' => 'confiteria']); ?>"
         class="nav-item <?php echo $seccion === 'confiteria' ? 'activo' : ''; ?>">
        <svg viewBox="0 0 24 24" width="20" height="20">
          <path d="M7 4 H17 L20 20 H4 Z" fill="currentColor"/>
        </svg>
        Confitería
      </a>
      <a href="<?php echo url('cartelera'); ?>" class="nav-item">
        <svg viewBox="0 0 24 24" width="20" height="20">
          <path d="M4 5h16v14H4z M4 9h16" stroke="currentColor" stroke-width="2" fill="none"/>
        </svg>
        Ver sitio
      </a>
    </nav>

    <a href="<?php echo url('logout'); ?>" class="btn-cerrar">
      <svg viewBox="0 0 24 24" width="18" height="18">
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"
              stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
      </svg>
      Cerrar Sesión
    </a>
  </aside>

  <main class="contenido-admin">

    <?php if (!empty($mensaje)): ?>
      <div class="alerta <?php echo e($tipo); ?>"><?php echo e($mensaje); ?></div>
    <?php endif; ?>
