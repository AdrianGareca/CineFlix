<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Entrar — CineFlix</title>
  <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
  <link rel="stylesheet" href="{{ asset('css/StyleLogin.css') }}">
</head>
<body>

<div class="login-logo">
  <svg width="34" height="34" viewBox="0 0 64 64">
    <rect x="4" y="20" width="56" height="36" rx="3" fill="none" stroke="#C4405A" stroke-width="3.5"/>
    <line x1="5"  y1="19" x2="57" y2="4"  stroke="#C4405A" stroke-width="3"/>
    <line x1="20" y1="10" x2="17" y2="20" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
    <line x1="32" y1="6"  x2="29" y2="16" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
    <line x1="44" y1="2"  x2="41" y2="12" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
  </svg>
  <span class="login-logo-text">CineFlix</span>
</div>

<div class="login-card">
  <h2>Bienvenido de vuelta</h2>

  @if($errors->any())
    <div class="error-msg">{{ $errors->first() }}</div>
  @endif

  <form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="cb-field">
      <label class="cb-label" for="usuario">Usuario</label>
      <input class="cb-input" type="text" id="usuario" name="usuario"
             value="{{ old('usuario') }}" placeholder="Tu nombre de usuario" required autofocus>
    </div>
    <div class="cb-field">
      <label class="cb-label" for="contrasena">Contraseña</label>
      <input class="cb-input" type="password" id="contrasena" name="contrasena"
             placeholder="••••••••" required>
    </div>
    <button type="submit" class="login-submit">Entrar</button>
  </form>

  <hr class="login-divider">
  <p class="login-alt">¿No tienes cuenta? <a href="{{ route('registro') }}">Regístrate</a></p>
  <p class="login-alt" style="margin-top:8px;">
    <a href="{{ route('cartelera') }}">← Volver a la cartelera</a>
  </p>
</div>

</body>
</html>
