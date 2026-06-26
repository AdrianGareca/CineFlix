<?php
/** Vista: Iniciar sesión. Variable: $error */
$this->parcial('header', [
    'titulo'  => 'Iniciar Sesión',
    'estilos' => ['StyleLogin'],
]);
?>

<div class="login-logo">
  <svg width="40" height="40" viewBox="0 0 64 64">
    <rect x="4" y="20" width="56" height="36" rx="3" fill="none" stroke="#C4405A" stroke-width="3.5"/>
    <line x1="5" y1="19" x2="57" y2="4" stroke="#C4405A" stroke-width="3"/>
    <line x1="20" y1="10" x2="17" y2="20" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
    <line x1="32" y1="6"  x2="29" y2="16" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
    <line x1="44" y1="2"  x2="41" y2="12" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
  </svg>
  <span class="login-logo-text"><?php echo APP_NAME; ?></span>
</div>

<div class="login-card">
  <h2>Iniciar Sesión</h2>

  <?php if (!empty($error)): ?>
    <div class="error-msg" style="background:rgba(196,64,90,0.12);border:1px solid rgba(196,64,90,0.3);color:#e57a8a;padding:10px 14px;border-radius:var(--r);margin-bottom:18px;font-size:0.88rem;">
      <?php echo e($error); ?>
    </div>
  <?php endif; ?>

  <form method="POST" action="<?php echo url('login'); ?>">
    <div class="cb-field">
      <label class="cb-label" for="usuario">Usuario</label>
      <input class="cb-input" type="text" name="usuario" id="usuario" placeholder="Tu nombre de usuario" required>
    </div>
    <div class="cb-field">
      <label class="cb-label" for="contrasena">Contraseña</label>
      <input class="cb-input" type="password" name="contrasena" id="contrasena" placeholder="••••••••" required>
    </div>
    <button type="submit" class="login-submit">Entrar</button>
  </form>

  <hr class="login-divider">
  <p class="login-alt">¿No tienes una cuenta? <a href="<?php echo url('registro'); ?>">Registrarse</a></p>
</div>

<?php $this->parcial('footer', ['mostrarFooter' => false]); ?>
