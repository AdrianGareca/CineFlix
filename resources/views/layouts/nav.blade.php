<nav class="cb-nav">
  <a class="cb-nav-logo" href="{{ route('cartelera') }}">
    <svg width="22" height="22" viewBox="0 0 64 64">
      <rect x="4"  y="20" width="56" height="36" rx="3" fill="none" stroke="#C4405A" stroke-width="3.5"/>
      <line x1="5"  y1="19" x2="57" y2="4"  stroke="#C4405A" stroke-width="3"/>
      <line x1="20" y1="10" x2="17" y2="20" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
      <line x1="32" y1="6"  x2="29" y2="16" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
      <line x1="44" y1="2"  x2="41" y2="12" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
    </svg>
    CineFlix
  </a>
  <nav class="cb-nav-links">
    <a href="{{ route('cartelera') }}">Cartelera</a>
    <a href="{{ route('golosinas') }}">Confitería</a>
    @auth
      @if(auth()->user()->rol === 'admin')
        <a href="{{ route('admin.dashboard') }}">Admin</a>
      @endif
      <a href="{{ route('logout') }}" class="nav-salir"
         onclick="event.preventDefault(); document.getElementById('cf-logout').submit();">Salir</a>
      <form id="cf-logout" action="{{ route('logout') }}" method="POST" hidden>@csrf</form>
    @else
      <a href="{{ route('login') }}" class="nav-salir">Entrar</a>
    @endauth
  </nav>
</nav>
