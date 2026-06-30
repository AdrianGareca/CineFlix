@extends('layouts.app')

@section('title', 'Contacto')

@section('content')

<div style="max-width:560px;margin:0 auto;padding:48px 24px;">

  <h1 style="font-family:var(--font-serif);font-size:2rem;font-weight:400;
             letter-spacing:-0.02em;color:var(--text-1);margin-bottom:8px;">
    Contáctanos
  </h1>
  <p style="font-size:0.9rem;color:var(--text-2);margin-bottom:36px;">
    ¿Tienes preguntas o comentarios? Escríbenos y te responderemos pronto.
  </p>

  @if(session('exito'))
    <div style="background:rgba(39,174,96,0.12);border:1px solid rgba(39,174,96,0.28);
                color:#6fd96f;padding:12px 16px;border-radius:var(--r);margin-bottom:24px;
                font-size:0.88rem;">
      {{ session('exito') }}
    </div>
  @endif

  @if($errors->any())
    <div style="background:rgba(196,64,90,0.12);border:1px solid rgba(196,64,90,0.28);
                color:#e57a8a;padding:12px 16px;border-radius:var(--r);margin-bottom:24px;
                font-size:0.88rem;">
      <ul style="margin:0;padding-left:16px;">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('contacto.enviar') }}">
    @csrf
    <div class="cb-field">
      <label class="cb-label" for="nombre">Nombre</label>
      <input class="cb-input" type="text" id="nombre" name="nombre"
             value="{{ old('nombre') }}" placeholder="Tu nombre completo" required>
    </div>
    <div class="cb-field">
      <label class="cb-label" for="correo">Correo electrónico</label>
      <input class="cb-input" type="email" id="correo" name="correo"
             value="{{ old('correo') }}" placeholder="tucorreo@ejemplo.com" required>
    </div>
    <div class="cb-field">
      <label class="cb-label" for="mensaje">Mensaje</label>
      <textarea class="cb-input" id="mensaje" name="mensaje" rows="5"
                placeholder="Escribe tu mensaje aquí..." required
                style="resize:vertical;">{{ old('mensaje') }}</textarea>
    </div>
    <button type="submit" class="cb-btn cb-btn-red" style="width:100%;padding:13px;">
      Enviar mensaje
    </button>
  </form>

</div>

@endsection
