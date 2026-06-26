<?php
/** Vista: Resumen de compra y datos de facturación (estática integrada). */
$this->parcial('header', [
    'titulo'  => 'Checkout',
    'estilos' => ['factura'],
]);
?>

<div class="conteneidor">

  <div class="factura-logo"><?php echo APP_NAME; ?></div>

  <div class="titulo0"><h2>Resumen de compra</h2></div>

  <div class="montoDcompra">
    <h4><span>Entradas (3)</span><span>Bs. 174.00</span></h4>
    <h4><span>Confitería</span><span>Bs. 30.00</span></h4>
    <h4><span>Total</span><span>Bs. 204.00</span></h4>
  </div>

  <hr class="factura-sep">

  <div class="titulo"><h2>Datos de facturación</h2></div>

  <div class="datosDefactura">
    <div class="cb-field">
      <label class="cb-label">Nombre completo</label>
      <input class="cb-input" type="text" placeholder="Tu nombre">
    </div>
    <div class="cb-field">
      <label class="cb-label">CI / NIT</label>
      <input class="cb-input" type="text" placeholder="12345678">
    </div>
  </div>

  <hr class="factura-sep">

  <div class="titulo2"><h2>Método de pago</h2></div>

  <div class="botonesDpago">
    <div class="QR">
      <a href="<?php echo url('pago', ['metodo' => 'qr']); ?>">Pagar con QR</a>
    </div>
    <div class="tarjeta">
      <a href="<?php echo url('pago', ['metodo' => 'tarjeta']); ?>">Pagar con Tarjeta</a>
    </div>
    <div class="tigoM">
      <a href="<?php echo url('pago', ['metodo' => 'tigo']); ?>">Pagar con Tigo Money</a>
    </div>
  </div>

</div>

<?php $this->parcial('footer', ['mostrarFooter' => false]); ?>
