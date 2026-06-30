<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crear cuenta — CineFlix</title>
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
  <h2>Crear cuenta</h2>

  @if($errors->any())
    <div class="error-msg">
      <ul style="margin:0;padding-left:16px;">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('registro.store') }}">
    @csrf
    <div class="cb-field">
      <label class="cb-label" for="nombre">Nombre completo</label>
      <input class="cb-input" type="text" id="nombre" name="nombre"
             value="{{ old('nombre') }}" placeholder="Tu nombre" required autofocus>
    </div>
    <div class="cb-field">
      <label class="cb-label" for="correo">Correo electrónico</label>
      <input class="cb-input" type="email" id="correo" name="correo"
             value="{{ old('correo') }}" placeholder="tucorreo@ejemplo.com" required>
    </div>
    <div class="cb-field">
      <label class="cb-label" for="usuario">Nombre de usuario</label>
      <input class="cb-input" type="text" id="usuario" name="usuario"
             value="{{ old('usuario') }}" placeholder="usuario123" required>
    </div>
    <div class="cb-field">
      <label class="cb-label" for="contrasena">Contraseña</label>
      <input class="cb-input" type="password" id="contrasena" name="contrasena"
             placeholder="Mínimo 6 caracteres" required>
    </div>
    <div class="cb-field">
      <label class="cb-label" for="contrasena_confirmation">Confirmar contraseña</label>
      <input class="cb-input" type="password" id="contrasena_confirmation"
             name="contrasena_confirmation" placeholder="Repite tu contraseña" required>
    </div>
    <button type="submit" class="login-submit">Crear cuenta</button>
  </form>

  <hr class="login-divider">
  <p class="login-alt">¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
</div>

</body>
</html>
