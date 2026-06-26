<?php
/** Vista: Cartelera. Variables: $carrusel (array), $peliculas (array) */
$this->parcial('header', [
    'titulo'     => 'Cartelera',
    'estilos'    => ['estilo'],
    'mostrarNav' => true,
]);
?>

<!-- ── Hero Carousel ────────────────────────────────────── -->
<?php if (!empty($carrusel)): ?>
<div class="carrusel-hero">
  <div class="carrusel-pista" id="carruselPista">
    <?php foreach ($carrusel as $f): ?>
    <div class="carrusel-slide">
      <img src="<?php echo asset(e($f['imagen'])); ?>"
           alt="<?php echo e($f['titulo']); ?>" class="carrusel-bg">
      <div class="carrusel-contenido">
        <span class="carrusel-etiqueta"><?php echo e($f['genero']); ?></span>
        <h2 class="carrusel-titulo"><?php echo e($f['titulo']); ?></h2>
        <p class="carrusel-meta"><?php echo e($f['duracion']); ?></p>
        <a href="<?php echo url('vermas', ['id' => (int)$f['id']]); ?>" class="carrusel-boton">Ver más</a>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <button class="carrusel-prev" id="carruselPrev" aria-label="Anterior">&#8249;</button>
  <button class="carrusel-next" id="carruselNext" aria-label="Siguiente">&#8250;</button>

  <div class="carrusel-dots" id="carruselDots">
    <?php foreach ($carrusel as $idx => $f): ?>
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
    <?php if (!empty($peliculas)): ?>
      <?php foreach ($peliculas as $p): ?>
      <div class="tarjeta-pelicula">
        <img src="<?php echo asset(e($p['imagen'])); ?>" alt="<?php echo e($p['titulo']); ?>">
        <div class="info">
          <span class="cb-tag cb-tag-dark"><?php echo e($p['genero']); ?></span>
          <h3><?php echo e($p['titulo']); ?></h3>
          <p class="meta"><?php echo e($p['duracion']); ?></p>
          <a href="<?php echo url('vermas', ['id' => (int)$p['id']]); ?>" class="boton-ver-mas">Ver más &rarr;</a>
        </div>
      </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="sin-peliculas">No hay películas en cartelera por el momento.</p>
    <?php endif; ?>
  </div>
</div>

<?php $this->parcial('footer', ['scripts' => ['carrusel']]); ?>
