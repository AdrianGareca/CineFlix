@extends('admin.layout')

@section('titulo', 'Dashboard')
@section('encabezado', 'Dashboard')

@section('admin-content')

{{-- ── Tarjetas de estadísticas ─────────────────────────────── --}}
<div class="form-grid" style="margin-bottom:24px;">
  <div class="card-form" style="margin-bottom:0;">
    <h2 style="border:none;padding:0;margin:0 0 8px;">Películas</h2>
    <p style="font-family:var(--font-serif);font-size:2.4rem;color:var(--red);line-height:1;">
      {{ $totalPeliculas }}
    </p>
    <a href="{{ route('admin.peliculas.index') }}" class="cb-tag cb-tag-dark" style="margin-top:12px;display:inline-block;">Gestionar →</a>
  </div>

  <div class="card-form" style="margin-bottom:0;">
    <h2 style="border:none;padding:0;margin:0 0 8px;">Productos de Confitería</h2>
    <p style="font-family:var(--font-serif);font-size:2.4rem;color:#C9A84C;line-height:1;">
      {{ $totalConfiteria }}
    </p>
    <a href="{{ route('admin.confiteria.index') }}" class="cb-tag cb-tag-dark" style="margin-top:12px;display:inline-block;">Gestionar →</a>
  </div>
</div>

{{-- ── Actividad reciente: Películas ─────────────────────────── --}}
<div class="card-lista" style="margin-bottom:24px;">
  <h2>Últimas películas añadidas</h2>
  <div class="tabla-wrapper">
    <table>
      <thead>
        <tr><th>Imagen</th><th>Título</th><th>Género</th><th>Añadida</th></tr>
      </thead>
      <tbody>
        @forelse($ultimasPeliculas as $p)
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
            <td>{{ $p->creado_en?->format('d/m/Y') ?? '—' }}</td>
          </tr>
        @empty
          <tr><td colspan="4" class="sin-datos">Aún no hay películas registradas.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- ── Actividad reciente: Confitería ───────────────────────── --}}
<div class="card-lista">
  <h2>Últimos productos añadidos</h2>
  <div class="tabla-wrapper">
    <table>
      <thead>
        <tr><th>Imagen</th><th>Producto</th><th>Precio</th><th>Añadido</th></tr>
      </thead>
      <tbody>
        @forelse($ultimaConfiteria as $c)
          <tr>
            <td>
              @if($c->imagen)
                <img src="{{ asset($c->imagen) }}" alt="{{ $c->titulo }}" class="tabla-img">
              @else
                <div class="sin-imagen">sin<br>imagen</div>
              @endif
            </td>
            <td><strong>{{ $c->titulo }}</strong></td>
            <td class="precio-col">Bs. {{ number_format((float) $c->precio, 2) }}</td>
            <td>{{ $c->creado_en?->format('d/m/Y') ?? '—' }}</td>
          </tr>
        @empty
          <tr><td colspan="4" class="sin-datos">Aún no hay productos registrados.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection
