<?php
/**
 * AdminController — Panel de administración (películas y confitería).
 * Protegido: solo usuarios con rol 'admin'.
 */
class AdminController extends Controller
{
    public function index(): void
    {
        requerirAdmin();

        $seccion = $_GET['seccion'] ?? 'peliculas';
        $mensaje = '';
        $tipo    = '';

        if ($seccion === 'confiteria') {
            [$mensaje, $tipo, $editando] = $this->gestionarConfiteria();
            $modelo    = $this->modelo('Confiteria');
            $productos = $modelo->todas('DESC');

            $this->vista('admin/confiteria', [
                'seccion'   => $seccion,
                'mensaje'   => $mensaje,
                'tipo'      => $tipo,
                'productos' => $productos,
                'editando'  => $editando,
            ]);
        } else {
            $seccion = 'peliculas';
            [$mensaje, $tipo, $editando] = $this->gestionarPeliculas();
            $modelo    = $this->modelo('Pelicula');
            $peliculas = $modelo->todas();

            $this->vista('admin/peliculas', [
                'seccion'   => $seccion,
                'mensaje'   => $mensaje,
                'tipo'      => $tipo,
                'peliculas' => $peliculas,
                'editando'  => $editando,
            ]);
        }
    }

    // ─── Lógica de la sección Películas ───────────────────────
    private function gestionarPeliculas(): array
    {
        $modelo   = $this->modelo('Pelicula');
        $editando = null;

        if (isset($_POST['agregar_pelicula'])) {
            $titulo       = trim($_POST['titulo'] ?? '');
            $descripcion  = trim($_POST['descripcion'] ?? '');
            $duracion     = trim($_POST['duracion'] ?? '');
            $genero       = trim($_POST['genero'] ?? '');
            $calificacion = (int) ($_POST['calificacion'] ?? 3);
            $imagen       = $this->subirImagen('pelicula');

            if ($modelo->crear($titulo, $descripcion, $duracion, $genero, $calificacion, $imagen)) {
                return ['¡Película agregada exitosamente!', 'exito', null];
            }
            return ['Error al agregar la película.', 'error', null];
        }

        if (isset($_POST['actualizar_pelicula'])) {
            $id           = (int) ($_POST['id'] ?? 0);
            $titulo       = trim($_POST['titulo'] ?? '');
            $descripcion  = trim($_POST['descripcion'] ?? '');
            $duracion     = trim($_POST['duracion'] ?? '');
            $genero       = trim($_POST['genero'] ?? '');
            $calificacion = (int) ($_POST['calificacion'] ?? 3);
            $imagen       = $this->subirImagen('pelicula');

            if ($modelo->actualizar($id, $titulo, $descripcion, $duracion, $genero, $calificacion, $imagen)) {
                return ['¡Película actualizada exitosamente!', 'exito', null];
            }
            return ['Error al actualizar la película.', 'error', null];
        }

        if (isset($_GET['eliminar_pelicula'])) {
            $id = (int) $_GET['eliminar_pelicula'];
            if ($modelo->eliminar($id)) {
                return ['Película eliminada.', 'exito', null];
            }
            return ['Error al eliminar.', 'error', null];
        }

        if (isset($_GET['editar_pelicula'])) {
            $id       = (int) $_GET['editar_pelicula'];
            $editando = $modelo->porId($id);
        }

        return ['', '', $editando];
    }

    // ─── Lógica de la sección Confitería ──────────────────────
    private function gestionarConfiteria(): array
    {
        $modelo   = $this->modelo('Confiteria');
        $editando = null;

        if (isset($_POST['agregar_producto'])) {
            $titulo      = trim($_POST['titulo'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $precio      = (float) ($_POST['precio'] ?? 0);
            $imagen      = $this->subirImagen('producto');

            if ($modelo->crear($titulo, $descripcion, $precio, $imagen)) {
                return ['¡Producto agregado exitosamente!', 'exito', null];
            }
            return ['Error al agregar el producto.', 'error', null];
        }

        if (isset($_POST['actualizar_producto'])) {
            $id          = (int) ($_POST['id'] ?? 0);
            $titulo      = trim($_POST['titulo'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $precio      = (float) ($_POST['precio'] ?? 0);
            $imagen      = $this->subirImagen('producto');

            if ($modelo->actualizar($id, $titulo, $descripcion, $precio, $imagen)) {
                return ['¡Producto actualizado exitosamente!', 'exito', null];
            }
            return ['Error al actualizar el producto.', 'error', null];
        }

        if (isset($_GET['eliminar_producto'])) {
            $id = (int) $_GET['eliminar_producto'];
            if ($modelo->eliminar($id)) {
                return ['Producto eliminado.', 'exito', null];
            }
            return ['Error al eliminar.', 'error', null];
        }

        if (isset($_GET['editar_producto'])) {
            $id       = (int) $_GET['editar_producto'];
            $editando = $modelo->porId($id);
        }

        return ['', '', $editando];
    }

    /**
     * Procesa la subida de una imagen y devuelve la ruta relativa
     * guardada en BD (ej: "uploads/pelicula_123.jpg") o '' si no hubo archivo.
     */
    private function subirImagen(string $prefijo): string
    {
        if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
            return '';
        }

        $permitidas = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $permitidas, true)) {
            return '';
        }

        if (!is_dir(UPLOAD_DIR)) {
            mkdir(UPLOAD_DIR, 0777, true);
        }

        $nombre  = $prefijo . '_' . time() . '.' . $ext;
        $destino = UPLOAD_DIR . '/' . $nombre;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
            return 'uploads/' . $nombre;
        }

        return '';
    }
}
