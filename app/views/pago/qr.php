<?php
/** Vista: Pago con QR. */
$this->parcial('header', [
    'titulo'  => 'Pago QR',
    'estilos' => ['pagoQR'],
]);
?>

<div class="contenedor">
  <div class="titulo"><h2>Escanea el código QR</h2></div>
  <img class="qr-image" src="<?php echo asset('img/QR.jpg'); ?>" alt="Código QR de pago">
  <p class="qr-hint">Abre tu app bancaria, escanea el QR y confirma el pago de <strong>Bs. 204.00</strong></p>
  <button class="btnDescargarImg" onclick="window.location.href='<?php echo url('cartelera'); ?>'">
    <h3>Confirmar pago</h3>
  </button>
</div>

<?php $this->parcial('footer', ['mostrarFooter' => false]); ?>
