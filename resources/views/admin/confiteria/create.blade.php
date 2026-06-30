@extends('admin.layout')

@section('titulo', 'Nuevo producto')
@section('encabezado', 'Nuevo Producto de Confitería')

@section('admin-content')

<div class="card-form">
  <h2>Datos del producto</h2>

  <form action="{{ route('admin.confiteria.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="form-grid">
      <div class="form-grupo">
        <label for="titulo">Nombre *</label>
        <input type="text" id="titulo" name="titulo" value="{{ old('titulo') }}" required>
      </div>

      <div class="form-grupo">
        <label for="precio">Precio (Bs.) *</label>
        <input type="number" id="precio" name="precio" step="0.01" min="0" value="{{ old('precio') }}" required>
      </div>

      <div class="form-grupo">
        <label for="imagen">Imagen *</label>
        <input type="file" id="imagen" name="imagen" accept="image/*" required>
      </div>

      <div class="form-grupo full-width">
        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
      </div>
    </div>

    <button type="submit" class="btn-agregar">Guardar producto</button>
    <a href="{{ route('admin.confiteria.index') }}" class="cb-btn cb-btn-ghost" style="margin-left:8px;">Cancelar</a>
  </form>
</div>

@endsection
