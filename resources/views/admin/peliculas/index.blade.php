@extends('admin.layout')

@section('titulo', 'Películas')
@section('encabezado', 'Gestión de Películas')

@section('admin-content')

<div style="margin-bottom:20px;">
  <a href="{{ route('admin.peliculas.create') }}" class="btn-agregar" style="text-decoration:none;">
    + Nueva película
  </a>
</div>

<div class="card-lista">
  <h2>Catálogo de películas ({{ $peliculas->count() }})</h2>
  <div class="tabla-wrapper">
    <table>
      <thead>
        <tr>
          <th>Imagen</th>
          <th>Título</th>
          <th>Género</th>
          <th>Duración</th>
          <th>Calificación</th>
          <th style="text-align:right;">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($peliculas as $p)
          <tr>
            <td>
              @if($p->imagen)
                <img src="{{ asset($p->imagen) }}" alt="{{ $p->titulo }}" class="tabla-img">
              @else
                <div class="sin-imagen">sin<br>imagen</div>
              @endif
            </td>
            <td><strong>{{ $p->titulo }}</strong></td>
            <td>{{ $p->genero ?? '—' }}</td>
            <td>{{ $p->duracion ?? '—' }}</td>
            <td>
              @for($i = 1; $i <= 5; $i++)
                <span class="estrella {{ $i <= (int) $p->calificacion ? 'activa' : '' }}">★</span>
              @endfor
            </td>
            <td style="text-align:right;white-space:nowrap;">
              <a href="{{ route('admin.peliculas.edit', $p->id) }}" class="cb-btn cb-btn-sm cb-btn-ghost">Editar</a>
              <form action="{{ route('admin.peliculas.destroy', $p->id) }}" method="POST"
                    style="display:inline;"
                    onsubmit="return confirm('¿Eliminar la película «{{ $p->titulo }}»? Esta acción no se puede deshacer.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-eliminar">Eliminar</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="sin-datos">No hay películas registradas. ¡Crea la primera!</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection
