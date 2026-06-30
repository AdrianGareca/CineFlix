@extends('layouts.app')

@section('title', $pelicula->titulo)

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/estilo.css') }}">
@endpush

@section('content')

<div class="cb-back-bar">
  <a href="{{ route('cartelera') }}" class="cb-back">&#8592; Cartelera</a>
</div>

<div style="max-width:860px;margin:0 auto;padding:40px 24px;">
  <div style="display:grid;grid-template-columns:260px 1fr;gap:40px;align-items:start;">

    {{-- Poster --}}
    <img src="{{ asset($pelicula->imagen) }}"
         alt="{{ $pelicula->titulo }}"
         style="width:100%;border-radius:var(--r-lg);border:1px solid var(--border);">

    {{-- Info --}}
    <div>
      <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px;">
        <span class="cb-tag cb-tag-dark">{{ $pelicula->genero }}</span>
        <span class="cb-tag cb-tag-red">
          {{ str_repeat('★', min((int)$pelicula->calificacion, 5)) }}
          {{ str_repeat('☆', max(0, 5 - (int)$pelicula->calificacion)) }}
        </span>
      </div>

      <h1 style="font-family:var(--font-serif);font-size:2.2rem;font-weight:400;
                 letter-spacing:-0.02em;color:var(--text-1);margin-bottom:8px;line-height:1.2;">
        {{ $pelicula->titulo }}
      </h1>
      <p style="font-size:0.85rem;color:var(--text-2);margin-bottom:24px;">
        {{ $pelicula->duracion }}
      </p>
      <p style="font-size:0.95rem;color:var(--text-2);line-height:1.8;margin-bottom:36px;
                max-width:520px;">
        {{ $pelicula->descripcion }}
      </p>

      <div style="display:flex;flex-direction:column;gap:10px;max-width:300px;">
        <a href="{{ route('asientos.index', ['pelicula' => $pelicula->id]) }}"
           class="cb-btn cb-btn-red cb-btn-lg">
          Comprar entrada &rarr;
        </a>
        <a href="{{ route('cartelera') }}" class="cb-btn cb-btn-ghost">
          Ver todas las películas
        </a>
      </div>
    </div>
  </div>
</div>

@endsection
