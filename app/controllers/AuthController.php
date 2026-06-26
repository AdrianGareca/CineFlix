<?php
/**
 * AuthController — Inicio de sesión, registro y cierre de sesión.
 */
class AuthController extends Controller
{
    /** Login: muestra el formulario y procesa el envío (POST). */
    public function login(): void
    {
        // Si ya está logueado, mandarlo a su lugar
        if (estaLogueado()) {
            redirigir(esAdmin() ? 'admin' : 'cartelera');
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario    = trim($_POST['usuario'] ?? '');
            $contrasena = $_POST['contrasena'] ?? '';

            if ($usuario === '' || $contrasena === '') {
                $error = 'Por favor completa todos los campos.';
            } else {
                $modelo = $this->modelo('Usuario');
                $fila   = $modelo->autenticar($usuario, $contrasena);

                if ($fila) {
                    $_SESSION['usuario_id']     = $fila['id'];
                    $_SESSION['usuario_nombre'] = $fila['nombre'];
                    $_SESSION['usuario_rol']    = $fila['rol'];
                    redirigir($fila['rol'] === 'admin' ? 'admin' : 'cartelera');
                } else {
                    $error = 'Usuario o contraseña incorrectos.';
                }
            }
        }

        $this->vista('auth/login', ['error' => $error]);
    }

    /** Registro de nuevos usuarios. */
    public function registro(): void
    {
        $error = '';
        $exito = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre     = trim($_POST['nombre'] ?? '');
            $correo     = trim($_POST['correo'] ?? '');
            $usuario    = trim($_POST['usuario'] ?? '');
            $contrasena = $_POST['contrasena'] ?? '';

            if ($nombre === '' || $correo === '' || $usuario === '' || $contrasena === '') {
                $error = 'Por favor completa todos los campos.';
            } elseif (strlen($contrasena) < 6) {
                $error = 'La contraseña debe tener al menos 6 caracteres.';
            } else {
                $modelo = $this->modelo('Usuario');
                if ($modelo->existe($usuario, $correo)) {
                    $error = 'El usuario o correo ya está registrado.';
                } elseif ($modelo->crear($nombre, $correo, $usuario, $contrasena)) {
                    $exito = 'Cuenta creada exitosamente. Ya puedes iniciar sesión.';
                } else {
                    $error = 'Error al crear la cuenta. Intenta de nuevo.';
                }
            }
        }

        $this->vista('auth/registro', ['error' => $error, 'exito' => $exito]);
    }

    /** Cierra la sesión. */
    public function logout(): void
    {
        session_destroy();
        redirigir('login');
    }
}
