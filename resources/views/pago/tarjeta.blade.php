<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pago con Tarjeta — CineFlix</title>
  <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
  <link rel="stylesheet" href="{{ asset('css/pagotarjeta.css') }}">
</head>
<body>

<div class="contenedor">
  <div class="titulo">
    <svg width="28" height="22" viewBox="0 0 28 22" fill="none">
      <rect x="1" y="1" width="26" height="20" rx="3"
            stroke="currentColor" stroke-width="1.8"/>
      <line x1="1" y1="7" x2="27" y2="7"
            stroke="currentColor" stroke-width="2"/>
      <rect x="4" y="13" width="6" height="3" rx="1"
            fill="currentColor"/>
    </svg>
    <h2>Pago con Tarjeta</h2>
  </div>

  <p style="font-size:0.85rem;color:var(--text-2);margin-bottom:20px;">
    Total: <strong style="color:var(--text-1);">Bs. {{ number_format($total, 0) }}</strong>
  </p>

  <div class="cajas">
    <div class="cb-field">
      <label class="cb-label">Número de tarjeta</label>
      <input class="cb-input" type="text" placeholder="0000 0000 0000 0000"
             maxlength="19" oninput="formatearTarjeta(this)">
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
      <div class="cb-field">
        <label class="cb-label">Vencimiento</label>
        <input class="cb-input" type="text" placeholder="MM/AA" maxlength="5">
      </div>
      <div class="cb-field">
        <label class="cb-label">CVV</label>
        <input class="cb-input" type="text" placeholder="123" maxlength="4">
      </div>
    </div>
    <div class="cb-field">
      <label class="cb-label">Nombre en la tarjeta</label>
      <input class="cb-input" type="text" placeholder="NOMBRE APELLIDO"
             style="text-transform:uppercase;">
    </div>
  </div>

  <form method="POST" action="{{ route('pago.tarjeta.procesar') }}">
    @csrf
    <button type="submit" class="btnPagar">
      <h3>Pagar Bs. {{ number_format($total, 0) }}</h3>
    </button>
  </form>
</div>

<script>
  function formatearTarjeta(input) {
    var v = input.value.replace(/\D/g, '').substring(0, 16);
    input.value = v.replace(/(.{4})/g, '$1 ').trim();
  }
</script>
</body>
</html>
