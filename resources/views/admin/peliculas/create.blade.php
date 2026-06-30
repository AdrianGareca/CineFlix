@extends('admin.layout')

@section('titulo', 'Nueva película')
@section('encabezado', 'Nueva Película')

@section('admin-content')

<div class="card-form">
  <h2>Datos de la película</h2>

  <form action="{{ route('admin.peliculas.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="form-grid">
      <div class="form-grupo full-width">
        <label for="titulo">Título *</label>
        <input type="text" id="titulo" name="titulo" value="{{ old('titulo') }}" required>
      </div>

      <div class="form-grupo">
        <label for="genero">Género</label>
        <input type="text" id="genero" name="genero" value="{{ old('genero') }}" placeholder="Acción, Drama…">
      </div>

      <div class="form-grupo">
        <label for="duracion">Duración</label>
        <input type="text" id="duracion" name="duracion" value="{{ old('duracion') }}" placeholder="120 min">
      </div>

      <div class="form-grupo">
        <label for="calificacion">Calificación (0–5)</label>
        <input type="number" id="calificacion" name="calificacion" min="0" max="5" value="{{ old('calificacion', 0) }}">
      </div>

      <div class="form-grupo">
        <label for="imagen">Imagen (póster) *</label>
        <input type="file" id="imagen" name="imagen" accept="image/*" required>
      </div>

      <div class="form-grupo full-width">
        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion" rows="4">{{ old('descripcion') }}</textarea>
      </div>
    </div>

    <button type="submit" class="btn-agregar">Guardar película</button>
    <a href="{{ route('admin.peliculas.index') }}" class="cb-btn cb-btn-ghost" style="margin-left:8px;">Cancelar</a>
  </form>
</div>

@endsection
