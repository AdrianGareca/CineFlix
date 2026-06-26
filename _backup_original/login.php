<?php
session_start();
require 'conexion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_input   = trim($_POST['usuario']);
    $contrasena_input = $_POST['contrasena'];

    if (empty($usuario_input) || empty($contrasena_input)) {
        $error = 'Por favor completa todos los campos.';
    } else {
        $stmt = $conn->prepare("SELECT id, nombre, usuario, contrasena, rol FROM usuarios WHERE usuario = ?");
        $stmt->bind_param("s", $usuario_input);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $fila = $resultado->fetch_assoc();

            if (password_verify($contrasena_input, $fila['contrasena'])) {
                $_SESSION['usuario_id']  = $fila['id'];
                $_SESSION['usuario_nombre'] = $fila['nombre'];
                $_SESSION['usuario_rol']    = $fila['rol'];

                // Redirigir según rol
                if ($fila['rol'] === 'admin') {
                    header("Location: admin.php");
                } else {
                    header("Location: cartelera.php");
                }
                exit();
            } else {
                $error = 'Usuario o contraseña incorrectos.';
            }
        } else {
            $error = 'Usuario o contraseña incorrectos.';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar Sesión — CineFlix</title>
  <link rel="stylesheet" href="css/design-system.css">
  <link rel="stylesheet" href="css/StyleLogin.css">
</head>
<body>

  <div class="login-logo">
    <svg width="40" height="40" viewBox="0 0 64 64">
      <rect x="4" y="20" width="56" height="36" rx="3" fill="none" stroke="#C4405A" stroke-width="3.5"/>
      <line x1="5" y1="19" x2="57" y2="4" stroke="#C4405A" stroke-width="3"/>
      <line x1="20" y1="10" x2="17" y2="20" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
      <line x1="32" y1="6"  x2="29" y2="16" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
      <line x1="44" y1="2"  x2="41" y2="12" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
    </svg>
    <span class="login-logo-text">CineFlix</span>
  </div>

  <div class="login-card">
    <h2>Iniciar Sesión</h2>

    <?php if ($error): ?>
      <div class="error-msg" style="background:rgba(196,64,90,0.12);border:1px solid rgba(196,64,90,0.3);color:#e57a8a;padding:10px 14px;border-radius:var(--r);margin-bottom:18px;font-size:0.88rem;">
        <?php echo htmlspecialchars($error); ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="login.php">
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
    <p class="login-alt">¿No tienes una cuenta? <a href="registrarse.php">Registrarse</a></p>
  </div>

</body>
</html>
