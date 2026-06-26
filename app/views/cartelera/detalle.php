<?php
/** Vista: Detalle de película. Variable: $p (array) */
$this->parcial('header', [
    'titulo'  => $p['titulo'],
    'estilos' => ['estilos'],
]);
?>

<!-- Barra de volver -->
<div class="cb-back-bar">
  <a href="<?php echo url('cartelera'); ?>" class="cb-back">&#8592; Cartelera</a>
</div>

<div class="contenedor">

  <div class="contenedor-izquierda">
    <img src="<?php echo asset(e($p['imagen'])); ?>" alt="<?php echo e($p['titulo']); ?>">
  </div>

  <div class="contenedor-derecha">
    <h2><?php echo e($p['titulo']); ?></h2>
    <p class="resumen"><?php echo e($p['descripcion']); ?></p>

    <div class="datos">
      <p><strong>Duración</strong> <?php echo e($p['duracion']); ?></p>
      <p><strong>Género</strong> <?php echo e($p['genero']); ?></p>

      <div class="calificacion">
        <strong>Calificación</strong>
        <div class="stars">
          <?php for ($i = 1; $i <= 5; $i++): ?>
            <div class="star <?php echo $i <= $p['calificacion'] ? '' : 'empty'; ?>"></div>
          <?php endfor; ?>
        </div>
      </div>
    </div>

    <div class="entradas">
      <a href="#" class="boton-volver">Ver trailer</a>
      <a href="<?php echo url('asientos'); ?>" class="boton-volver primary">Comprar entradas</a>
      <a href="<?php echo url('cartelera'); ?>" class="boton-volver">Volver</a>
    </div>
  </div>

</div>

<?php $this->parcial('footer', ['mostrarFooter' => false]); ?>
