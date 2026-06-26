<?php
/**
 * Layout: pie de página común.
 * Variables opcionales:
 *   $mostrarFooter -> bool, si se muestra el footer completo (default true)
 *   $scripts       -> array de scripts JS (nombres sin .js) a incluir
 */
$mostrarFooter = $mostrarFooter ?? true;
$scripts       = $scripts       ?? [];
?>
<?php if ($mostrarFooter): ?>
<footer class="cb-footer">
  <span><?php echo APP_NAME; ?> Center &nbsp;·&nbsp; Santa Cruz &nbsp;·&nbsp; +591 700-12345</span>
  <nav class="cb-footer-links">
    <a href="#">Términos</a>
    <a href="#">Privacidad</a>
    <a href="<?php echo url('contacto'); ?>">Contáctanos</a>
  </nav>
  <span>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?></span>
</footer>
<?php endif; ?>

<?php foreach ($scripts as $js): ?>
<script src="<?php echo asset('js/' . $js . '.js'); ?>"></script>
<?php endforeach; ?>
</body>
</html>
