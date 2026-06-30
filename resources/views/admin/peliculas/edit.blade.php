@extends('admin.layout')

@section('titulo', 'Editar película')
@section('encabezado', 'Editar Película')

@section('admin-content')

<div class="card-form">
  <h2>Editando: {{ $pelicula->titulo }}</h2>

  <form action="{{ route('admin.peliculas.update', $pelicula->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="form-grid">
      <div class="form-grupo full-width">
        <label for="titulo">Título *</label>
        <input type="text" id="titulo" name="titulo" value="{{ old('titulo', $pelicula->titulo) }}" required>
      </div>

      <div class="form-grupo">
        <label for="genero">Género</label>
        <input type="text" id="genero" name="genero" value="{{ old('genero', $pelicula->genero) }}">
      </div>

      <div class="form-grupo">
        <label for="duracion">Duración</label>
        <input type="text" id="duracion" name="duracion" value="{{ old('duracion', $pelicula->duracion) }}">
      </div>

      <div class="form-grupo">
        <label for="calificacion">Calificación (0–5)</label>
        <input type="number" id="calificacion" name="calificacion" min="0" max="5"
               value="{{ old('calificacion', $pelicula->calificacion) }}">
      </div>

      <div class="form-grupo">
        <label for="imagen">Reemplazar imagen</label>
        <input type="file" id="imagen" name="imagen" accept="image/*">
        @if($pelicula->imagen)
          <img src="{{ asset($pelicula->imagen) }}" alt="actual" class="tabla-img" style="margin-top:8px;width:80px;height:80px;">
        @endif
      </div>

      <div class="form-grupo full-width">
        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion" rows="4">{{ old('descripcion', $pelicula->descripcion) }}</textarea>
      </div>
    </div>

    <button type="submit" class="btn-agregar">Guardar cambios</button>
    <a href="{{ route('admin.peliculas.index') }}" class="cb-btn cb-btn-ghost" style="margin-left:8px;">Cancelar</a>
  </form>
</div>

@endsection
