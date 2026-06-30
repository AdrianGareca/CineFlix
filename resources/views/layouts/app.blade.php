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

  @yield('content')

  {{-- Views that need no footer override this section with an empty @section block --}}
  @section('footer')
    @include('layouts.footer')
  @show

  @stack('scripts')
</body>
</html>
