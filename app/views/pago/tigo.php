<?php
/** Vista: Pago con Tigo Money. */
$this->parcial('header', [
    'titulo'  => 'Tigo Money',
    'estilos' => ['pagoTigoM'],
]);
?>

<div class="contenedor">
  <div class="titulo"><h2>Pago con Tigo Money</h2></div>
  <div class="cajaNumeroDcelular">
    <div class="cb-field">
      <label class="cb-label">Número de celular</label>
      <input class="cb-input" type="tel" placeholder="7X XXX XXX" maxlength="8">
    </div>
  </div>
  <p class="tigo-hint">Recibirás un mensaje de Tigo Money para confirmar el pago de <strong>Bs. 204.00</strong>. Autoriza el pago desde tu celular.</p>
  <button class="btnPagar" onclick="window.location.href='<?php echo url('cartelera'); ?>'">
    <h3>Enviar solicitud de pago</h3>
  </button>
</div>

<?php $this->parcial('footer', ['mostrarFooter' => false]); ?>
