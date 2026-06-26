<?php
/** Vista admin: Gestión de películas. Variables: $seccion, $mensaje, $tipo, $peliculas, $editando */
$this->parcial('admin_header', ['seccion' => $seccion, 'mensaje' => $mensaje, 'tipo' => $tipo]);
?>

<div class="seccion-header">
  <h1>Gestión de Películas</h1>
</div>

<!-- ── Formulario EDITAR (solo visible cuando se hace clic en Editar) ── -->
<?php if (!empty($editando)): ?>
<div class="card-form" style="border:2px solid #C4405A;">
  <h2 style="color:#C4405A;">✏️ Editar Película</h2>
  <form method="POST" action="<?php echo url('admin', ['seccion' => 'peliculas']); ?>" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo (int) $editando['id']; ?>">
    <div class="form-grid">
      <div class="form-grupo">
        <label>Título</label>
        <input type="text" name="titulo" value="<?php echo e($editando['titulo']); ?>" required>
      </div>
      <div class="form-grupo">
        <label>Duración</label>
        <input type="text" name="duracion" value="<?php echo e($editando['duracion']); ?>" required>
      </div>
      <div class="form-grupo">
        <label>Género</label>
        <input type="text" name="genero" value="<?php echo e($editando['genero']); ?>" required>
      </div>
      <div class="form-grupo">
        <label>Calificación (1 a 5 estrellas)</label>
        <select name="calificacion">
          <?php for ($i = 1; $i <= 5; $i++): ?>
            <option value="<?php echo $i; ?>" <?php echo $i === (int)$editando['calificacion'] ? 'selected' : ''; ?>>
              <?php echo str_repeat('⭐', $i) . ' ' . $i . ' estrella' . ($i > 1 ? 's' : ''); ?>
            </option>
          <?php endfor; ?>
        </select>
      </div>
      <div class="form-grupo full-width">
        <label>Descripción</label>
        <textarea name="descripcion" rows="3" required><?php echo e($editando['descripcion']); ?></textarea>
      </div>
      <div class="form-grupo full-width">
        <label>Nueva imagen <small style="color:var(--text-2)">(dejar vacío para conservar la actual)</small></label>
        <?php if ($editando['imagen']): ?>
          <img src="<?php echo asset(e($editando['imagen'])); ?>" alt="actual" style="height:60px;border-radius:6px;margin-bottom:8px;display:block;">
        <?php endif; ?>
        <input type="file" name="imagen" accept="image/*">
      </div>
    </div>
    <div style="display:flex;gap:12px;flex-wrap:wrap;">
      <button type="submit" name="actualizar_pelicula" class="btn-agregar">💾 Guardar Cambios</button>
      <a href="<?php echo url('admin', ['seccion' => 'peliculas']); ?>" class="btn-eliminar" style="padding:10px 20px;text-decoration:none;line-height:1.6;">✕ Cancelar</a>
    </div>
  </form>
</div>
<?php endif; ?>

<!-- ── Formulario AGREGAR película ── -->
<div class="card-form">
  <h2>Agregar Nueva Película</h2>
  <form method="POST" action="<?php echo url('admin', ['seccion' => 'peliculas']); ?>" enctype="multipart/form-data">
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
    <button type="submit" name="agregar_pelicula" class="btn-agregar">+ Agregar Película</button>
  </form>
</div>

<!-- ── Lista de películas ── -->
<div class="card-lista">
  <h2>Películas en Cartelera</h2>
  <div class="tabla-wrapper">
    <table>
      <thead>
        <tr>
          <th>Imagen</th><th>Título</th><th>Género</th><th>Duración</th><th>Calificación</th><th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($peliculas)): ?>
          <?php foreach ($peliculas as $p): ?>
          <tr>
            <td>
              <?php if ($p['imagen']): ?>
                <img src="<?php echo asset(e($p['imagen'])); ?>" alt="<?php echo e($p['titulo']); ?>" class="tabla-img">
              <?php else: ?>
                <div class="sin-imagen">Sin imagen</div>
              <?php endif; ?>
            </td>
            <td><strong><?php echo e($p['titulo']); ?></strong></td>
            <td><?php echo e($p['genero']); ?></td>
            <td><?php echo e($p['duracion']); ?></td>
            <td>
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <span class="estrella <?php echo $i <= $p['calificacion'] ? 'activa' : ''; ?>">★</span>
              <?php endfor; ?>
            </td>
            <td style="white-space:nowrap;">
              <a href="<?php echo url('admin', ['seccion' => 'peliculas', 'editar_pelicula' => (int)$p['id']]); ?>"
                 class="btn-agregar" style="padding:6px 14px;font-size:0.82rem;margin-right:6px;text-decoration:none;">✏️ Editar</a>
              <a href="<?php echo url('admin', ['seccion' => 'peliculas', 'eliminar_pelicula' => (int)$p['id']]); ?>"
                 class="btn-eliminar"
                 onclick="return confirm('¿Eliminar la película <?php echo e($p['titulo']); ?>?')">Eliminar</a>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="6" class="sin-datos">No hay películas registradas.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php $this->parcial('admin_footer'); ?>
