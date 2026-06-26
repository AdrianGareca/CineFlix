# CineFlix — Arquitectura MVC

Sitio web de cine sobre **PHP + MySQL (XAMPP)** reestructurado con el patrón **Modelo-Vista-Controlador** y un **front controller** único (`index.php`).

## Cómo ejecutar (XAMPP)

1. Copia esta carpeta dentro de `htdocs` (ya está en `C:\xampp\htdocs\cineboom 2\CineFlix`).
2. Inicia **Apache** y **MySQL** desde el panel de XAMPP.
3. Crea la base de datos: abre `http://localhost/phpmyadmin`, pestaña **Importar**, y selecciona `database/cineboom.sql` (o pega su contenido en la pestaña SQL).
4. Abre en el navegador:
   `http://localhost/cineboom%202/CineFlix/index.php`

Si la conexión falla, revisa los datos en `app/config/config.php` (host, usuario, contraseña).

### Cuentas de prueba
- **Admin:** usuario `admin` · contraseña `password`
- **Usuario:** usuario `usuario1` · contraseña `password`

## Estructura del proyecto

```
CineFlix/
├── index.php              ← FRONT CONTROLLER (único punto de entrada)
├── .htaccess              ← index.php por defecto
│
├── app/
│   ├── .htaccess          ← bloquea acceso web directo a la lógica
│   ├── config/
│   │   └── config.php     ← configuración (BD, rutas, app)
│   ├── core/
│   │   ├── Database.php    ← conexión PDO (singleton)
│   │   ├── Controller.php  ← controlador base (carga modelos/vistas)
│   │   ├── Router.php      ← mapa de rutas → controlador/acción
│   │   └── helpers.php     ← url(), asset(), e(), auth, redirecciones
│   ├── controllers/
│   │   ├── AuthController.php       ← login / registro / logout
│   │   ├── CarteleraController.php  ← cartelera y detalle
│   │   ├── GolosinaController.php   ← confitería pública
│   │   ├── AdminController.php      ← panel admin (CRUD)
│   │   └── CheckoutController.php   ← butacas, factura, pagos
│   ├── models/
│   │   ├── Usuario.php
│   │   ├── Pelicula.php
│   │   └── Confiteria.php
│   └── views/
│       ├── layouts/    ← header, footer, nav, admin_header, admin_footer
│       ├── auth/       ← login, registro
│       ├── cartelera/  ← index, detalle
│       ├── golosinas/  ← index
│       ├── admin/      ← peliculas, confiteria
│       ├── asientos/   ← index
│       ├── factura/    ← index
│       ├── pago/       ← qr, tarjeta, tigo
│       └── 404.php
│
├── css/  img/  js/  uploads/   ← recursos estáticos
├── database/cineboom.sql        ← script de creación de la BD
└── _backup_original/            ← copia de los archivos antes del MVC
```

## Rutas (todas pasan por `index.php?ruta=`)

| URL | Controlador → acción |
|-----|----------------------|
| `?ruta=login` | AuthController::login |
| `?ruta=registro` | AuthController::registro |
| `?ruta=logout` | AuthController::logout |
| `?ruta=cartelera` | CarteleraController::index |
| `?ruta=vermas&id=N` | CarteleraController::detalle |
| `?ruta=golosinas` | GolosinaController::index |
| `?ruta=admin&seccion=peliculas\|confiteria` | AdminController::index |
| `?ruta=asientos` | CheckoutController::asientos |
| `?ruta=factura` | CheckoutController::factura |
| `?ruta=pago&metodo=qr\|tarjeta\|tigo` | CheckoutController::pago |

Los archivos `.php` y `.html` antiguos (login.php, cartelera.php, HTML/ASIENTOS.html, etc.)
se conservan como **redirecciones** al front controller, por compatibilidad con enlaces guardados.

## Cómo agregar una función nueva

1. **Modelo** (si toca BD): agrega un método en `app/models/`.
2. **Controlador**: crea/usa un método en `app/controllers/`.
3. **Ruta**: registra `'mi_ruta' => ['MiController', 'miMetodo']` en `app/core/Router.php`.
4. **Vista**: crea el `.php` en `app/views/` y muéstralo con `$this->vista('carpeta/archivo', $datos)`.

## Cambios en la base de datos

El script vive en `database/cineboom.sql`. Para recrear la BD desde cero, vuelve a importarlo
en phpMyAdmin. Si agregas tablas/columnas, edita ese archivo y crea el modelo correspondiente.
