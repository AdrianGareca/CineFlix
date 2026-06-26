<?php
session_start();
require 'conexion.php';

$productos = $conn->query("SELECT * FROM confiteria ORDER BY creado_en ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Confitería — CineFlix</title>
  <link rel="stylesheet" href="css/design-system.css">
  <link rel="stylesheet" href="css/golosina.css">
</head>
<body>

  <!-- Navbar -->
  <nav class="cb-nav">
    <a class="cb-nav-logo" href="cartelera.php">
      <svg width="22" height="22" viewBox="0 0 64 64">
        <rect x="4" y="20" width="56" height="36" rx="3" fill="none" stroke="#C4405A" stroke-width="3.5"/>
        <line x1="5" y1="19" x2="57" y2="4" stroke="#C4405A" stroke-width="3"/>
        <line x1="20" y1="10" x2="17" y2="20" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
        <line x1="32" y1="6"  x2="29" y2="16" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
        <line x1="44" y1="2"  x2="41" y2="12" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
      </svg>
      CineFlix
    </a>
    <nav class="cb-nav-links">
      <a href="cartelera.php">Cartelera</a>
      <?php if (isset($_SESSION['usuario_nombre'])): ?>
        <a href="logout.php" class="nav-salir">Salir</a>
      <?php endif; ?>
    </nav>
  </nav>

  <!-- Barra volver -->
  <div class="cb-back-bar">
    <a href="HTML/ASIENTOS.html" class="cb-back">&#8592; Volver a butacas</a>
    <span class="cb-timer">06:11</span>
  </div>

  <!-- Encabezado -->
  <div class="golosinas-header">
    <h1>Confitería</h1>
    <p>Precios exclusivos para compras en línea</p>
  </div>

  <!-- Grid de productos -->
  <div class="golosinas-grid">

    <?php if ($productos && $productos->num_rows > 0): ?>
      <?php while ($prod = $productos->fetch_assoc()): ?>
      <div class="tarjeta-pelicula">
        <img src="<?php echo htmlspecialchars($prod['imagen']); ?>"
             alt="<?php echo htmlspecialchars($prod['titulo']); ?>">
        <div class="info">
          <h3><?php echo htmlspecialchars($prod['titulo']); ?></h3>
          <?php if ($prod['descripcion']): ?>
            <p><?php echo htmlspecialchars($prod['descripcion']); ?></p>
          <?php endif; ?>
          <p class="precio">Bs. <?php echo number_format($prod['precio'], 0); ?></p>
          <div class="contador">
            <button class="btn-menos" aria-label="Reducir">
              <svg viewBox="0 0 20 20" width="14" height="14">
                <rect x="2" y="9" width="16" height="2"/>
              </svg>
            </button>
            <span class="cantidad">0</span>
            <button class="btn-mas" aria-label="Aumentar">
              <svg viewBox="0 0 20 20" width="14" height="14">
                <rect x="9" y="2" width="2" height="16"/>
                <rect x="2" y="9" width="16" height="2"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p style="color:var(--text-2);padding:40px 0;grid-column:1/-1;text-align:center;">
        No hay productos disponibles.
      </p>
    <?php endif; ?>

  </div>

  <!-- Botón fijo comprar -->
  <div class="boton-comprar-container">
    <a href="HTML/DatosFactura.html" class="boton-comprar">
      Ir al pago &rarr;
    </a>
  </div>

  <!-- Script contadores -->
  <script>
    document.querySelectorAll('.contador').forEach(function(contador) {
      var btnMas   = contador.querySelector('.btn-mas');
      var btnMenos = contador.querySelector('.btn-menos');
      var cantidad = contador.querySelector('.cantidad');

      btnMas.addEventListener('click', function() {
        cantidad.textContent = parseInt(cantidad.textContent) + 1;
      });
      btnMenos.addEventListener('click', function() {
        var val = parseInt(cantidad.textContent);
        if (val > 0) cantidad.textContent = val - 1;
      });
    });
  </script>

</body>
</html>
