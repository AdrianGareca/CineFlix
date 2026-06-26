<?php
session_start();

// Proteger la página: solo admins
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require 'conexion.php';

$seccion = isset($_GET['seccion']) ? $_GET['seccion'] : 'peliculas';
$mensaje = '';
$tipo_mensaje = '';

// ============================================================
// ACCIONES DE PELÍCULAS
// ============================================================
if ($seccion === 'peliculas') {

    // AGREGAR PELÍCULA
    if (isset($_POST['agregar_pelicula'])) {
        $titulo       = trim($_POST['titulo']);
        $descripcion  = trim($_POST['descripcion']);
        $duracion     = trim($_POST['duracion']);
        $genero       = trim($_POST['genero']);
        $calificacion = intval($_POST['calificacion']);
        $ruta_imagen  = '';

        // Subir imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
            $ext_permitidas = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $ext_permitidas)) {
                $nombre_archivo = 'pelicula_' . time() . '.' . $ext;
                $destino = 'uploads/' . $nombre_archivo;
                move_uploaded_file($_FILES['imagen']['tmp_name'], $destino);
                $ruta_imagen = $destino;
            }
        }

        $stmt = $conn->prepare("INSERT INTO peliculas (titulo, descripcion, duracion, genero, calificacion, imagen) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssis", $titulo, $descripcion, $duracion, $genero, $calificacion, $ruta_imagen);
        if ($stmt->execute()) {
            $mensaje = '¡Película agregada exitosamente!';
            $tipo_mensaje = 'exito';
        } else {
            $mensaje = 'Error al agregar la película.';
            $tipo_mensaje = 'error';
        }
        $stmt->close();
    }

    // ELIMINAR PELÍCULA
    if (isset($_GET['eliminar_pelicula'])) {
        $id = intval($_GET['eliminar_pelicula']);
        $stmt = $conn->prepare("DELETE FROM peliculas WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $mensaje = 'Película eliminada.';
            $tipo_mensaje = 'exito';
        } else {
            $mensaje = 'Error al eliminar.';
            $tipo_mensaje = 'error';
        }
        $stmt->close();
    }

    // Obtener todas las películas
    $peliculas = $conn->query("SELECT * FROM peliculas ORDER BY creado_en DESC");
}

