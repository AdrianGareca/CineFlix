<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pago con QR — CineFlix</title>
  <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
  <link rel="stylesheet" href="{{ asset('css/pagoQR.css') }}">
</head>
<body>

<div class="contenedor">
  <div class="titulo">
    <h2>Pago con QR</h2>
  </div>

  <img src="{{ asset('img/QR.jpg') }}"
       alt="Código QR de pago CineFlix"
       class="qr-image">

  <p class="qr-hint">
    Escanea el código QR con tu aplicación bancaria.<br>
    Monto a pagar: <strong>Bs. {{ number_format($total, 0) }}</strong>
  </p>

  <button class="btnDescargarImg" onclick="descargarQR()" type="button">
    <h3>Descargar QR</h3>
  </button>

  <form method="POST" action="{{ route('pago.qr.procesar') }}" style="margin-top:16px;">
    @csrf
    <button type="submit" class="btnDescargarImg"
            style="background:var(--accent,#c4404a);color:#fff;">
      <h3>Confirmar pago — Bs. {{ number_format($total, 0) }}</h3>
    </button>
  </form>
</div>

<script>
  function descargarQR() {
    var a      = document.createElement('a');
    a.href     = '{{ asset('img/QR.jpg') }}';
    a.download = 'CineFlix-QR-Pago.jpg';
    a.click();
  }
</script>
</body>
</html>
