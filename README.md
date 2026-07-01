<div align="center">

# CineFlix

### Sistema de Venta de Entradas de Cine — Laravel · MVC · PHP 8.2

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MariaDB](https://img.shields.io/badge/MariaDB-XAMPP%3A3307-003545?style=for-the-badge&logo=mariadb&logoColor=white)
![Blade](https://img.shields.io/badge/Blade-SSR-F05340?style=for-the-badge&logo=laravel&logoColor=white)

> Para la documentación técnica completa (arquitectura, ciclo de vida Laravel, análisis MVC y fundamentos SSR) consultar **[MANUAL_DEFENSA.md](MANUAL_DEFENSA.md)**.

</div>

---

## 1. Descripción del Proyecto

**CineFlix** es una aplicación web para la gestión integral de un complejo de cine. Permite navegar la **cartelera**, ver el **detalle** de cada película, **seleccionar butacas** en una sala interactiva, añadir productos de **confitería**, generar la **factura** y completar el **pago** (QR, tarjeta o Tigo Money). Incluye un **panel de administración** con CRUD completo de películas y confitería.

El proyecto implementa el patrón **MVC con Laravel** sobre una base de datos MySQL preexistente (`cineboom`) con esquema en español.

---

## 2. Stack Tecnológico

| Capa | Tecnología |
|------|-----------|
| Framework | Laravel 11 (PHP 8.2+) |
| Motor de vistas | Blade (SSR) |
| Base de datos | MariaDB 10.4 / MySQL (XAMPP, puerto 3307) |
| Autenticación | Laravel Auth con lógica personalizada (campo `usuario`) |
| Frontend | HTML5 + CSS3 + JavaScript vanilla |
| Servidor local | Apache (XAMPP) |

---

## 3. Estructura MVC

```
app/
├── Http/Controllers/   ← Controladores (C): lógica de negocio
└── Models/             ← Modelos (M): Eloquent ORM sobre tablas en español

resources/views/        ← Vistas (V): plantillas Blade con herencia de layouts
routes/web.php          ← Tabla de enrutamiento HTTP completa
```

| Modelo | Tabla BD |
|--------|---------|
| `User` | `usuarios` |
| `Pelicula` | `peliculas` |
| `Confiteria` | `confiteria` |
| `Contacto` | `contactos` |

---

## 4. Cuentas de Prueba

| Usuario | Contraseña | Rol |
|---------|-----------|-----|
| `admin` | `password` | Administrador |
| `usuario1` | `password` | Usuario normal |

---

## 5. Guía de Instalación Local (XAMPP)

> **Requisitos:** XAMPP (Apache + MariaDB), PHP 8.2+, Composer. El puerto de MySQL debe ser **3307**.

**Paso 1 — Clonar / ubicar el proyecto:**

```
c:\xampp\htdocs\CineFlix\
```

**Paso 2 — Instalar dependencias PHP:**

```bash
composer install
```

**Paso 3 — Configurar el archivo `.env`** (copiar de `.env.example` si no existe):

```bash
cp .env.example .env
```

```env
APP_NAME=CineFlix
APP_ENV=local
APP_URL=http://localhost/CineFlix

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=cineboom
DB_USERNAME=root
DB_PASSWORD=
```

**Paso 4 — Generar la clave de la aplicación:**

```bash
php artisan key:generate
```

**Paso 5 — Crear la base de datos e importar el SQL:**

En **phpMyAdmin** (`http://localhost/phpmyadmin`):
- Pestaña **Importar** → seleccionar [`database/cineboom.sql`](database/cineboom.sql) → **Continuar**.

O por consola:

```bash
mysql -u root -P 3307 -h 127.0.0.1 < database/cineboom.sql
```

**Paso 6 — Limpiar caché de configuración:**

```bash
php artisan config:clear
```

**Paso 7 — Abrir en el navegador:**

```
http://localhost/CineFlix
```

Iniciar sesión con `admin` / `password` para acceder al panel en `/admin`.

### Verificación rápida

```bash
php artisan tinker --execute="echo App\Models\Pelicula::count();"
# debe imprimir: 4
```

---

<div align="center">

**CineFlix** · Laravel 11 · Arquitectura MVC · PHP 8.2+

Ver documentación técnica completa: **[MANUAL_DEFENSA.md](MANUAL_DEFENSA.md)**

</div>
