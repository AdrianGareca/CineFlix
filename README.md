<div align="center">

# 🎬 CineFlix

### Sistema de Gestión Cinematográfica — Laravel 12 (MVC)

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MariaDB](https://img.shields.io/badge/MariaDB-XAMPP%3A3307-003545?style=for-the-badge&logo=mariadb&logoColor=white)
![Blade](https://img.shields.io/badge/Blade-Responsive-F05340?style=for-the-badge&logo=laravel&logoColor=white)

</div>

---

## 1. 📌 Título y Descripción

**CineFlix** es una aplicación web para la gestión integral de un complejo de cine. Permite navegar la **cartelera**, ver el **detalle** de cada película, **seleccionar butacas** en una sala interactiva, añadir productos de **confitería**, generar la **factura** y completar el **pago** (QR, tarjeta o Tigo Money). Incluye un **panel de administración** con CRUD de películas y confitería.

El proyecto fue **migrado en sitio** desde un sistema en **PHP nativo** (MVC artesanal con enrutador propio y PDO) hacia **Laravel 12**, conservando intactos su diseño, su base de datos en español (`cineboom`) y su mecanismo de autenticación original.

> **Estado verificado en runtime:** conexión PDO OK a `cineboom` (MariaDB 10.4 / puerto 3307); Eloquent lee las tablas sembradas (4 películas, 6 productos, 2 usuarios); login del usuario `admin` validado con su hash bcrypt original.

---

## 2. 🏛️ Explicación del MVC en el Proyecto

Laravel controla la app con un ciclo **petición → respuesta**: todo entra por `public/index.php`, se resuelve en `routes/web.php`, y el framework reparte el trabajo entre las tres capas.

### 📦 Modelos (M) — `app/Models/`

Clases **Eloquent** que mapean directamente las tablas existentes de `cineboom`. **No se usaron migraciones de Laravel** para el dominio: los modelos apuntan a las tablas y columnas en español ya creadas. Cada uno declara *timestamps personalizados* y deshabilita `updated_at` (las tablas no tienen esa columna):

| Modelo | Tabla | Timestamp | `updated_at` |
| :--- | :--- | :--- | :---: |
| `Pelicula` | `peliculas` | `creado_en` | deshabilitado |
| `Confiteria` | `confiteria` | `creado_en` | deshabilitado |
| `Contacto` | `contactos` | `enviado_en` | deshabilitado |
| `User` | `usuarios` | `creado_en` | deshabilitado |

```php
// app/Models/Pelicula.php
class Pelicula extends Model {
    protected $table = 'peliculas';
    protected $fillable = ['titulo','descripcion','duracion','genero','calificacion','imagen'];
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = null;
}
```

### 🎨 Vistas (V) — `resources/views/`

Plantillas **Blade** con **herencia**. Hay dos layouts maestros:

- **Público** → `layouts/app.blade.php`: define `@yield('content')`, incluye `layouts/nav` y `layouts/footer`, expone `@stack('styles')` y `@stack('scripts')`. Cada página hace `@extends('layouts.app')` y rellena `@section('content')`.
- **Admin** → `admin/layout.blade.php`: sidebar + zona de contenido con `@yield('admin-content')` y manejo de alertas (`exito` / `error` / errores de validación).

```text
layouts/app.blade.php     ◄── layout público
   ├── @include('layouts.nav')
   ├── @yield('content')   ◄── lo rellena cada vista hija
   └── @section('footer') @include('layouts.footer') @show

home/cartelera.blade.php  → @extends('layouts.app') + @section('content')
```

Vistas principales: `home/` (cartelera, golosinas), `peliculas/detalle`, `asientos/index`, `factura/index`, `pago/` (qr, tarjeta, tigo), `contacto/index`, `auth/` (login, registro) y `admin/` (dashboard + peliculas/confiteria × index/create/edit).

### 🧠 Controladores (C) — `app/Http/Controllers/`

Reciben la `Request`, consultan los modelos, devuelven una vista y **gestionan el estado del flujo de compra en sesión** bajo el prefijo `booking.*`:

```php
// HomeController@cartelera
$carrusel  = Pelicula::orderBy('creado_en','desc')->take(4)->get();
$peliculas = Pelicula::orderBy('creado_en','desc')->get();
return view('home.cartelera', compact('carrusel','peliculas'));
```

El flujo Asiento → Golosinas → Factura → Pago se encadena guardando en sesión `booking.pelicula_id`, `booking.asientos`, `booking.precio_entradas`, `booking.golosinas`, `booking.total`. Todas las rutas usan **rutas con nombre** (`cartelera`, `peliculas.show`, `asientos.index`, `factura.index`, `admin.*`, etc.).

---

## 3. 🔐 Autenticación Personalizada

El sistema **no usa** el `Auth::attempt()` ni el campo `email` que Laravel asume por defecto. Se preservó **exactamente** el mecanismo del PHP nativo, que autentica por **`usuario`** (nombre de usuario), no por correo:

1. El formulario de login envía **`usuario`** + `contrasena`.
2. `AuthController@login` busca con `User::where('usuario', …)->first()`.
3. Verifica con `Hash::check($contrasena, $user->contrasena)`. El hash bcrypt creado por el `password_hash(PASSWORD_DEFAULT)` original **es compatible** con `Hash::check`, así que las cuentas existentes funcionan sin re-hashear.
4. Tras autenticar: `Auth::loginUsingId()` + `session()->regenerate()`. Los **admin** van a `admin.dashboard`; el resto a `cartelera`.

```php
// app/Http/Controllers/AuthController.php
$user = User::where('usuario', $request->usuario)->first();
if (!$user || !Hash::check($request->contrasena, $user->contrasena)) {
    return back()->withErrors(['usuario' => 'Usuario o contraseña incorrectos.']);
}
Auth::loginUsingId($user->id);
```

> En el modelo `User`, `contrasena` está en `$hidden` y el remember-token está deshabilitado (la tabla `usuarios` no tiene esa columna).

### Guard de rol admin (protección de `/admin`)

El grupo de rutas `admin.*` está protegido. Cada método de `AdminController` ejecuta un guard privado al inicio: si el usuario no está autenticado **o** `Auth::user()->rol !== 'admin'`, se le redirige a `cartelera` con un mensaje *flash* de error.

```php
private function guard(): ?RedirectResponse {
    if (!Auth::check() || Auth::user()->rol !== 'admin') {
        return redirect()->route('cartelera')
            ->with('error', 'Acceso restringido: necesitas una cuenta de administrador.');
    }
    return null;
}
// uso al inicio de cada acción:  if ($r = $this->guard()) return $r;
```

La barra de navegación (`layouts/nav`) solo muestra el enlace **Admin** cuando `auth()->user()->rol === 'admin'`.

**Cuentas de prueba sembradas** (en `database/cineboom.sql`):

| Usuario | Contraseña | Rol |
| :--- | :--- | :--- |
| `admin` | `password` | admin |
| `usuario1` | `password` | usuario |

---

## 4. 📱 Diseño Responsive

Cada hoja de `public/css/` incluye media queries. Puntos optimizados para móvil/tablet:

- **Mapa de butacas** (`asiento.css`): en `@media (max-width: 480px)` las butacas y los espacios se reducen de `22px` a `18px` y el `gap` de la fila baja a `3px`, de modo que las 12 columnas de asientos quepan en pantallas angostas. Como respaldo, el contenedor `.mapa` usa `overflow-x: auto` para permitir desplazamiento horizontal si la sala es muy ancha.
- **Sidebar del admin** (`admin.css`): en `@media (max-width: 768px)` el sidebar pasa de `220px` a `60px` (solo iconos, se oculta el texto con `display:none`), el `.contenido-admin` ajusta su `margin-left` a `60px`, y el `.form-grid` de los formularios colapsa de 2 columnas a **1 columna** (`grid-template-columns: 1fr`).
- **Formularios y tablas**: los inputs usan ancho fluido dentro de `.form-grupo`; las tablas de listados van dentro de `.tabla-wrapper { overflow-x: auto }` para no romper el layout en celulares.
- **Grillas de películas / confitería** (`estilo.css`, `golosina.css`): las cuadrículas pasan a una sola columna en viewports pequeños mediante sus propias media queries.
- Todas las vistas declaran `<meta name="viewport" content="width=device-width, initial-scale=1.0">`.

---

## 5. 🚀 Guía de Instalación Paso a Paso

> Requisitos: **XAMPP** (Apache + MySQL/MariaDB), **PHP 8.2+**, **Composer**. MySQL debe escuchar en el puerto **3307**.

**1. Ubicar el proyecto** dentro de XAMPP:

```text
c:\xampp\htdocs\CineFlix
```

**2. Instalar dependencias** de PHP con Composer:

```bash
composer install
```

**3. Preparar el archivo `.env`** en la raíz (si no existe, copiar de `.env.example`) y configurar la base de datos:

```bash
# Si hace falta crearlo:
cp .env.example .env
```

```env
APP_NAME=CineFlix
APP_ENV=local
APP_URL=http://localhost/CineFlix

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307          # ⚠️ Puerto no estándar (evita conflicto en XAMPP)
DB_DATABASE=cineboom
DB_USERNAME=root
DB_PASSWORD=
```

**4. Generar la clave de la aplicación:**

```bash
php artisan key:generate
```

**5. Crear la base de datos e importar el `.sql` original.** En **phpMyAdmin** (`http://localhost/phpmyadmin`):

- Pestaña **Importar** → seleccionar [`database/cineboom.sql`](database/cineboom.sql) → **Continuar**.

El script crea la base `cineboom`, las 4 tablas (`usuarios`, `peliculas`, `confiteria`, `contactos`) y los datos iniciales. Alternativa por consola:

```bash
mysql -u root -P 3307 -h 127.0.0.1 < database/cineboom.sql
```

**6. (Recomendado) Limpiar la configuración cacheada:**

```bash
php artisan config:clear
```

**7. Abrir la aplicación** en el navegador:

```text
http://localhost/CineFlix
```

   Iniciar sesión con **`admin` / `password`** para acceder al panel en `/admin`.

### Verificar que todo conecta (opcional)

```bash
php artisan tinker --execute="echo App\Models\Pelicula::count();"   # debe imprimir 4
```

---

<div align="center">

**CineFlix** · Migración a Laravel 12 · Arquitectura MVC verificada en runtime

</div>
