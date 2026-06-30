<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tigo Money — CineFlix</title>
  <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
  <link rel="stylesheet" href="{{ asset('css/pagoTigoM.css') }}">
</head>
<body>

<div style="min-height:100vh;display:flex;flex-direction:column;
            align-items:center;justify-content:center;padding:40px 16px;">

  <div style="background:var(--surface);border:1px solid var(--border);
              border-radius:var(--r-lg);padding:36px 40px;width:100%;max-width:440px;
              box-shadow:0 0 0 1px rgba(255,255,255,0.06),0 8px 32px rgba(0,0,0,0.5);
              animation:cardIn 700ms var(--ease) forwards;opacity:0;transform:translateY(16px);">

    <div style="margin-bottom:24px;padding-bottom:16px;border-bottom:1px solid var(--border);">
      <h2 style="font-family:var(--font-serif);font-size:1.3rem;font-weight:400;
                 letter-spacing:-0.01em;color:var(--text-1);">
        Pago con Tigo Money
      </h2>
    </div>

    <p style="font-size:0.85rem;color:var(--text-2);margin-bottom:24px;">
      Total a pagar:
      <strong style="color:var(--text-1);font-size:1.1rem;">
        Bs. {{ number_format($total, 0) }}
      </strong>
    </p>

    <div class="cb-field">
      <label class="cb-label">Número Tigo Money</label>
      <input class="cb-input" type="tel" placeholder="7X XXX XXXX"
             maxlength="11" oninput="this.value=this.value.replace(/\D/g,'')">
    </div>

    <div class="cb-field">
      <label class="cb-label">PIN de confirmación</label>
      <input class="cb-input" type="password" placeholder="••••" maxlength="4">
    </div>

    <button style="margin-top:24px;display:block;width:100%;padding:13px;
                   background:var(--text-1);color:#0F0F0F;border:none;
                   border-radius:var(--r);font-size:0.9rem;font-weight:700;
                   cursor:pointer;transition:background-color 200ms,transform 160ms var(--ease);
                   letter-spacing:0.03em;"
            onmouseover="this.style.background='#d8d5d0'"
            onmouseout="this.style.background='var(--text-1)'"
            onclick="alert('✓ Pago procesado exitosamente. ¡Disfruta la película!')">
      Confirmar pago — Bs. {{ number_format($total, 0) }}
    </button>
  </div>
</div>

<style>
  @keyframes cardIn { to { opacity:1; transform:none; } }
</style>

</body>
</html>
