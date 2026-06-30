@extends('layouts.app')

@section('title', 'Cartelera')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/estilo.css') }}">
@endpush

@section('content')

{{-- ── Hero Carousel ──────────────────────────────────────────── --}}
@if($carrusel->isNotEmpty())
<div class="carrusel-hero">
  <div class="carrusel-pista" id="carruselPista">
    @foreach($carrusel as $f)
    <div class="carrusel-slide">
      <img src="{{ asset($f->imagen) }}" alt="{{ $f->titulo }}" class="carrusel-bg">
      <div class="carrusel-contenido">
        <span class="carrusel-etiqueta">{{ $f->genero }}</span>
        <h2 class="carrusel-titulo">{{ $f->titulo }}</h2>
        <p class="carrusel-meta">{{ $f->duracion }}</p>
        <a href="{{ route('peliculas.show', $f->id) }}" class="carrusel-boton">Ver más</a>
      </div>
    </div>
    @endforeach
  </div>

  <button class="carrusel-prev" id="carruselPrev" aria-label="Anterior">&#8249;</button>
  <button class="carrusel-next" id="carruselNext" aria-label="Siguiente">&#8250;</button>

  <div class="carrusel-dots" id="carruselDots">
    @foreach($carrusel as $idx => $f)
      <button class="dot {{ $idx === 0 ? 'activo' : '' }}"
              data-index="{{ $idx }}"
              aria-label="Slide {{ $idx + 1 }}"></button>
    @endforeach
  </div>
</div>
@endif

{{-- ── Movie Grid ─────────────────────────────────────────────── --}}
<div class="cb-content">
  <h2 class="cb-section-title">En cartelera</h2>
  <p class="cb-section-subtitle">Santa Cruz &nbsp;·&nbsp; Esta semana</p>

  <div class="peliculas-grid">
    @forelse($peliculas as $p)
      <div class="tarjeta-pelicula">
        <img src="{{ asset($p->imagen) }}" alt="{{ $p->titulo }}">
        <div class="info">
          <span class="cb-tag cb-tag-dark">{{ $p->genero }}</span>
          <h3>{{ $p->titulo }}</h3>
          <p class="meta">{{ $p->duracion }}</p>
          <a href="{{ route('peliculas.show', $p->id) }}" class="boton-ver-mas">Ver más &rarr;</a>
        </div>
      </div>
    @empty
      <p class="sin-peliculas">No hay películas en cartelera por el momento.</p>
    @endforelse
  </div>
</div>

@endsection

@push('scripts')
  <script src="{{ asset('js/carrusel.js') }}"></script>
@endpush
