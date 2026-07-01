# MANUAL TÉCNICO — CineFlix
## Documentación Arquitectónica para Defensa Universitaria

**Proyecto:** CineFlix — Sistema de Venta de Entradas de Cine  
**Framework:** Laravel 11 (PHP 8.2+)  
**Patrón:** MVC (Modelo-Vista-Controlador)  
**Base de datos:** MariaDB / MySQL — esquema `cineboom`  

---

## ÍNDICE

1. [El Framework Laravel y su Ecosistema](#1-el-framework-laravel-y-su-ecosistema)
2. [El Patrón MVC en CineFlix](#2-el-patrón-mvc-en-cineflix)
3. [Análisis Detallado de Controladores y Métodos](#3-análisis-detallado-de-controladores-y-métodos)
4. [SSR vs. AJAX — Decisión Arquitectónica](#4-ssr-vs-ajax--decisión-arquitectónica)

---

## 1. El Framework Laravel y su Ecosistema

### 1.1 ¿Qué es Laravel?

Laravel es un framework PHP de código abierto que implementa el patrón arquitectónico **MVC (Modelo-Vista-Controlador)**. Provee una capa de abstracción sobre PHP puro que resuelve de manera estandarizada y segura los problemas recurrentes del desarrollo web: enrutamiento HTTP, acceso a base de datos mediante ORM, autenticación, manejo de sesiones, validación de formularios, protección contra ataques CSRF y compilación de plantillas de vista.

CineFlix utiliza **Laravel 11**, cuya principal característica diferenciadora respecto a versiones anteriores es el nuevo sistema de arranque "slim": se elimina el antiguo `app/Http/Kernel.php` y toda la configuración de la aplicación se centraliza en un único archivo expresivo (`bootstrap/app.php`) mediante una API fluida encadenada.

### 1.2 El Ciclo de Vida de una Petición HTTP

Cuando un usuario introduce `http://localhost/CineFlix/public/` en su navegador, se desencadena la siguiente cadena de eventos de manera secuencial e imperativa:

```
Navegador
    ↓  HTTP GET /
Apache (XAMPP)
    ↓  reescritura de URL → public/index.php
public/index.php          ← ÚNICO punto de entrada de la aplicación
    ↓
vendor/autoload.php       ← Composer carga todas las clases del framework
    ↓
bootstrap/app.php         ← Laravel se instancia y configura
    ↓
HTTP Kernel               ← Middleware stack (sesiones, CSRF, etc.)
    ↓
routes/web.php            ← El Router resuelve la URL contra las rutas registradas
    ↓
XxxController@metodo      ← Laravel instancia el controlador e invoca el método
    ↓
Pelicula::all()           ← Eloquent ORM ejecuta la query SQL
    ↓
return view('...')        ← Blade compila la plantilla a PHP puro
    ↓
HTML → Response           ← Laravel envía la respuesta HTTP al navegador
```

#### `public/index.php` — El único punto de entrada

```php
// Todo request HTTP aterriza aquí. Apache tiene una regla .htaccess que
// redirige cualquier URL que no sea un archivo físico a este script.
require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
$response->send();
$kernel->terminate($request, $response);
```

Este archivo nunca debe modificarse. Es el "portero" que captura la petición HTTP del servidor web y la entrega al framework para su procesamiento.

#### `bootstrap/app.php` — Configuración declarativa de la aplicación

```php
// Código real del proyecto CineFlix:
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',    // registra todas las rutas web
        commands: __DIR__.'/../routes/console.php',
        health: '/up',                         // endpoint de health check integrado
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // El middleware stack de Laravel 11 viene preconfigurado:
        // - VerifyCsrfToken (protección automática contra CSRF)
        // - StartSession     (inicialización de sesiones PHP)
        // - ShareErrorsFromSession (errores de validación disponibles en vistas)
        // - EncryptCookies   (cifrado automático de cookies)
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Manejador de excepciones personalizable
    })->create();
```

### 1.3 Variables de Entorno y el Archivo `.env`

Laravel implementa el principio de **separación de configuración y código** (Twelve-Factor App, factor III). Las credenciales sensibles, puertos de base de datos y modos de operación se definen en el archivo `.env`, el cual:

- **Nunca se versiona** en Git (está en `.gitignore`)
- Se lee una sola vez al arrancar la aplicación
- Permite que distintos entornos (desarrollo, producción) tengan configuraciones diferentes sin modificar el código fuente

```env
# .env — CineFlix en entorno XAMPP local
APP_NAME=CineFlix
APP_ENV=local           ← activa debug, logs verbosos
APP_DEBUG=true
APP_URL=http://localhost/CineFlix

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307            ← puerto no estándar de XAMPP (evita conflicto con MySQL del SO)
DB_DATABASE=cineboom    ← nombre real de la base de datos heredada
DB_USERNAME=root
DB_PASSWORD=            ← vacío en XAMPP por defecto
```

Los archivos en `config/` acceden a estas variables mediante el helper `env()`:

```php
// config/database.php
'mysql' => [
    'driver'   => 'mysql',
    'host'     => env('DB_HOST', '127.0.0.1'),
    'port'     => env('DB_PORT', '3306'),        // toma 3307 del .env
    'database' => env('DB_DATABASE', 'laravel'), // toma 'cineboom' del .env
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', ''),
    'charset'  => 'utf8mb4',
    'collation'=> 'utf8mb4_unicode_ci',
],
```

**Implicación de seguridad crítica**: si un atacante accede al repositorio de GitHub, no encuentra ninguna credencial real. Las credenciales solo existen en el servidor donde corre la aplicación.

---

## 2. El Patrón MVC en CineFlix

El patrón **MVC (Modelo-Vista-Controlador)** divide la aplicación en tres capas con responsabilidades bien delimitadas, eliminando el acoplamiento entre la lógica de negocio, la presentación y el acceso a datos.

```
┌─────────────┐    consulta     ┌─────────────┐    query SQL    ┌──────────────┐
│ CONTROLADOR │ ──────────────► │    MODELO   │ ──────────────► │  Base Datos  │
│ (C)         │                 │    (M)      │ ◄────────────── │  cineboom    │
│ app/Http/   │ ◄────────────── │ app/Models/ │   resultado     └──────────────┘
│ Controllers │   datos PHP     └─────────────┘
│             │
│             │    pasa datos   ┌─────────────┐
│             │ ──────────────► │    VISTA    │ ──► HTML → Usuario
└─────────────┘                 │    (V)      │
                                │ resources/  │
                                │ views/      │
                                └─────────────┘
```

### 2.1 MODELOS (M) — `app/Models/`

Los modelos son clases PHP que extienden `Illuminate\Database\Eloquent\Model`. Esta herencia otorga automáticamente capacidades de **ORM (Object-Relational Mapping)**: el modelo sabe cómo traducir operaciones de objetos PHP a sentencias SQL y viceversa, sin que el desarrollador escriba una sola query manualmente.

#### Adaptación a la base de datos heredada (sin migraciones estándar)

CineFlix parte de una base de datos preexistente (`cineboom.sql`) que **no sigue las convenciones de nomenclatura de Laravel** (tabla `users`, timestamps `created_at`/`updated_at`). Los modelos resuelven esta discrepancia mediante declaraciones de mapeo explícito:

**`app/Models/Pelicula.php`**
```php
class Pelicula extends Model
{
    // Laravel por convención buscaría la tabla 'peliculas' (pluralización automática).
    // Se declara explícitamente para mayor claridad y portabilidad.
    protected $table = 'peliculas';

    // Mass Assignment Protection: SOLO estos campos pueden recibir datos
    // de formularios externos. Previene que un atacante inyecte campos
    // no autorizados (p. ej. 'id', 'rol') en un INSERT masivo.
    protected $fillable = [
        'titulo', 'descripcion', 'duracion', 'genero', 'calificacion', 'imagen',
    ];

    // Reasignación del nombre de la columna de creación.
    // Laravel busca 'created_at' por defecto; aquí apunta a 'creado_en'.
    const CREATED_AT = 'creado_en';

    // Indica que NO existe columna de última modificación.
    // Sin esto, Eloquent intentaría hacer SET updated_at=NOW() en cada UPDATE,
    // lo que provocaría un error SQL porque la columna no existe.
    const UPDATED_AT = null;
}
```

Con solo estas declaraciones, Eloquent comprende completamente la estructura de la tabla. Las operaciones disponibles son:

```php
Pelicula::all()                              // SELECT * FROM peliculas
Pelicula::find(3)                            // SELECT * FROM peliculas WHERE id = 3 LIMIT 1
Pelicula::findOrFail(3)                      // ídem, pero lanza 404 si no existe
Pelicula::orderBy('creado_en', 'desc')->get() // SELECT * ORDER BY creado_en DESC
Pelicula::orderBy('creado_en', 'desc')->take(5)->get() // + LIMIT 5
Pelicula::count()                            // SELECT COUNT(*) FROM peliculas
Pelicula::create($datos)                     // INSERT INTO peliculas (...) VALUES (...)
$pelicula->update($datos)                    // UPDATE peliculas SET ... WHERE id = ?
$pelicula->delete()                          // DELETE FROM peliculas WHERE id = ?
```

**`app/Models/User.php`** — El más complejo por gestionar autenticación:

```php
// Extiende Authenticatable en lugar del Model básico.
// Esta clase provee los contratos que el sistema Auth de Laravel necesita
// para reconocer el modelo como un "sujeto autenticable".
class User extends Authenticatable
{
    use Notifiable; // habilita notificaciones (email, SMS, etc.)

    protected $table      = 'usuarios'; // tabla no estándar
    protected $primaryKey = 'id';

    protected $fillable = ['nombre', 'correo', 'usuario', 'contrasena', 'rol'];

    // Los campos en $hidden NUNCA se incluyen cuando el modelo
    // se serializa a JSON o array (p. ej. en respuestas API).
    // Esto previene la exposición accidental de contraseñas hasheadas.
    protected $hidden = ['contrasena'];

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = null;

    // La tabla 'usuarios' no tiene columna 'remember_token'.
    // Sin esta sobreescritura, Laravel intentaría escribir en esa columna
    // al hacer login, causando un error SQL.
    public function getRememberTokenName(): string
    {
        return ''; // string vacío = deshabilitar remember token
    }

    // Sin casteos automáticos: el hashing de contraseñas se realiza
    // de forma explícita en AuthController, no automáticamente por el modelo.
    protected function casts(): array
    {
        return [];
    }
}
```

**Resumen de todos los modelos:**

| Modelo | Tabla | `CREATED_AT` | `UPDATED_AT` | Hereda de |
|--------|-------|-------------|-------------|----------|
| `User` | `usuarios` | `creado_en` | `null` | `Authenticatable` |
| `Pelicula` | `peliculas` | `creado_en` | `null` | `Model` |
| `Confiteria` | `confiteria` | `creado_en` | `null` | `Model` |
| `Contacto` | `contactos` | `enviado_en` | `null` | `Model` |

### 2.2 VISTAS (V) — `resources/views/` y el Motor Blade

Las vistas son archivos con extensión `.blade.php` que combinan HTML estático con directivas especiales del motor de plantillas Blade. Cuando Laravel procesa una vista por primera vez, la **compila** a PHP puro y almacena el resultado en `storage/framework/views/` como caché. Las peticiones subsiguientes usan el caché directamente, lo que hace el sistema significativamente más eficiente que interpretar plantillas en cada request.

#### Herencia de layouts — El mecanismo de plantillas maestras

CineFlix implementa un patrón de herencia de dos niveles. Existe un **layout maestro** que define la estructura HTML completa, y las **vistas hijas** solo aportan el contenido específico de cada página.

**Layout maestro** (`resources/views/layouts/app.blade.php`):
```blade
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'CineFlix')</title>
    {{-- @yield define un "hueco" que las vistas hijas pueden rellenar --}}
    @yield('estilos')
</head>
<body>
    @include('layouts.nav')    {{-- incluye nav.blade.php en este punto --}}

    <main>
        @yield('content')      {{-- HUECO PRINCIPAL: cada página pone su contenido aquí --}}
    </main>

    @include('layouts.footer')
    @yield('scripts')
</body>
</html>
```

**Vista hija** (p. ej. `resources/views/home/cartelera.blade.php`):
```blade
@extends('layouts.app')            {{-- "Quiero usar este layout como base" --}}

@section('titulo', 'Cartelera')    {{-- Rellena @yield('titulo') del layout --}}

@section('estilos')
    <link rel="stylesheet" href="{{ asset('css/estilo.css') }}">
@endsection

@section('content')                {{-- Rellena @yield('content') del layout --}}
    <div class="cartelera-grid">
        @foreach($peliculas as $p)
            <div class="card-pelicula">
                <img src="{{ asset($p->imagen) }}" alt="{{ $p->titulo }}">
                {{-- {{ }} imprime con escape HTML automático → previene ataques XSS --}}
                <h3>{{ $p->titulo }}</h3>
                <p>{{ $p->genero }} · {{ $p->duracion }}</p>
                <a href="{{ route('asientos.index', ['pelicula' => $p->id]) }}">
                    Comprar entrada
                </a>
            </div>
        @endforeach
    </div>
@endsection
```

#### Directivas Blade utilizadas en el proyecto

| Directiva | Función en CineFlix |
|-----------|-------------------|
| `@extends('layout')` | Hereda layout maestro |
| `@yield('slot')` | Define un hueco rellenable en el layout |
| `@section('slot')` | Rellena un hueco desde la vista hija |
| `@include('partial')` | Inserta una vista parcial (nav, footer) |
| `@foreach / @endforeach` | Itera colecciones Eloquent (películas, snacks) |
| `@if / @else / @endif` | Lógica condicional en plantillas |
| `@auth / @endauth` | Renderiza solo si hay sesión activa |
| `@guest / @endguest` | Renderiza solo si NO hay sesión |
| `@csrf` | Inserta token de protección CSRF en formularios |
| `@method('PUT')` | Simula métodos HTTP no soportados por HTML forms |
| `{{ $var }}` | Imprime con escape HTML (previene XSS) |
| `{!! $var !!}` | Imprime sin escape (solo para HTML confiable) |
| `{{ route('nombre') }}` | Genera URL de ruta nombrada |
| `{{ asset('ruta') }}` | Genera URL de archivo en `public/` |
| `@error('campo')` | Muestra errores de validación por campo |

#### Mapa completo de vistas

```
resources/views/
│
├── layouts/
│   ├── app.blade.php          ← Layout maestro público
│   ├── nav.blade.php          ← Barra de navegación (auth-aware)
│   └── footer.blade.php       ← Pie de página
│
├── home/
│   ├── cartelera.blade.php    ← Página de inicio (carousel + grid de películas)
│   └── golosinas.blade.php    ← Menú de snacks con timer de 6 minutos
│
├── peliculas/
│   └── detalle.blade.php      ← Ficha completa de película (poster, sinopsis, rating)
│
├── asientos/
│   └── index.blade.php        ← Sala interactiva 5×10 con timer de 10 minutos
│
├── factura/
│   ├── index.blade.php        ← Resumen de compra con desglose de precios
│   └── comprobante.blade.php  ← Ticket de confirmación post-pago
│
├── pago/
│   ├── qr.blade.php           ← Pantalla de pago con código QR
│   ├── tarjeta.blade.php      ← Formulario de tarjeta de crédito
│   └── tigo.blade.php         ← Formulario de Tigo Money
│
├── contacto/
│   └── index.blade.php        ← Formulario de contacto
│
├── auth/
│   ├── login.blade.php        ← Formulario de inicio de sesión
│   └── registro.blade.php     ← Formulario de registro de usuario
│
└── admin/
    ├── layout.blade.php       ← Layout del panel admin (sidebar + header)
    ├── dashboard.blade.php    ← Panel con estadísticas y tablas recientes
    ├── peliculas/
    │   ├── index.blade.php    ← Listado de películas con acciones CRUD
    │   ├── create.blade.php   ← Formulario de alta de película
    │   └── edit.blade.php     ← Formulario de edición de película
    └── confiteria/
        ├── index.blade.php    ← Listado de snacks con acciones CRUD
        ├── create.blade.php   ← Formulario de alta de snack
        └── edit.blade.php     ← Formulario de edición de snack
```

### 2.3 CONTROLADORES (C) y RUTAS — `routes/web.php`

El archivo `routes/web.php` es el **mapa de enrutamiento completo** de la aplicación. Cada entrada asocia un par `[Verbo HTTP + URI]` con un método de controlador específico, y opcionalmente le asigna un nombre de ruta para referencias internas:

```php
// Sintaxis general:
Route::VERBO('uri', [Controlador::class, 'metodo'])->name('nombre.ruta');
```

El **nombre de ruta** es crítico porque permite generar URLs dinámicamente en vistas y controladores sin hardcodear rutas:

```php
// En un controlador:
return redirect()->route('factura.index');

// En una vista Blade:
<a href="{{ route('peliculas.show', ['id' => $p->id]) }}">Ver más</a>

// Si la URL base cambia, solo hay que modificar routes/web.php.
// Todas las referencias mediante route() se actualizan automáticamente.
```

#### Tabla de rutas completa

| Verbo | URI | Controlador@Método | Nombre de Ruta |
|-------|-----|-------------------|----------------|
| GET | `/` | `HomeController@cartelera` | `home` |
| GET | `/cartelera` | `HomeController@cartelera` | `cartelera` |
| GET | `/pelicula/{id}` | `PeliculaController@show` | `peliculas.show` |
| GET | `/asientos` | `AsientoController@index` | `asientos.index` |
| POST | `/asientos` | `AsientoController@guardar` | `asientos.guardar` |
| GET | `/golosinas` | `HomeController@golosinas` | `golosinas` |
| POST | `/golosinas` | `HomeController@guardarGolosinas` | `golosinas.guardar` |
| GET | `/factura` | `FacturaController@index` | `factura.index` |
| GET | `/factura/comprobante` | `FacturaController@comprobante` | `factura.comprobante` |
| GET | `/pago/qr` | `PagoController@qr` | `pago.qr` |
| POST | `/pago/qr` | `PagoController@procesarQr` | `pago.qr.procesar` |
| GET | `/pago/tarjeta` | `PagoController@tarjeta` | `pago.tarjeta` |
| POST | `/pago/tarjeta` | `PagoController@procesarTarjeta` | `pago.tarjeta.procesar` |
| GET | `/pago/tigo` | `PagoController@tigo` | `pago.tigo` |
| POST | `/pago/tigo` | `PagoController@procesarTigo` | `pago.tigo.procesar` |
| GET | `/contacto` | `ContactoController@index` | `contacto` |
| POST | `/contacto` | `ContactoController@enviar` | `contacto.enviar` |
| GET | `/login` | `AuthController@showLogin` | `login` |
| POST | `/login` | `AuthController@login` | — |
| POST | `/logout` | `AuthController@logout` | `logout` |
| GET | `/registro` | `AuthController@showRegistro` | `registro` |
| POST | `/registro` | `AuthController@registro` | `registro.store` |
| GET | `/admin` | `AdminController@index` | `admin.dashboard` |
| GET | `/admin/peliculas` | `AdminController@peliculasIndex` | `admin.peliculas.index` |
| GET | `/admin/peliculas/crear` | `AdminController@peliculasCreate` | `admin.peliculas.create` |
| POST | `/admin/peliculas` | `AdminController@peliculasStore` | `admin.peliculas.store` |
| GET | `/admin/peliculas/{id}/editar` | `AdminController@peliculasEdit` | `admin.peliculas.edit` |
| PUT | `/admin/peliculas/{id}` | `AdminController@peliculasUpdate` | `admin.peliculas.update` |
| DELETE | `/admin/peliculas/{id}` | `AdminController@peliculasDestroy` | `admin.peliculas.destroy` |
| GET | `/admin/confiteria` | `AdminController@confiteriaIndex` | `admin.confiteria.index` |
| GET | `/admin/confiteria/crear` | `AdminController@confiteriaCreate` | `admin.confiteria.create` |
| POST | `/admin/confiteria` | `AdminController@confiteriaStore` | `admin.confiteria.store` |
| GET | `/admin/confiteria/{id}/editar` | `AdminController@confiteriaEdit` | `admin.confiteria.edit` |
| PUT | `/admin/confiteria/{id}` | `AdminController@confiteriaUpdate` | `admin.confiteria.update` |
| DELETE | `/admin/confiteria/{id}` | `AdminController@confiteriaDestroy` | `admin.confiteria.destroy` |

---

## 3. Análisis Detallado de Controladores y Métodos

### 3.1 AuthController — Autenticación Personalizada

CineFlix implementa **autenticación manual con Eloquent**, a diferencia del scaffolding estándar de Laravel (Breeze/Jetstream) que usa el campo `email`. El sistema preserva exactamente el mecanismo de la versión PHP nativa original: autenticación por nombre de usuario (`usuario`).

#### Método `login()` — Análisis línea por línea

```php
public function login(Request $request)
{
    // ── PASO 1: Validación de entrada ──────────────────────────────────────
    // Laravel verifica que los campos existen y no están vacíos.
    // Si falla, lanza una ValidationException automáticamente:
    // redirige al formulario con los errores disponibles en $errors (Blade).
    $request->validate([
        'usuario'    => 'required|string',
        'contrasena' => 'required|string',
    ]);

    // ── PASO 2: Búsqueda del usuario en BD ────────────────────────────────
    // Genera: SELECT * FROM usuarios WHERE usuario = 'valor' LIMIT 1
    // ->first() devuelve null si no encuentra ningún registro.
    $user = User::where('usuario', $request->usuario)->first();

    // ── PASO 3: Verificación doble (existencia + contraseña) ──────────────
    // Hash::check($textoPlano, $hashAlmacenado):
    //   - Internamente llama a password_verify() de PHP
    //   - Es compatible con hashes bcrypt generados por password_hash(PASSWORD_DEFAULT)
    //   - Las cuentas sembradas en cineboom.sql funcionan sin re-hashear
    //   - Si un atacante roba la BD, no puede revertir el hash a texto plano
    //
    // La condición compuesta (!$user || !Hash::check(...)) evita timing attacks:
    // si el usuario no existe, no se ejecuta Hash::check (ya falla con !$user),
    // pero el mensaje de error es idéntico para ambos casos (no revela si el
    // usuario existe en el sistema).
    if (!$user || !Hash::check($request->contrasena, $user->contrasena)) {
        return back()
            ->withErrors(['usuario' => 'Usuario o contraseña incorrectos.'])
            ->withInput(['usuario' => $request->usuario]); // conserva el campo usuario
    }

    // ── PASO 4: Establecimiento de la sesión autenticada ──────────────────
    // Auth::loginUsingId() carga el usuario en el Guard de sesión de Laravel.
    // A partir de aquí, Auth::user(), Auth::check(), @auth en Blade, etc. funcionan.
    Auth::loginUsingId($user->id);

    // ── PASO 5: Regeneración del ID de sesión ─────────────────────────────
    // Previene el ataque "Session Fixation": un atacante que haya obtenido el
    // ID de sesión previo al login no podrá usarlo post-autenticación porque
    // el ID cambió.
    $request->session()->regenerate();

    // ── PASO 6: Redirección basada en rol ─────────────────────────────────
    // redirect()->intended() redirige a la URL que el usuario intentaba acceder
    // antes de ser enviado al login. Si no hay URL previa, usa la ruta indicada.
    if ($user->rol === 'admin') {
        return redirect()->intended(route('admin.dashboard'));
    }
    return redirect()->intended(route('cartelera'));
}
```

#### Método `registro()` — Alta segura de usuarios

```php
public function registro(Request $request)
{
    $request->validate([
        'nombre'     => 'required|string|max:100',
        // unique:tabla,columna → genera: SELECT COUNT(*) FROM usuarios WHERE correo = ?
        // Si devuelve > 0, la validación falla con error de unicidad.
        'correo'     => 'required|email|max:100|unique:usuarios,correo',
        'usuario'    => 'required|string|max:50|unique:usuarios,usuario',
        // confirmed → busca automáticamente el campo 'contrasena_confirmation' en el form
        // y verifica que ambos valores sean iguales. Sin código adicional.
        'contrasena' => 'required|string|min:6|confirmed',
    ]);

    $user = User::create([
        'nombre'     => $request->nombre,
        'correo'     => $request->correo,
        'usuario'    => $request->usuario,
        // Hash::make() genera un hash bcrypt con salt aleatorio.
        // Ejemplo de resultado: $2y$12$N9qo8uLOickgx2ZMRZoMyeI...
        // El factor de coste (rounds) por defecto es 12 en Laravel 11.
        'contrasena' => Hash::make($request->contrasena),
        'rol'        => 'usuario', // siempre 'usuario', NUNCA 'admin' desde registro público
    ]);

    Auth::loginUsingId($user->id); // login automático post-registro
    return redirect()->route('cartelera');
}
```

### 3.2 AdminController — CRUD con Control de Acceso y Subida de Archivos

#### El mecanismo de guardia `guard()`

```php
private function guard(): ?RedirectResponse
{
    // Doble verificación:
    // 1. Auth::check() → ¿existe una sesión autenticada activa?
    // 2. Auth::user()->rol !== 'admin' → ¿tiene el privilegio correcto?
    // Cualquiera de las dos condiciones fallidas resulta en acceso denegado.
    if (!Auth::check() || Auth::user()->rol !== 'admin') {
        return redirect()->route('cartelera')
            ->with('error', 'Acceso denegado. Se requieren permisos de administrador.');
    }
    return null; // null = acceso permitido, el método continúa ejecutándose
}
```

Cada método público del AdminController invoca esta guardia como primera instrucción:

```php
public function peliculasIndex()
{
    if ($r = $this->guard()) return $r; // si guard() devuelve una redirección, se aborta aquí
    
    $peliculas = Pelicula::orderBy('creado_en', 'desc')->get();
    return view('admin.peliculas.index', compact('peliculas'));
}
```

Este patrón es funcionalmente equivalente a un middleware de autorización, pero implementado directamente en el controlador para máxima visibilidad y control.

#### El método `guardarImagen()` — Subida segura de archivos

```php
private function guardarImagen(Request $request, string $campo = 'imagen'): ?string
{
    if (!$request->hasFile($campo)) {
        return null; // no se envió ningún archivo, se retorna null
    }

    // $request->file() devuelve una instancia de Symfony\Component\HttpFoundation\File\UploadedFile
    $archivo = $request->file($campo);

    // ── Sanitización del nombre de archivo ────────────────────────────────
    // getClientOriginalName() devuelve el nombre que el usuario tenía en su equipo.
    // Puede contener caracteres peligrosos: espacios, tildes, /, .., etc.
    // preg_replace elimina todo lo que no sea alfanumérico, punto, guion o underscore.
    // Esto previene el ataque "Path Traversal" (p. ej. "../../etc/passwd.jpg").
    $limpio = preg_replace('/[^A-Za-z0-9._-]/', '_', $archivo->getClientOriginalName());

    // ── Unicidad garantizada por timestamp Unix ────────────────────────────
    // time() devuelve los segundos transcurridos desde el 1 de enero de 1970.
    // Ejemplo: 1751234567_Rocky_poster.jpg
    // Dos subidas en el mismo segundo producirían el mismo nombre, pero en la
    // práctica es suficiente para evitar colisiones en un sistema de cine.
    $nombre = time() . '_' . $limpio;

    // ── Movimiento del archivo temporal ───────────────────────────────────
    // PHP almacena los archivos subidos en un directorio temporal del SO.
    // ->move() los mueve al destino definitivo en public/img/.
    // public_path('img') resuelve a: C:\xampp\htdocs\CineFlix\public\img
    $archivo->move(public_path('img'), $nombre);

    // Retorna la ruta relativa, compatible con asset('img/xxxx.jpg') en Blade
    return 'img/' . $nombre;
}
```

#### Flujo completo de `peliculasStore()` — Creación de película

```php
public function peliculasStore(Request $request)
{
    // PASO 1: Guardia de acceso
    if ($r = $this->guard()) return $r;

    // PASO 2: Validación con reglas específicas para imagen
    // Si alguna regla falla, Laravel lanza ValidationException automáticamente:
    // - Redirige al formulario de creación
    // - Pone errores en session('errors') accesible como $errors en Blade
    // - Conserva los valores del formulario en session('_old_input') (helper old())
    $datos = $request->validate([
        'titulo'       => 'required|string|max:150',
        'descripcion'  => 'nullable|string',
        'duracion'     => 'nullable|string|max:50',
        'genero'       => 'nullable|string|max:80',
        'calificacion' => 'nullable|integer|min:0|max:5',
        // 'image' verifica que sea imagen (magic bytes del archivo, no solo extensión)
        // 'mimes' restringe los tipos MIME aceptados
        // 'max:4096' limita el tamaño a 4 MB (4096 KB)
        'imagen'       => 'required|image|mimes:jpeg,jpg,png,webp,gif|max:4096',
    ]);

    // PASO 3: Subida de imagen (sobreescribe el campo 'imagen' del array validado)
    $datos['imagen'] = $this->guardarImagen($request);

    // PASO 4: Inserción en base de datos
    // Eloquent genera: INSERT INTO peliculas (titulo, descripcion, ..., imagen, creado_en)
    //                  VALUES ('Rocky', 'Sinopsis...', ..., 'img/1751_Rocky.jpg', NOW())
    Pelicula::create($datos);

    // PASO 5: Redirección con mensaje flash
    // ->with('exito', ...) almacena el mensaje en sesión por una sola request.
    // En la vista admin se muestra si session('exito') no es null.
    return redirect()->route('admin.peliculas.index')
        ->with('exito', 'Película «' . $datos['titulo'] . '» creada correctamente.');
}
```

#### Lógica de `peliculasUpdate()` — Preservación de imagen existente

```php
public function peliculasUpdate(Request $request, int $id)
{
    if ($r = $this->guard()) return $r;

    // findOrFail lanza una excepción 404 automáticamente si el ID no existe.
    // Esto previene manipulación de IDs (enumeration attack).
    $pelicula = Pelicula::findOrFail($id);

    $datos = $request->validate([
        'titulo'       => 'required|string|max:150',
        'descripcion'  => 'nullable|string',
        'duracion'     => 'nullable|string|max:50',
        'genero'       => 'nullable|string|max:80',
        'calificacion' => 'nullable|integer|min:0|max:5',
        // nullable: el campo imagen es OPCIONAL en edición
        'imagen'       => 'nullable|image|mimes:jpeg,jpg,png,webp,gif|max:4096',
    ]);

    // Lógica de imagen condicional:
    // Si el admin subió una nueva imagen → usar la nueva
    // Si no subió nada → conservar la imagen existente (no sobrescribir con null)
    if ($nuevaImagen = $this->guardarImagen($request)) {
        $datos['imagen'] = $nuevaImagen;
    } else {
        unset($datos['imagen']); // elimina 'imagen' del array para no incluirlo en el UPDATE
    }

    // Eloquent genera: UPDATE peliculas SET titulo=?, descripcion=?, ... WHERE id=?
    $pelicula->update($datos);

    return redirect()->route('admin.peliculas.index')
        ->with('exito', 'Película «' . $pelicula->titulo . '» actualizada.');
}
```

### 3.3 AsientoController — Selección de Butacas y Cálculo de Precios

```php
class AsientoController extends Controller
{
    // Constantes de clase: valores inmutables en tiempo de ejecución.
    // En un sistema productivo, estos datos vendrían de una tabla 'reservas' en BD.
    // Para el alcance del proyecto (demo académico), están hardcodeados.
    private const OCUPADOS   = ['A3','A7','B1','B5','C2','C6','C9','D4','D8','E3','E7'];
    private const ESPECIALES = ['E4','E5','E6','E7']; // butacas VIP, fila E

    public function index(Request $request)
    {
        // Obtiene el ID de película del query string o, si ya viene de una sesión
        // previa, de la sesión (permite navegar hacia atrás).
        $peliculaId = $request->query('pelicula', session('booking.pelicula_id'));
        $pelicula   = $peliculaId ? Pelicula::find($peliculaId) : null;

        if (!$pelicula) {
            return redirect()->route('cartelera')
                ->with('error', 'Selecciona una película primero.');
        }

        // Inicializa el "carrito de compra" en sesión con los datos de la película.
        // Esta información persiste en todas las pantallas siguientes.
        session([
            'booking.pelicula_id'     => $pelicula->id,
            'booking.pelicula_titulo' => $pelicula->titulo,
        ]);

        return view('asientos.index', [
            'pelicula'   => $pelicula,
            'ocupados'   => self::OCUPADOS,
            'especiales' => self::ESPECIALES,
        ]);
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'asientos' => 'required|array|min:1|max:8',
            // min:1 → debe seleccionar al menos 1 butaca
            // max:8 → no puede comprar más de 8 en una transacción
        ]);

        $asientos = $request->input('asientos'); // → ['A5', 'C3', 'E5']

        // Cálculo del precio total:
        // VIP (E4, E5, E6, E7) = Bs. 55 cada una
        // Regular (resto)      = Bs. 35 cada una
        $precio = 0;
        foreach ($asientos as $asiento) {
            $precio += in_array($asiento, self::ESPECIALES) ? 55 : 35;
        }

        // Persiste las selecciones en sesión para que FacturaController las recupere
        session([
            'booking.asientos'        => $asientos,
            'booking.precio_entradas' => $precio,
        ]);

        return redirect()->route('golosinas');
    }
}
```

### 3.4 FacturaController — El Nodo Central del Checkout

```php
class FacturaController extends Controller
{
    public function index()
    {
        // Recupera todo el "carrito" de la sesión actual del usuario.
        // session('booking', []) devuelve array vacío si 'booking' no existe.
        $reserva = session('booking', []);

        // Validación de prerrequisitos del flujo:
        // Si no hay película o butacas seleccionadas, el usuario llegó por URL directa.
        // Se redirige al inicio para forzar el flujo correcto.
        if (empty($reserva['pelicula_id']) || empty($reserva['asientos'])) {
            return redirect()->route('cartelera')
                ->with('error', 'No tienes una reserva activa.');
        }

        $precioEntradas  = $reserva['precio_entradas']  ?? 0;
        $precioGolosinas = $reserva['precio_golosinas'] ?? 0;
        $total           = $precioEntradas + $precioGolosinas;

        // Actualiza el total consolidado en sesión.
        // PagoController lo leerá desde session('booking.total') para mostrarlo.
        session(['booking.total' => $total]);

        // Se pasan variables individuales (no el array booking completo) a la vista.
        // Esto sigue el principio de mínima exposición: la vista solo recibe
        // exactamente los datos que necesita para renderizarse.
        return view('factura.index', [
            'pelicula_titulo'  => $reserva['pelicula_titulo'] ?? '—',
            'asientos'         => $reserva['asientos'],
            'golosinas'        => $reserva['golosinas'] ?? [],
            'precio_entradas'  => $precioEntradas,
            'precio_golosinas' => $precioGolosinas,
            'total'            => $total,
        ]);
    }

    public function comprobante()
    {
        // Los datos del comprobante se almacenan en sesión por PagoController
        // DESPUÉS de limpiar booking. Esto permite mostrar el ticket sin
        // mantener el carrito activo.
        $comprobante = session('comprobante');

        if (empty($comprobante)) {
            return redirect()->route('cartelera')
                ->with('error', 'No hay comprobante disponible.');
        }

        return view('factura.comprobante', compact('comprobante'));
    }
}
```

### 3.5 PagoController — Procesamiento y Limpieza del Carrito

```php
class PagoController extends Controller
{
    // ── Vistas de pago (GET) ─────────────────────────────────────────────
    // Solo leen el total de la sesión y presentan el formulario correspondiente.

    public function qr()
    {
        $total = session('booking.total', 0);
        return view('pago.qr', compact('total'));
    }

    // ── Procesamiento de pago (POST) ─────────────────────────────────────
    // Se ejecutan al confirmar el pago en cada formulario.

    public function procesarTarjeta(Request $request)
    {
        // 1. Registra el comprobante con los datos del carrito actual
        $this->registrarComprobante('Tarjeta de Crédito');

        // 2. ¡LIMPIEZA COMPLETA DEL CARRITO!
        //    session()->forget('booking') elimina la clave 'booking' y TODOS
        //    sus subvalores de la sesión del usuario.
        //    Después de esta línea, el sistema está listo para una nueva compra.
        session()->forget('booking');

        // 3. Redirige al comprobante (que lee session('comprobante'), no 'booking')
        return redirect()->route('factura.comprobante');
    }

    // procesarQr() y procesarTigo() siguen el mismo patrón exacto.

    private function registrarComprobante(string $metodoPago): void
    {
        // Guarda un snapshot del booking en session('comprobante')
        // ANTES de que se borre 'booking'.
        session(['comprobante' => array_merge(
            session('booking', []),
            [
                'metodo_pago' => $metodoPago,
                'fecha'       => now()->format('d/m/Y H:i'),
                'codigo'      => strtoupper(substr(md5(uniqid()), 0, 8)),
            ]
        )]);
    }
}
```

### 3.6 Flujo de Compra Completo — Estado de Sesión Entre Pantallas

El estado de compra se preserva mediante la sesión PHP gestionada por Laravel. Cada pantalla agrega información al prefijo `booking.*`:

```
GET /asientos?pelicula=3
    ↓ AsientoController@index
    session: {
        booking.pelicula_id:     3,
        booking.pelicula_titulo: "Rocky"
    }

POST /asientos  (asientos=['A5','C3','E5'])
    ↓ AsientoController@guardar
    session: {
        booking.pelicula_id:     3,
        booking.pelicula_titulo: "Rocky",
        booking.asientos:        ["A5", "C3", "E5"],
        booking.precio_entradas: 145   ← 2×35 + 1×55 (E5 es VIP)
    }

POST /golosinas  (Pipocas×2, Soda×1)
    ↓ HomeController@guardarGolosinas
    session: {
        ...anterior...,
        booking.golosinas: [
            {titulo: "Pipocas", cantidad: 2, precio_unit: 25, subtotal: 50},
            {titulo: "Soda",    cantidad: 1, precio_unit: 15, subtotal: 15}
        ],
        booking.precio_golosinas: 65,
        booking.total:            210   ← 145 + 65
    }

GET /factura
    ↓ FacturaController@index
    Lee toda la sesión → renderiza resumen → guarda booking.total: 210

GET /pago/tarjeta
    ↓ PagoController@tarjeta
    Lee session('booking.total') → muestra formulario con total Bs. 210

POST /pago/tarjeta
    ↓ PagoController@procesarTarjeta
    registrarComprobante('Tarjeta de Crédito')  ← snapshot a session('comprobante')
    session()->forget('booking')                ← LIMPIEZA COMPLETA
    redirect → /factura/comprobante

GET /factura/comprobante
    ↓ FacturaController@comprobante
    Lee session('comprobante') → renderiza ticket final
```

---

## 4. SSR vs. AJAX — Decisión Arquitectónica

### 4.1 ¿Qué es Server-Side Rendering (SSR)?

CineFlix implementa **SSR clásico**: cada interacción del usuario que requiere procesamiento de lógica de negocio desencadena una petición HTTP completa al servidor. Laravel procesa la lógica, ejecuta las queries necesarias, renderiza el HTML completo del servidor y lo envía al navegador. El navegador descarta la página anterior y muestra la nueva.

```
[Usuario hace clic en "Comprar"]
    ↓
POST /asientos   (formulario HTML estándar)
    ↓
Apache recibe la petición → Laravel procesa
    ↓
session()->put(...) → redirect() → GET /golosinas
    ↓
Laravel renderiza golosinas.blade.php → HTML completo
    ↓
Navegador muestra página nueva (ciclo completo)
```

### 4.2 Comparación Técnica: SSR vs. AJAX/SPA

| Criterio de diseño | SSR — CineFlix | AJAX/SPA (alternativa) |
|-------------------|---------------|----------------------|
| **Complejidad de implementación** | Baja: PHP + Blade únicamente | Alta: requiere API REST + framework JS (React, Vue) |
| **Protección CSRF** | Automática mediante `@csrf` | Manual en cada petición `fetch`/`axios` |
| **SEO (indexabilidad)** | HTML indexable directamente | Requiere SSR adicional o prerendering |
| **Estado de la aplicación** | Sesión PHP gestionada por el servidor | Estado JS del cliente (Vuex, Redux, Zustand) |
| **Compatibilidad** | Funciona sin JavaScript habilitado | Requiere JavaScript obligatoriamente |
| **Fidelidad al sistema legado** | Preserva exactamente el flujo original | Requeriría reescribir el backend como API |
| **Mantenibilidad** | Un solo lenguaje (PHP/Blade) | Dos ecosistemas separados (PHP + JS) |
| **Tiempo de desarrollo** | Menor: flujo directo Form POST | Mayor: serialización, endpoints, estados de carga |

### 4.3 Justificación de la Elección SSR

La decisión de SSR no es una limitación técnica sino una **elección arquitectónica deliberada** fundamentada en tres razones:

**1. Fidelidad al sistema legado**: El sistema PHP nativo original operaba con `$_POST`, `$_SESSION` y `header('Location: ...')`. La migración a Laravel mantuvo esta semántica de petición-respuesta intacta, preservando el flujo de negocio probado y validado. Introducir AJAX habría implicado rediseñar la lógica de estado de compra completa.

**2. Reducción de superficie de ataque**: Un sistema SSR expone únicamente rutas HTML estándar con protección CSRF integrada. Una API REST AJAX expone múltiples endpoints JSON que requieren autenticación adicional (tokens JWT/Bearer), manejo de CORS, y protección contra ataques de API (rate limiting, esquemas de autorización explícitos).

**3. Coherencia tecnológica**: El equipo trabaja con PHP/Laravel. SSR permite que toda la lógica resida en un único lenguaje y paradigma, eliminando la complejidad operacional de mantener dos ecosistemas (PHP backend + JavaScript frontend) con herramientas, pruebas y despliegues separados.

### 4.4 Protección CSRF — Mecanismo de Seguridad en Formularios SSR

**CSRF (Cross-Site Request Forgery)** es un ataque donde un sitio malicioso engaña al navegador de la víctima para que envíe peticiones a la aplicación objetivo usando su sesión activa. Laravel previene esto automáticamente en todos los formularios POST/PUT/DELETE:

**En cada formulario Blade:**
```blade
<form method="POST" action="{{ route('asientos.guardar') }}">
    @csrf
    {{-- @csrf genera automáticamente: --}}
    {{-- <input type="hidden" name="_token" value="abc123xyz789..."> --}}
    ...
</form>
```

**Mecanismo interno:**
1. Al iniciar la sesión, Laravel genera un token criptográficamente aleatorio y lo almacena en la sesión del usuario.
2. `@csrf` inserta ese token como campo oculto en el formulario.
3. Al recibir cualquier petición POST/PUT/DELETE, el middleware `VerifyCsrfToken` compara el token del formulario con el almacenado en sesión.
4. Si no coinciden (o no existe), Laravel devuelve error HTTP 419 "Page Expired".
5. Un sitio externo no puede conocer el token de la sesión de otro usuario, haciendo el ataque imposible.

**Para métodos HTTP no soportados por formularios HTML** (PUT, DELETE):
```blade
<form method="POST" action="{{ route('admin.peliculas.update', $pelicula->id) }}">
    @csrf
    @method('PUT')
    {{-- Genera: <input type="hidden" name="_method" value="PUT"> --}}
    {{-- Laravel lee _method y enruta la petición como PUT semántico --}}
    ...
</form>
```

Este mecanismo permite que el CRUD semántico (GET=leer, POST=crear, PUT=actualizar, DELETE=eliminar) funcione con HTML estándar, sin requerir JavaScript para los formularios administrativos.

---

## Resumen Ejecutivo

| Componente | Implementación en CineFlix |
|-----------|--------------------------|
| **Framework** | Laravel 11 — bootstrapping slim, API fluida |
| **Punto de entrada** | `public/index.php` → `bootstrap/app.php` |
| **Enrutamiento** | `routes/web.php` — 32 rutas nombradas |
| **Modelos** | Eloquent ORM adaptado a esquema heredado `cineboom` |
| **Timestamps** | `creado_en` (personalizado), `updated_at` deshabilitado |
| **Vistas** | Blade con herencia de layouts (público + admin) |
| **Autenticación** | Manual por campo `usuario` + `Hash::check` bcrypt |
| **Autorización admin** | Guardia de rol `guard()` en cada método del AdminController |
| **Subida de imágenes** | Sanitización de nombre + timestamp Unix → `public/img/` |
| **Estado de compra** | Sesión PHP prefijo `booking.*` (5 campos acumulativos) |
| **Seguridad CSRF** | Middleware automático `VerifyCsrfToken` + `@csrf` en Blade |
| **Renderizado** | SSR completo (Form POST / GET) — sin AJAX |

---

*Documentación generada para defensa universitaria — CineFlix · Laravel 11 · MVC*
