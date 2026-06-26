<?php
/**
 * ContactoController — Formulario de contacto público.
 * GET  → muestra el formulario vacío.
 * POST → valida, guarda en DB, muestra confirmación.
 */
class ContactoController extends Controller
{
    public function index(): void
    {
        $exito   = '';
        $errores = [];
        $form    = ['nombre' => '', 'correo' => '', 'mensaje' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre  = trim($_POST['nombre']  ?? '');
            $correo  = trim($_POST['correo']  ?? '');
            $mensaje = trim($_POST['mensaje'] ?? '');

            // Conservar valores para repoblar el formulario si hay error
            $form = ['nombre' => $nombre, 'correo' => $correo, 'mensaje' => $mensaje];

            // Validación server-side (segunda línea de defensa tras el JS)
            if ($nombre === '') {
                $errores[] = 'El nombre es obligatorio.';
            }
            if ($correo === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $errores[] = 'Ingresa un correo electrónico válido.';
            }
            if (strlen($mensaje) < 10) {
                $errores[] = 'El mensaje debe tener al menos 10 caracteres.';
            }

            if (empty($errores)) {
                $modelo = $this->modelo('Contacto');
                if ($modelo->guardar($nombre, $correo, $mensaje)) {
                    // Limpiar el formulario tras éxito
                    $exito = '¡Gracias por contactarnos, ' . e($nombre) . '! Te responderemos pronto.';
                    $form  = ['nombre' => '', 'correo' => '', 'mensaje' => ''];
                } else {
                    $errores[] = 'No se pudo enviar el mensaje. Inténtalo de nuevo.';
                }
            }
        }

        $this->vista('contacto/index', [
            'exito'   => $exito,
            'errores' => $errores,
            'form'    => $form,
        ]);
    }
}
