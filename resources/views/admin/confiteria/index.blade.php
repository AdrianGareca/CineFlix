@extends('admin.layout')

@section('titulo', 'Confitería')
@section('encabezado', 'Gestión de Confitería')

@section('admin-content')

<div style="margin-bottom:20px;">
  <a href="{{ route('admin.confiteria.create') }}" class="btn-agregar" style="text-decoration:none;">
    + Nuevo producto
  </a>
</div>

<div class="card-lista">
  <h2>Productos de confitería ({{ $productos->count() }})</h2>
  <div class="tabla-wrapper">
    <table>
      <thead>
        <tr>
          <th>Imagen</th>
          <th>Producto</th>
          <th>Descripción</th>
          <th>Precio</th>
          <th style="text-align:right;">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($productos as $c)
          <tr>
            <td>
              @if($c->imagen)
                <img src="{{ asset($c->imagen) }}" alt="{{ $c->titulo }}" class="tabla-img">
              @else
                <div class="sin-imagen">sin<br>imagen</div>
              @endif
            </td>
            <td><strong>{{ $c->titulo }}</strong></td>
            <td>{{ \Illuminate\Support\Str::limit($c->descripcion, 60) ?: '—' }}</td>
            <td class="precio-col">Bs. {{ number_format((float) $c->precio, 2) }}</td>
            <td style="text-align:right;white-space:nowrap;">
              <a href="{{ route('admin.confiteria.edit', $c->id) }}" class="cb-btn cb-btn-sm cb-btn-ghost">Editar</a>
              <form action="{{ route('admin.confiteria.destroy', $c->id) }}" method="POST"
                    style="display:inline;"
                    onsubmit="return confirm('¿Eliminar el producto «{{ $c->titulo }}»?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-eliminar">Eliminar</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="sin-datos">No hay productos registrados. ¡Crea el primero!</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection
