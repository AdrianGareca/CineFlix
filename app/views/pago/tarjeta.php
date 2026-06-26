<?php
/** Vista: Pago con tarjeta. */
$this->parcial('header', [
    'titulo'  => 'Pago con Tarjeta',
    'estilos' => ['pagotarjeta'],
]);
?>

<div class="contenedor">
  <div class="titulo">
    <svg viewBox="0 0 24 24" width="28" height="28" fill="none">
      <rect x="2" y="5" width="20" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/>
      <line x1="2" y1="10" x2="22" y2="10" stroke="currentColor" stroke-width="1.5"/>
    </svg>
    <h2>Pago con Tarjeta</h2>
  </div>
  <div class="cajas">
    <div class="cb-field">
      <label class="cb-label">Número de tarjeta</label>
      <input class="cb-input" type="text" placeholder="0000 0000 0000 0000" maxlength="19">
    </div>
    <div class="cb-field">
      <label class="cb-label">Nombre en la tarjeta</label>
      <input class="cb-input" type="text" placeholder="Como aparece en la tarjeta">
    </div>
    <div class="cb-field">
      <label class="cb-label">Vencimiento</label>
      <input class="cb-input" type="text" placeholder="MM/AA" maxlength="5">
    </div>
    <div class="cb-field">
      <label class="cb-label">CVV</label>
      <input class="cb-input" type="text" placeholder="•••" maxlength="4">
    </div>
  </div>
  <button class="btnPagar" onclick="window.location.href='<?php echo url('cartelera'); ?>'">
    <h3>Pagar Bs. 204.00</h3>
  </button>
</div>

<?php $this->parcial('footer', ['mostrarFooter' => false]); ?>
