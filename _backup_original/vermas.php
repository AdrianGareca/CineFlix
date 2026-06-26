<?php
session_start();
require 'conexion.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $conn->prepare("SELECT * FROM peliculas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$p = $resultado->fetch_assoc();

if (!$p) {
    header("Location: cartelera.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($p['titulo']); ?> — CineFlix</title>
  <link rel="stylesheet" href="css/design-system.css">
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

  <!-- Barra de volver -->
  <div class="cb-back-bar">
    <a href="cartelera.php" class="cb-back">&#8592; Cartelera</a>
  </div>

  <div class="contenedor">

    <!-- Poster -->
    <div class="contenedor-izquierda">
      <img src="<?php echo htmlspecialchars($p['imagen']); ?>" alt="<?php echo htmlspecialchars($p['titulo']); ?>">
    </div>

    <!-- Info -->
    <div class="contenedor-derecha">
      <h2><?php echo htmlspecialchars($p['titulo']); ?></h2>
      <p class="resumen"><?php echo htmlspecialchars($p['descripcion']); ?></p>

      <div class="datos">
        <p><strong>Duración</strong> <?php echo htmlspecialchars($p['duracion']); ?></p>
        <p><strong>Género</strong> <?php echo htmlspecialchars($p['genero']); ?></p>

        <div class="calificacion">
          <strong>Calificación</strong>
          <div class="stars">
            <?php for ($i = 1; $i <= 5; $i++): ?>
              <div class="star <?php echo $i <= $p['calificacion'] ? '' : 'empty'; ?>"></div>
            <?php endfor; ?>
          </div>
        </div>
      </div>

      <div class="entradas">
        <a href="#" class="boton-volver">Ver trailer</a>
        <a href="HTML/ASIENTOS.html" class="boton-volver primary">Comprar entradas</a>
        <a href="cartelera.php" class="boton-volver">Volver</a>
      </div>
    </div>

  </div>
</body>
</html>
