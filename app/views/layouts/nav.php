<?php /* Layout: barra de navegación pública */ ?>
<nav class="cb-nav">
  <a class="cb-nav-logo" href="<?php echo url('cartelera'); ?>">
    <svg width="22" height="22" viewBox="0 0 64 64">
      <rect x="4" y="20" width="56" height="36" rx="3" fill="none" stroke="#C4405A" stroke-width="3.5"/>
      <line x1="5" y1="19" x2="57" y2="4" stroke="#C4405A" stroke-width="3"/>
      <line x1="20" y1="10" x2="17" y2="20" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
      <line x1="32" y1="6"  x2="29" y2="16" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
      <line x1="44" y1="2"  x2="41" y2="12" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
    </svg>
    <?php echo APP_NAME; ?>
  </a>
  <nav class="cb-nav-links">
    <a href="<?php echo url('cartelera'); ?>">Cartelera</a>
    <a href="<?php echo url('golosinas'); ?>">Confitería</a>
    <?php if (esAdmin()): ?>
      <a href="<?php echo url('admin'); ?>">Admin</a>
    <?php endif; ?>
    <?php if (estaLogueado()): ?>
      <a href="<?php echo url('logout'); ?>" class="nav-salir">Salir</a>
    <?php else: ?>
      <a href="<?php echo url('login'); ?>" class="nav-salir">Entrar</a>
    <?php endif; ?>
  </nav>
</nav>
