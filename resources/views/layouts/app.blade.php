<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'CineFlix') — CineFlix</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
  @stack('styles')
</head>
<body>

  @include('layouts.nav')

  @if(session('exito'))
    <div style="background:rgba(39,174,96,0.12);border:1px solid rgba(39,174,96,0.28);
                color:#6fd96f;padding:14px 20px;border-radius:var(--r);
                max-width:960px;margin:20px auto 0;font-size:0.9rem;text-align:center;">
      {{ session('exito') }}
    </div>
  @endif

  @if(session('error'))
    <div style="background:rgba(196,64,90,0.12);border:1px solid rgba(196,64,90,0.28);
                color:#e57a8a;padding:14px 20px;border-radius:var(--r);
                max-width:960px;margin:20px auto 0;font-size:0.9rem;text-align:center;">
      {{ session('error') }}
    </div>
  @endif

  @yield('content')

  {{-- Views that need no footer override this section with an empty @section block --}}
  @section('footer')
    @include('layouts.footer')
  @show

  @stack('scripts')
</body>
</html>
