<?php
/** Vista admin: Gestión de confitería. Variables: $seccion, $mensaje, $tipo, $productos, $editando */
$this->parcial('admin_header', ['seccion' => $seccion, 'mensaje' => $mensaje, 'tipo' => $tipo]);
?>

<div class="seccion-header">
  <h1>Gestión de Confitería</h1>
</div>

<!-- ── Formulario EDITAR (solo visible cuando se hace clic en Editar) ── -->
<?php if (!empty($editando)): ?>
<div class="card-form" style="border:2px solid #C4405A;">
  <h2 style="color:#C4405A;">✏️ Editar Producto</h2>
  <form method="POST" action="<?php echo url('admin', ['seccion' => 'confiteria']); ?>" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo (int) $editando['id']; ?>">
    <div class="form-grid">
      <div class="form-grupo">
        <label>Título del producto</label>
        <input type="text" name="titulo" value="<?php echo e($editando['titulo']); ?>" required>
      </div>
      <div class="form-grupo">
        <label>Precio (Bs.)</label>
        <input type="number" name="precio" value="<?php echo e($editando['precio']); ?>" step="0.01" min="0" required>
      </div>
      <div class="form-grupo full-width">
        <label>Descripción</label>
        <textarea name="descripcion" rows="2"><?php echo e($editando['descripcion']); ?></textarea>
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
      <button type="submit" name="actualizar_producto" class="btn-agregar">💾 Guardar Cambios</button>
      <a href="<?php echo url('admin', ['seccion' => 'confiteria']); ?>" class="btn-eliminar" style="padding:10px 20px;text-decoration:none;line-height:1.6;">✕ Cancelar</a>
    </div>
  </form>
</div>
<?php endif; ?>

<!-- ── Formulario AGREGAR producto ── -->
<div class="card-form">
  <h2>Agregar Nuevo Producto</h2>
  <form method="POST" action="<?php echo url('admin', ['seccion' => 'confiteria']); ?>" enctype="multipart/form-data">
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
    <button type="submit" name="agregar_producto" class="btn-agregar">+ Agregar Producto</button>
  </form>
</div>

<!-- ── Lista de productos ── -->
<div class="card-lista">
  <h2>Productos en Confitería</h2>
  <div class="tabla-wrapper">
    <table>
      <thead>
        <tr><th>Imagen</th><th>Producto</th><th>Descripción</th><th>Precio</th><th>Acciones</th></tr>
      </thead>
      <tbody>
        <?php if (!empty($productos)): ?>
          <?php foreach ($productos as $prod): ?>
          <tr>
            <td>
              <?php if ($prod['imagen']): ?>
                <img src="<?php echo asset(e($prod['imagen'])); ?>" alt="<?php echo e($prod['titulo']); ?>" class="tabla-img">
              <?php else: ?>
                <div class="sin-imagen">Sin imagen</div>
              <?php endif; ?>
            </td>
            <td><strong><?php echo e($prod['titulo']); ?></strong></td>
            <td><?php echo e($prod['descripcion']); ?></td>
            <td class="precio-col">Bs. <?php echo number_format((float)$prod['precio'], 2); ?></td>
            <td style="white-space:nowrap;">
              <a href="<?php echo url('admin', ['seccion' => 'confiteria', 'editar_producto' => (int)$prod['id']]); ?>"
                 class="btn-agregar" style="padding:6px 14px;font-size:0.82rem;margin-right:6px;text-decoration:none;">✏️ Editar</a>
              <a href="<?php echo url('admin', ['seccion' => 'confiteria', 'eliminar_producto' => (int)$prod['id']]); ?>"
                 class="btn-eliminar"
                 onclick="return confirm('¿Eliminar el producto <?php echo e($prod['titulo']); ?>?')">Eliminar</a>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="5" class="sin-datos">No hay productos registrados.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php $this->parcial('admin_footer'); ?>
