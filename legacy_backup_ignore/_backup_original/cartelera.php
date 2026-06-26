<?php
session_start();
require 'conexion.php';

// Películas para el carrusel (primeras 4) — loop manual, compatible con cualquier PHP
$carousel_films = [];
$result_carousel = $conn->query("SELECT * FROM peliculas ORDER BY creado_en DESC LIMIT 4");
if ($result_carousel) {
    while ($row = $result_carousel->fetch_assoc()) {
        $carousel_films[] = $row;
    }
}

// Todas las películas para el grid
$result_grid = $conn->query("SELECT * FROM peliculas ORDER BY creado_en DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cartelera — CineFlix</title>
  <link rel="stylesheet" href="css/design-system.css">
  <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

  <!-- ── Navbar ───────────────────────────────────────────── -->
  <nav class="cb-nav">
    <a class="cb-nav-logo" href="cartelera.php">
      <svg width="22" height="22" viewBox="0 0 64 64">
        <rect x="4" y="20" width="56" height="36" rx="3" fill="none" stroke="#C4405A" stroke-width="3.5"/>
        <line x1="5" y1="19" x2="57" y2="4" stroke="#C4405A" stroke-width="3"/>
        <line x1="20" y1="10" x2="17" y2="20" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
        <line x1="32" y1="6"  x2="29" y2="16" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
        <line x1="44" y1="2"  x2="41" y2="12" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
      </svg>
      CineFlix
    </a>
    <nav class="cb-nav-links">
      <a href="#">Películas</a>
      <a href="#">Carteleras</a>
      <a href="#">Ofertas</a>
      <a href="golosinas.php">Confitería</a>
      <?php if (isset($_SESSION['usuario_nombre'])): ?>
        <a href="logout.php" class="nav-salir">Salir</a>
      <?php endif; ?>
    </nav>
  </nav>

  <!-- ── Hero Carousel ────────────────────────────────────── -->
  <?php if (!empty($carousel_films)): ?>
  <div class="carrusel-hero">
    <div class="carrusel-pista" id="carruselPista">

      <?php foreach ($carousel_films as $f): ?>
      <div class="carrusel-slide">
        <img src="<?php echo htmlspecialchars($f['imagen']); ?>"
             alt="<?php echo htmlspecialchars($f['titulo']); ?>"
             class="carrusel-bg">
        <div class="carrusel-contenido">
          <span class="carrusel-etiqueta"><?php echo htmlspecialchars($f['genero']); ?></span>
          <h2 class="carrusel-titulo"><?php echo htmlspecialchars($f['titulo']); ?></h2>
          <p class="carrusel-meta"><?php echo htmlspecialchars($f['duracion']); ?></p>
          <a href="vermas.php?id=<?php echo intval($f['id']); ?>" class="carrusel-boton">Ver más</a>
        </div>
      </div>
      <?php endforeach; ?>

    </div>

    <button class="carrusel-prev" id="carruselPrev" aria-label="Anterior">&#8249;</button>
    <button class="carrusel-next" id="carruselNext" aria-label="Siguiente">&#8250;</button>

    <div class="carrusel-dots" id="carruselDots">
      <?php foreach ($carousel_films as $idx => $f): ?>
        <button class="dot <?php echo $idx === 0 ? 'activo' : ''; ?>"
                data-index="<?php echo $idx; ?>"
                aria-label="Slide <?php echo $idx + 1; ?>"></button>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- ── Grid de películas ─────────────────────────────────── -->
  <div class="cb-content">
    <h2 class="cb-section-title">En cartelera</h2>
    <p class="cb-section-subtitle">Santa Cruz &nbsp;·&nbsp; Esta semana</p>

    <div class="peliculas-grid">
      <?php if ($result_grid && $result_grid->num_rows > 0):
            while ($p = $result_grid->fetch_assoc()): ?>
      <div class="tarjeta-pelicula">
        <img src="<?php echo htmlspecialchars($p['imagen']); ?>"
             alt="<?php echo htmlspecialchars($p['titulo']); ?>">
        <div class="info">
          <span class="cb-tag cb-tag-dark"><?php echo htmlspecialchars($p['genero']); ?></span>
          <h3><?php echo htmlspecialchars($p['titulo']); ?></h3>
          <p class="meta"><?php echo htmlspecialchars($p['duracion']); ?></p>
          <a href="vermas.php?id=<?php echo intval($p['id']); ?>" class="boton-ver-mas">Ver más &rarr;</a>
        </div>
      </div>
      <?php endwhile;
      else: ?>
        <p class="sin-peliculas">No hay películas en cartelera por el momento.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- ── Footer ────────────────────────────────────────────── -->
  <footer class="cb-footer">
    <span>CineFlix Center &nbsp;·&nbsp; Santa Cruz &nbsp;·&nbsp; +591 700-12345</span>
    <nav class="cb-footer-links">
      <a href="#">Términos</a>
      <a href="#">Privacidad</a>
      <a href="#">Contáctanos</a>
    </nav>
    <span>&copy; 2025 CineFlix</span>
  </footer>

  <script src="SCRIPTS/carrusel.js"></script>

</body>
</html>
