@extends('layouts.app')

@section('title', 'Confitería')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/golosina.css') }}">
@endpush

{{-- Suppress default footer --}}
@section('footer')@endsection

@section('content')

<div class="cb-back-bar">
  @if(session('booking.pelicula_id'))
    <a href="{{ route('asientos.index', ['pelicula' => session('booking.pelicula_id')]) }}"
       class="cb-back">&#8592; Volver a butacas</a>
  @else
    <a href="{{ route('cartelera') }}" class="cb-back">&#8592; Cartelera</a>
  @endif
  <span class="cb-timer" id="cronometro">06:00</span>
</div>

<div class="golosinas-header">
  <h1>Confitería</h1>
  <p>Precios exclusivos para compras en línea</p>
</div>

<form method="POST" action="{{ route('golosinas.guardar') }}" id="formGolosinas">
  @csrf

  <div class="golosinas-grid">
    @forelse($productos as $prod)
      {{-- Hidden input carries the quantity for this product --}}
      <input type="hidden" name="cantidades[{{ $prod->id }}]"
             id="cant_{{ $prod->id }}" value="0">

      <div class="tarjeta-pelicula">
        <img src="{{ asset($prod->imagen) }}" alt="{{ $prod->titulo }}">
        <div class="info">
          <h3>{{ $prod->titulo }}</h3>
          @if($prod->descripcion)
            <p>{{ $prod->descripcion }}</p>
          @endif
          <p class="precio">Bs. {{ number_format((float) $prod->precio, 0) }}</p>
          <div class="contador">
            <button type="button" class="btn-menos"
                    data-id="{{ $prod->id }}" aria-label="Reducir">
              <svg viewBox="0 0 20 20" width="14" height="14">
                <rect x="2" y="9" width="16" height="2"/>
              </svg>
            </button>
            <span class="cantidad" id="lbl_{{ $prod->id }}">0</span>
            <button type="button" class="btn-mas"
                    data-id="{{ $prod->id }}" aria-label="Aumentar">
              <svg viewBox="0 0 20 20" width="14" height="14">
                <rect x="9" y="2" width="2" height="16"/>
                <rect x="2" y="9" width="16" height="2"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
    @empty
      <p style="color:var(--text-2);padding:40px 0;grid-column:1/-1;text-align:center;">
        No hay productos disponibles.
      </p>
    @endforelse
  </div>

  <div class="boton-comprar-container">
    <button type="submit" class="boton-comprar">
      Ir al pago &rarr;
    </button>
  </div>
</form>

@endsection

@push('scripts')
<script>
  document.querySelectorAll('.contador').forEach(function (contador) {
    var id    = contador.querySelector('.btn-mas').dataset.id;
    var lbl   = document.getElementById('lbl_' + id);
    var input = document.getElementById('cant_' + id);

    contador.querySelector('.btn-mas').addEventListener('click', function () {
      var v = parseInt(lbl.textContent) + 1;
      lbl.textContent = v;
      input.value     = v;
    });

    contador.querySelector('.btn-menos').addEventListener('click', function () {
      var v = parseInt(lbl.textContent);
      if (v > 0) {
        lbl.textContent = v - 1;
        input.value     = v - 1;
      }
    });
  });

  // Countdown timer — 6 minutes
  (function () {
    var seg = 360;
    var el  = document.getElementById('cronometro');
    var t   = setInterval(function () {
      if (--seg <= 0) { clearInterval(t); el.textContent = '00:00'; return; }
      var m = Math.floor(seg / 60).toString().padStart(2, '0');
      var s = (seg % 60).toString().padStart(2, '0');
      el.textContent = m + ':' + s;
    }, 1000);
  })();
</script>
@endpush
