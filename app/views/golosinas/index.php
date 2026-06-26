<?php
/** Vista: Confitería pública. Variable: $productos (array) */
$this->parcial('header', [
    'titulo'     => 'Confitería',
    'estilos'    => ['golosina'],
    'mostrarNav' => true,
]);
?>

<!-- Barra volver -->
<div class="cb-back-bar">
  <a href="<?php echo url('asientos'); ?>" class="cb-back">&#8592; Volver a butacas</a>
  <span class="cb-timer">06:11</span>
</div>

<!-- Encabezado -->
<div class="golosinas-header">
  <h1>Confitería</h1>
  <p>Precios exclusivos para compras en línea</p>
</div>

<!-- Grid de productos -->
<div class="golosinas-grid">
  <?php if (!empty($productos)): ?>
    <?php foreach ($productos as $prod): ?>
    <div class="tarjeta-pelicula">
      <img src="<?php echo asset(e($prod['imagen'])); ?>" alt="<?php echo e($prod['titulo']); ?>">
      <div class="info">
        <h3><?php echo e($prod['titulo']); ?></h3>
        <?php if ($prod['descripcion']): ?>
          <p><?php echo e($prod['descripcion']); ?></p>
        <?php endif; ?>
        <p class="precio">Bs. <?php echo number_format((float)$prod['precio'], 0); ?></p>
        <div class="contador">
          <button class="btn-menos" aria-label="Reducir">
            <svg viewBox="0 0 20 20" width="14" height="14"><rect x="2" y="9" width="16" height="2"/></svg>
          </button>
          <span class="cantidad">0</span>
          <button class="btn-mas" aria-label="Aumentar">
            <svg viewBox="0 0 20 20" width="14" height="14">
              <rect x="9" y="2" width="2" height="16"/><rect x="2" y="9" width="16" height="2"/>
            </svg>
          </button>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p style="color:var(--text-2);padding:40px 0;grid-column:1/-1;text-align:center;">
      No hay productos disponibles.
    </p>
  <?php endif; ?>
</div>

<!-- Botón fijo comprar -->
<div class="boton-comprar-container">
  <a href="<?php echo url('factura'); ?>" class="boton-comprar">Ir al pago &rarr;</a>
</div>

<script>
  document.querySelectorAll('.contador').forEach(function (contador) {
    var btnMas   = contador.querySelector('.btn-mas');
    var btnMenos = contador.querySelector('.btn-menos');
    var cantidad = contador.querySelector('.cantidad');
    btnMas.addEventListener('click', function () {
      cantidad.textContent = parseInt(cantidad.textContent) + 1;
    });
    btnMenos.addEventListener('click', function () {
      var val = parseInt(cantidad.textContent);
      if (val > 0) cantidad.textContent = val - 1;
    });
  });
</script>

<?php $this->parcial('footer', ['mostrarFooter' => false]); ?>
