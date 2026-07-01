@extends('layouts.app')

@section('title', 'Comprobante de Pago')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/factura.css') }}">
@endpush

@section('footer')@endsection

@section('content')

<div class="conteneidor" style="max-width:600px;">

  {{-- Encabezado del comprobante --}}
  <div style="text-align:center;margin-bottom:28px;padding-bottom:20px;border-bottom:1px solid var(--border);">
    <p class="factura-logo" style="margin-bottom:16px;text-align:left;">
      CineFlix &nbsp;·&nbsp; Comprobante de Pago
    </p>
    <div style="display:inline-flex;align-items:center;
                background:rgba(39,174,96,0.12);border:1px solid rgba(39,174,96,0.28);
                color:#6fd96f;border-radius:var(--r);padding:10px 24px;
                font-size:0.85rem;font-weight:600;gap:8px;margin-bottom:20px;">
      &#10003;&nbsp; Pago confirmado exitosamente
    </div>
    <div style="display:flex;justify-content:space-between;align-items:center;
                font-size:0.8rem;color:var(--text-2);font-family:var(--font-mono);">
      <span>
        N° Factura:&nbsp;
        <strong style="color:var(--text-1);letter-spacing:0.05em;">
          {{ $comprobante['numero'] }}
        </strong>
      </span>
      <span>{{ $comprobante['fecha'] }}&nbsp;&nbsp;{{ $comprobante['hora'] }}</span>
    </div>
  </div>

  {{-- Método de pago --}}
  <div style="margin-bottom:24px;">
    <p style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.1em;
              color:var(--text-2);margin-bottom:10px;">
      Método de pago
    </p>
    <span style="background:var(--surface-dim);border:1px solid var(--border);
                 border-radius:var(--r);padding:7px 16px;
                 font-family:var(--font-mono);font-size:0.83rem;color:var(--text-1);">
      {{ $comprobante['metodo_pago'] }}
    </span>
  </div>

  <hr class="factura-sep">

  {{-- Entradas --}}
  <div class="titulo0">
    <h2>Entradas</h2>
  </div>
  <div class="montoDcompra">
    <h4>
      <span>Película</span>
      <span>{{ $comprobante['pelicula_titulo'] }}</span>
    </h4>
    <h4>
      <span>Asiento(s)</span>
      <span>{{ implode(', ', $comprobante['asientos']) }}</span>
    </h4>
    <h4>
      <span>Cantidad</span>
      <span>{{ count($comprobante['asientos']) }} entrada(s)</span>
    </h4>
    <h4>
      <span>Subtotal entradas</span>
      <span>Bs. {{ number_format($comprobante['precio_entradas'], 0) }}</span>
    </h4>
  </div>

  {{-- Confitería --}}
  @if(!empty($comprobante['golosinas']))
  <hr class="factura-sep">
  <div class="titulo">
    <h2>Confitería</h2>
  </div>
  <div class="montoDcompra">
    @foreach($comprobante['golosinas'] as $g)
      <h4>
        <span>{{ $g['titulo'] }} &times; {{ $g['cantidad'] }}</span>
        <span>Bs. {{ number_format($g['subtotal'], 0) }}</span>
      </h4>
    @endforeach
    <h4>
      <span>Subtotal confitería</span>
      <span>Bs. {{ number_format($comprobante['precio_golosinas'], 0) }}</span>
    </h4>
  </div>
  @endif

  {{-- Total pagado --}}
  <hr class="factura-sep">
  <div class="montoDcompra"
       style="background:rgba(39,174,96,0.06);border-color:rgba(39,174,96,0.22);">
    <h4>
      <span><strong>TOTAL PAGADO</strong></span>
      <span style="color:#6fd96f;">
        <strong>Bs. {{ number_format($comprobante['total'], 0) }}</strong>
      </span>
    </h4>
  </div>

  <hr class="factura-sep">

  {{-- Mensaje de agradecimiento --}}
  <div style="text-align:center;padding:20px 0 12px;">
    <p style="font-family:var(--font-serif);font-size:1.5rem;font-weight:300;
              color:var(--text-1);margin-bottom:12px;letter-spacing:-0.01em;">
      ¡Gracias por su compra!
    </p>
    <p style="font-size:0.83rem;color:var(--text-2);line-height:1.8;margin-bottom:8px;">
      Su comprobante de pago ha sido generado correctamente.<br>
      Presente el número de factura
      <strong style="color:var(--text-1);font-family:var(--font-mono);">
        {{ $comprobante['numero'] }}
      </strong>
      en taquilla para retirar sus entradas.
    </p>
    <p style="font-size:0.78rem;color:var(--text-3);">
      CineFlix &mdash; Santa Cruz, Bolivia
    </p>
  </div>

  <hr class="factura-sep">

  {{-- Botón de regreso --}}
  <div style="text-align:center;padding-top:8px;">
    <a href="{{ route('cartelera') }}"
       style="display:inline-block;background:var(--text-1);color:#0F0F0F;
              border-radius:var(--r);padding:13px 36px;font-size:0.88rem;
              font-weight:700;letter-spacing:0.03em;
              transition:background-color 200ms,transform 160ms var(--ease);"
       onmouseover="this.style.background='#d8d5d0'"
       onmouseout="this.style.background='var(--text-1)'">
      &#8592;&nbsp; Volver a la cartelera
    </a>
  </div>

</div>

@endsection
