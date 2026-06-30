<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('titulo', 'Panel') — CineFlix Admin</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
<div class="admin-wrapper">

  {{-- ── Sidebar ─────────────────────────────────────────────── --}}
  <aside class="sidebar">
    <div class="sidebar-logo">
      <svg width="22" height="22" viewBox="0 0 64 64">
        <rect x="4" y="20" width="56" height="36" rx="3" fill="none" stroke="#C4405A" stroke-width="3.5"/>
        <line x1="5" y1="19" x2="57" y2="4" stroke="#C4405A" stroke-width="3"/>
      </svg>
      <span>CineFlix</span>
    </div>

    <p class="sidebar-bienvenida">Hola, {{ auth()->user()->nombre ?? 'Admin' }}</p>

    <nav class="sidebar-nav">
      <a href="{{ route('admin.dashboard') }}"
         class="nav-item {{ request()->routeIs('admin.dashboard') ? 'activo' : '' }}">
        <span>Dashboard</span>
      </a>
      <a href="{{ route('admin.peliculas.index') }}"
         class="nav-item {{ request()->routeIs('admin.peliculas.*') ? 'activo' : '' }}">
        <span>Películas</span>
      </a>
      <a href="{{ route('admin.confiteria.index') }}"
         class="nav-item {{ request()->routeIs('admin.confiteria.*') ? 'activo' : '' }}">
        <span>Confitería</span>
      </a>
      <a href="{{ route('cartelera') }}" class="nav-item">
        <span>← Volver al sitio</span>
      </a>
    </nav>

    <a href="{{ route('logout') }}" class="btn-cerrar"
       onclick="event.preventDefault(); document.getElementById('admin-logout').submit();">
      <span>Cerrar sesión</span>
    </a>
    <form id="admin-logout" action="{{ route('logout') }}" method="POST" hidden>@csrf</form>
  </aside>

  {{-- ── Contenido ───────────────────────────────────────────── --}}
  <main class="contenido-admin">
    <div class="seccion-header">
      <h1>@yield('encabezado', 'Panel de Administración')</h1>
    </div>

    @if(session('exito'))
      <div class="alerta exito">{{ session('exito') }}</div>
    @endif
    @if(session('error'))
      <div class="alerta error">{{ session('error') }}</div>
    @endif
    @if($errors->any())
      <div class="alerta error">
        <strong>Revisa el formulario:</strong>
        <ul style="margin:6px 0 0 18px;">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @yield('admin-content')
  </main>

</div>
</body>
</html>
