@extends('admin.layout')

@section('titulo', 'Editar producto')
@section('encabezado', 'Editar Producto de Confitería')

@section('admin-content')

<div class="card-form">
  <h2>Editando: {{ $producto->titulo }}</h2>

  <form action="{{ route('admin.confiteria.update', $producto->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="form-grid">
      <div class="form-grupo">
        <label for="titulo">Nombre *</label>
        <input type="text" id="titulo" name="titulo" value="{{ old('titulo', $producto->titulo) }}" required>
      </div>

      <div class="form-grupo">
        <label for="precio">Precio (Bs.) *</label>
        <input type="number" id="precio" name="precio" step="0.01" min="0"
               value="{{ old('precio', $producto->precio) }}" required>
      </div>

      <div class="form-grupo">
        <label for="imagen">Reemplazar imagen</label>
        <input type="file" id="imagen" name="imagen" accept="image/*">
        @if($producto->imagen)
          <img src="{{ asset($producto->imagen) }}" alt="actual" class="tabla-img" style="margin-top:8px;width:80px;height:80px;">
        @endif
      </div>

      <div class="form-grupo full-width">
        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $producto->descripcion) }}</textarea>
      </div>
    </div>

    <button type="submit" class="btn-agregar">Guardar cambios</button>
    <a href="{{ route('admin.confiteria.index') }}" class="cb-btn cb-btn-ghost" style="margin-left:8px;">Cancelar</a>
  </form>
</div>

@endsection