// ============================================================
// ACCIONES DE CONFITERÍA
// ============================================================
if ($seccion === 'confiteria') {

    // AGREGAR PRODUCTO
    if (isset($_POST['agregar_producto'])) {
        $titulo      = trim($_POST['titulo']);
        $descripcion = trim($_POST['descripcion']);
        $precio      = floatval($_POST['precio']);
        $ruta_imagen = '';

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
            $ext_permitidas = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $ext_permitidas)) {
                $nombre_archivo = 'producto_' . time() . '.' . $ext;
                $destino = 'uploads/' . $nombre_archivo;
                move_uploaded_file($_FILES['imagen']['tmp_name'], $destino);
                $ruta_imagen = $destino;
            }
        }

        $stmt = $conn->prepare("INSERT INTO confiteria (titulo, descripcion, precio, imagen) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $titulo, $descripcion, $precio, $ruta_imagen);
        if ($stmt->execute()) {
            $mensaje = '¡Producto agregado exitosamente!';
            $tipo_mensaje = 'exito';
        } else {
            $mensaje = 'Error al agregar el producto.';
            $tipo_mensaje = 'error';
        }
        $stmt->close();
    }

    // ELIMINAR PRODUCTO
    if (isset($_GET['eliminar_producto'])) {
        $id = intval($_GET['eliminar_producto']);
        $stmt = $conn->prepare("DELETE FROM confiteria WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $mensaje = 'Producto eliminado.';
            $tipo_mensaje = 'exito';
        } else {
            $mensaje = 'Error al eliminar.';
            $tipo_mensaje = 'error';
        }
        $stmt->close();
    }

    // Obtener todos los productos
    $productos = $conn->query("SELECT * FROM confiteria ORDER BY creado_en DESC");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin — CineFlix</title>
    <link rel="stylesheet" href="css/design-system.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<div class="admin-wrapper">

    <!-- ===== SIDEBAR ===== -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <svg width="40" height="40">
                <rect x="2" y="14" width="36" height="23"
                    style="fill:#1c1c1c; stroke:rgb(158,7,7); stroke-width:3"/>
                <line x1="3" y1="13" x2="36" y2="3"
                    style="stroke:rgb(175,12,12); stroke-width:3"/>
                <line x1="13" y1="7" x2="11" y2="14"
                    style="stroke:#fff; stroke-width:1.5"/>
                <line x1="20" y1="5" x2="18" y2="12"
                    style="stroke:#fff; stroke-width:1.5"/>
                <line x1="28" y1="3" x2="26" y2="10"
                    style="stroke:#fff; stroke-width:1.5"/>
            </svg>
            <span>CineFlix</span>
        </div>

        <p class="sidebar-bienvenida">Hola, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>

        <nav class="sidebar-nav">
            <a href="admin.php?seccion=peliculas"
               class="nav-item <?php echo $seccion === 'peliculas' ? 'activo' : ''; ?>">
                <svg viewBox="0 0 24 24" width="20" height="20">
                    <path d="M3 6 H15 V18 H3 Z M15 10 L21 6 V18 L15 14 Z" fill="currentColor"/>
                </svg>
                Películas
            </a>
            <a href="admin.php?seccion=confiteria"
               class="nav-item <?php echo $seccion === 'confiteria' ? 'activo' : ''; ?>">
                <svg viewBox="0 0 24 24" width="20" height="20">
                    <path d="M7 4 H17 L20 20 H4 Z" fill="currentColor"/>
                </svg>
                Confitería
            </a>
        </nav>

        <a href="logout.php" class="btn-cerrar">
            <svg viewBox="0 0 24 24" width="18" height="18">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"
                    stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
            </svg>
            Cerrar Sesión
        </a>
    </aside>

    <!-- ===== CONTENIDO PRINCIPAL ===== -->
    <main class="contenido-admin">

        <?php if ($mensaje): ?>
            <div class="alerta <?php echo $tipo_mensaje; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <!-- ============ SECCIÓN PELÍCULAS ============ -->
        <?php if ($seccion === 'peliculas'): ?>

        <div class="seccion-header">
            <h1>Gestión de Películas</h1>
        </div>

        <!-- Formulario agregar película -->
        <div class="card-form">
            <h2>Agregar Nueva Película</h2>
            <form method="POST" action="admin.php?seccion=peliculas" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="form-grupo">
                        <label>Título</label>
                        <input type="text" name="titulo" placeholder="Ej: Avengers" required>
                    </div>
                    <div class="form-grupo">
                        <label>Duración</label>
                        <input type="text" name="duracion" placeholder="Ej: 2h 30min" required>
                    </div>
                    <div class="form-grupo">
                        <label>Género</label>
                        <input type="text" name="genero" placeholder="Ej: Acción / Aventura" required>
                    </div>
                    <div class="form-grupo">
                        <label>Calificación (1 a 5 estrellas)</label>
                        <select name="calificacion">
                            <option value="1">⭐ 1 estrella</option>
                            <option value="2">⭐⭐ 2 estrellas</option>
                            <option value="3" selected>⭐⭐⭐ 3 estrellas</option>
                            <option value="4">⭐⭐⭐⭐ 4 estrellas</option>
                            <option value="5">⭐⭐⭐⭐⭐ 5 estrellas</option>
                        </select>
                    </div>
                    <div class="form-grupo full-width">
                        <label>Descripción</label>
                        <textarea name="descripcion" rows="3" placeholder="Sinopsis de la película..." required></textarea>
                    </div>
                    <div class="form-grupo full-width">
                        <label>Imagen de la película</label>
                        <input type="file" name="imagen" accept="image/*">
                    </div>
                </div>
                <button type="submit" name="agregar_pelicula" class="btn-agregar">
                    + Agregar Película
                </button>
            </form>
        </div>

        <!-- Lista de películas -->
        <div class="card-lista">
            <h2>Películas en Cartelera</h2>
            <div class="tabla-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Título</th>
                            <th>Género</th>
                            <th>Duración</th>
                            <th>Calificación</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($peliculas && $peliculas->num_rows > 0): ?>
                            <?php while ($p = $peliculas->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <?php if ($p['imagen']): ?>
                                        <img src="<?php echo htmlspecialchars($p['imagen']); ?>"
                                             alt="<?php echo htmlspecialchars($p['titulo']); ?>"
                                             class="tabla-img">
                                    <?php else: ?>
                                        <div class="sin-imagen">Sin imagen</div>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?php echo htmlspecialchars($p['titulo']); ?></strong></td>
                                <td><?php echo htmlspecialchars($p['genero']); ?></td>
                                <td><?php echo htmlspecialchars($p['duracion']); ?></td>
                                <td>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <span class="estrella <?php echo $i <= $p['calificacion'] ? 'activa' : ''; ?>">★</span>
                                    <?php endfor; ?>
                                </td>
                                <td>
                                    <a href="admin.php?seccion=peliculas&eliminar_pelicula=<?php echo $p['id']; ?>"
                                       class="btn-eliminar"
                                       onclick="return confirm('¿Eliminar la película <?php echo htmlspecialchars($p['titulo']); ?>?')">
                                        Eliminar
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="sin-datos">No hay películas registradas.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php endif; ?>

        <!-- ============ SECCIÓN CONFITERÍA ============ -->
        <?php if ($seccion === 'confiteria'): ?>

        <div class="seccion-header">
            <h1>Gestión de Confitería</h1>
        </div>

        <!-- Formulario agregar producto -->
        <div class="card-form">
            <h2>Agregar Nuevo Producto</h2>
            <form method="POST" action="admin.php?seccion=confiteria" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="form-grupo">
                        <label>Título del producto</label>
                        <input type="text" name="titulo" placeholder="Ej: Palomitas grandes" required>
                    </div>
                    <div class="form-grupo">
                        <label>Precio (Bs.)</label>
                        <input type="number" name="precio" placeholder="Ej: 25.00" step="0.01" min="0" required>
                    </div>
                    <div class="form-grupo full-width">
                        <label>Descripción</label>
                        <textarea name="descripcion" rows="2" placeholder="Descripción del producto..."></textarea>
                    </div>
                    <div class="form-grupo full-width">
                        <label>Imagen del producto</label>
                        <input type="file" name="imagen" accept="image/*">
                    </div>
                </div>
                <button type="submit" name="agregar_producto" class="btn-agregar">
                    + Agregar Producto
                </button>
            </form>
        </div>

        <!-- Lista de productos -->
        <div class="card-lista">
            <h2>Productos en Confitería</h2>
            <div class="tabla-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Producto</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($productos && $productos->num_rows > 0): ?>
                            <?php while ($prod = $productos->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <?php if ($prod['imagen']): ?>
                                        <img src="<?php echo htmlspecialchars($prod['imagen']); ?>"
                                             alt="<?php echo htmlspecialchars($prod['titulo']); ?>"
                                             class="tabla-img">
                                    <?php else: ?>
                                        <div class="sin-imagen">Sin imagen</div>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?php echo htmlspecialchars($prod['titulo']); ?></strong></td>
                                <td><?php echo htmlspecialchars($prod['descripcion']); ?></td>
                                <td class="precio-col">Bs. <?php echo number_format($prod['precio'], 2); ?></td>
                                <td>
                                    <a href="admin.php?seccion=confiteria&eliminar_producto=<?php echo $prod['id']; ?>"
                                       class="btn-eliminar"
                                       onclick="return confirm('¿Eliminar el producto <?php echo htmlspecialchars($prod['titulo']); ?>?')">
                                        Eliminar
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="sin-datos">No hay productos registrados.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php endif; ?>

    </main>
</div>

</body>
</html>
