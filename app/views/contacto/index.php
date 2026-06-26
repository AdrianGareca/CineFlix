<?php
/**
 * Vista: Formulario de contacto.
 * Variables: $exito (string), $errores (array), $form (array)
 */
$this->parcial('header', [
    'titulo'     => 'Contáctanos',
    'estilos'    => ['StyleLogin', 'estilo'],
    'mostrarNav' => true,
]);
?>

<!-- ── Hero compacto ──────────────────────────────────────────── -->
<div style="background:linear-gradient(135deg,#1a1a2e 0%,#16213e 60%,#0f3460 100%);
            padding:56px 24px 48px;text-align:center;">
  <span style="display:inline-block;background:rgba(196,64,90,0.15);border:1px solid rgba(196,64,90,0.4);
               color:#C4405A;font-size:0.78rem;letter-spacing:2px;text-transform:uppercase;
               padding:4px 14px;border-radius:20px;margin-bottom:16px;">Soporte & Consultas</span>
  <h1 style="color:#fff;font-size:clamp(1.6rem,4vw,2.4rem);margin:0 0 12px;">¿Tienes alguna pregunta?</h1>
  <p style="color:rgba(255,255,255,0.6);max-width:480px;margin:0 auto;font-size:0.95rem;line-height:1.6;">
    Escríbenos y nuestro equipo te responderá lo antes posible.
  </p>
</div>

<!-- ── Contenedor principal ───────────────────────────────────── -->
<div style="max-width:640px;margin:48px auto;padding:0 20px 64px;">

  <!-- Mensaje de éxito -->
  <?php if (!empty($exito)): ?>
  <div style="background:rgba(39,174,96,0.12);border:1px solid rgba(39,174,96,0.35);
              color:#6fd96f;padding:16px 20px;border-radius:10px;margin-bottom:28px;
              font-size:0.93rem;display:flex;align-items:center;gap:10px;">
    <span style="font-size:1.3rem;">✓</span>
    <?php echo $exito; ?>
  </div>
  <?php endif; ?>

  <!-- Mensajes de error del servidor -->
  <?php if (!empty($errores)): ?>
  <div style="background:rgba(196,64,90,0.12);border:1px solid rgba(196,64,90,0.35);
              color:#e57a8a;padding:16px 20px;border-radius:10px;margin-bottom:28px;font-size:0.88rem;">
    <ul style="margin:0;padding-left:18px;">
      <?php foreach ($errores as $err): ?>
        <li><?php echo e($err); ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php endif; ?>

  <!-- Tarjeta del formulario -->
  <div class="login-card" style="max-width:100%;padding:36px 40px;">
    <h2 style="margin-top:0;margin-bottom:28px;font-size:1.25rem;">Envíanos un mensaje</h2>

    <form id="contactForm" method="POST" action="<?php echo url('contacto'); ?>" novalidate>

      <!-- Nombre -->
      <div class="cb-field" style="margin-bottom:20px;">
        <label class="cb-label" for="nombre">Nombre completo</label>
        <input class="cb-input" id="nombre" type="text" name="nombre"
               placeholder="Tu nombre"
               value="<?php echo e($form['nombre']); ?>">
        <span class="campo-error" id="err-nombre"
              style="display:none;color:#e57a8a;font-size:0.8rem;margin-top:4px;"></span>
      </div>

      <!-- Correo -->
      <div class="cb-field" style="margin-bottom:20px;">
        <label class="cb-label" for="correo">Correo electrónico</label>
        <input class="cb-input" id="correo" type="email" name="correo"
               placeholder="correo@ejemplo.com"
               value="<?php echo e($form['correo']); ?>">
        <span class="campo-error" id="err-correo"
              style="display:none;color:#e57a8a;font-size:0.8rem;margin-top:4px;"></span>
      </div>

      <!-- Mensaje -->
      <div class="cb-field" style="margin-bottom:28px;">
        <label class="cb-label" for="mensaje">Mensaje</label>
        <textarea class="cb-input" id="mensaje" name="mensaje" rows="5"
                  placeholder="Cuéntanos en qué podemos ayudarte..."
                  style="resize:vertical;height:auto;"><?php echo e($form['mensaje']); ?></textarea>
        <span class="campo-error" id="err-mensaje"
              style="display:none;color:#e57a8a;font-size:0.8rem;margin-top:4px;"></span>
      </div>

      <button type="submit" class="login-submit" style="width:100%;">Enviar mensaje</button>

    </form>
  </div>

  <!-- Datos de contacto alternativos -->
  <div style="margin-top:32px;display:grid;grid-template-columns:1fr 1fr;gap:16px;text-align:center;">
    <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);
                border-radius:10px;padding:20px 16px;">
      <div style="font-size:1.4rem;margin-bottom:8px;">📍</div>
      <div style="color:var(--text-2);font-size:0.82rem;">Dirección</div>
      <div style="font-size:0.9rem;margin-top:4px;">Av. Trompillo, Santa Cruz</div>
    </div>
    <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);
                border-radius:10px;padding:20px 16px;">
      <div style="font-size:1.4rem;margin-bottom:8px;">📞</div>
      <div style="color:var(--text-2);font-size:0.82rem;">Teléfono</div>
      <div style="font-size:0.9rem;margin-top:4px;">+591 700-12345</div>
    </div>
  </div>

</div>

<!-- ── Validación JS (client-side, primera línea de defensa) ─── -->
<script>
(function () {
  var form     = document.getElementById('contactForm');
  var reEmail  = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  function mostrarError(id, msg) {
    var el = document.getElementById(id);
    el.textContent = msg;
    el.style.display = 'block';
    document.getElementById(id.replace('err-', '')).style.borderColor = '#C4405A';
  }

  function limpiarError(id) {
    var el = document.getElementById(id);
    el.style.display = 'none';
    document.getElementById(id.replace('err-', '')).style.borderColor = '';
  }

  // Validación en tiempo real al salir del campo
  ['nombre', 'correo', 'mensaje'].forEach(function (campo) {
    document.getElementById(campo).addEventListener('blur', function () {
      validarCampo(campo);
    });
  });

  function validarCampo(campo) {
    var val = document.getElementById(campo).value.trim();
    limpiarError('err-' + campo);

    if (campo === 'nombre' && val === '') {
      mostrarError('err-nombre', 'El nombre es obligatorio.');
      return false;
    }
    if (campo === 'correo') {
      if (val === '' || !reEmail.test(val)) {
        mostrarError('err-correo', 'Ingresa un correo electrónico válido.');
        return false;
      }
    }
    if (campo === 'mensaje' && val.length < 10) {
      mostrarError('err-mensaje', 'El mensaje debe tener al menos 10 caracteres.');
      return false;
    }
    return true;
  }

  form.addEventListener('submit', function (e) {
    var ok = ['nombre', 'correo', 'mensaje'].map(validarCampo).every(Boolean);
    if (!ok) {
      e.preventDefault();
      // Desplazar hasta el primer error visible
      var primer = form.querySelector('.campo-error[style*="block"]');
      if (primer) primer.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  });
})();
</script>

<?php $this->parcial('footer', ['mostrarFooter' => true]); ?>
