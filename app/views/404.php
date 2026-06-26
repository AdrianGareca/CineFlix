<?php $titulo = 'Página no encontrada'; $estilos = ['estilo']; ?>
<?php $this->parcial('header', ['titulo' => $titulo, 'estilos' => $estilos, 'mostrarNav' => true]); ?>

<div class="cb-content" style="text-align:center; padding:80px 20px;">
  <h1 style="font-size:3rem; margin-bottom:10px;">404</h1>
  <p class="cb-section-subtitle">La página que buscas no existe.</p>
  <p style="margin-top:24px;">
    <a class="boton-ver-mas" href="<?php echo url('cartelera'); ?>">Volver a la cartelera &rarr;</a>
  </p>
</div>

<?php $this->parcial('footer'); ?>
