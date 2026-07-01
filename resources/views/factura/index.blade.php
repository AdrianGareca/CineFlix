@extends('layouts.app')

@section('title', 'Resumen de compra')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/factura.css') }}">
@endpush

@section('footer')@endsection

@section('content')

<div class="cb-back-bar">
  <a href="{{ route('golosinas') }}" class="cb-back">&#8592; Confitería</a>
</div>

<div class="conteneidor">
  <p class="factura-logo">CineFlix &nbsp;·&nbsp; Resumen de compra</p>

  {{-- Entradas --}}
  <div class="titulo0">
    <h2>Entradas</h2>
  </div>
  <div class="montoDcompra">
    <h4><span>Película</span>         <span>{{ $pelicula_titulo }}</span></h4>
    <h4><span>Asientos</span>         <span>{{ implode(', ', $asientos) }}</span></h4>
    <h4><span>Subtotal entradas</span><span>Bs. {{ number_format($precio_entradas, 0) }}</span></h4>
  </div>

  {{-- Confitería --}}
  @if(!empty($golosinas))
  <hr class="factura-sep">
  <div class="titulo">
    <h2>Confitería</h2>
  </div>
  <div class="montoDcompra">
    @foreach($golosinas as $g)
      <h4>
        <span>{{ $g['titulo'] }} × {{ $g['cantidad'] }}</span>
        <span>Bs. {{ number_format($g['subtotal'], 0) }}</span>
      </h4>
    @endforeach
    <h4>
      <span>Subtotal confitería</span>
      <span>Bs. {{ number_format($precio_golosinas, 0) }}</span>
    </h4>
  </div>
  @endif

  {{-- Total --}}
  <hr class="factura-sep">
  <div class="montoDcompra">
    <h4>
      <span><strong>TOTAL A PAGAR</strong></span>
      <span><strong>Bs. {{ number_format($total, 0) }}</strong></span>
    </h4>
  </div>

  <hr class="factura-sep">

  {{-- Métodos de pago --}}
  <div class="titulo2">
    <h2>Selecciona el método de pago</h2>
  </div>
  <div class="botonesDpago">
    <div class="QR">
      <a href="{{ route('pago.qr') }}">Pagar con QR</a>
    </div>
    <div class="tarjeta">
      <a href="{{ route('pago.tarjeta') }}">Pagar con Tarjeta</a>
    </div>
    <div class="tigoM">
      <a href="{{ route('pago.tigo') }}">Pagar con Tigo Money</a>
    </div>
  </div>
</div>

@endsection
