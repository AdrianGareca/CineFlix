<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AsientoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PeliculaController;
use Illuminate\Support\Facades\Route;

// ── Inicio / Cartelera ───────────────────────────────────────
Route::get('/',          [HomeController::class, 'cartelera'])->name('home');
Route::get('/cartelera', [HomeController::class, 'cartelera'])->name('cartelera');

// ── Confitería (GET muestra el menú, POST guarda la selección) ─
Route::get('/golosinas',  [HomeController::class, 'golosinas'])->name('golosinas');
Route::post('/golosinas', [HomeController::class, 'guardarGolosinas'])->name('golosinas.guardar');

// ── Detalle de película ──────────────────────────────────────
Route::get('/pelicula/{id}', [PeliculaController::class, 'show'])->name('peliculas.show');

// ── Selección de butacas ─────────────────────────────────────
Route::get('/asientos',  [AsientoController::class, 'index'])->name('asientos.index');
Route::post('/asientos', [AsientoController::class, 'guardar'])->name('asientos.guardar');

// ── Checkout / Factura ───────────────────────────────────────
Route::get('/factura',             [FacturaController::class, 'index'])->name('factura.index');
Route::get('/factura/comprobante', [FacturaController::class, 'comprobante'])->name('factura.comprobante');

// ── Pantallas de pago ────────────────────────────────────────
Route::get('/pago/qr',      [PagoController::class, 'qr'])->name('pago.qr');
Route::get('/pago/tarjeta', [PagoController::class, 'tarjeta'])->name('pago.tarjeta');
Route::get('/pago/tigo',    [PagoController::class, 'tigo'])->name('pago.tigo');

// ── Confirmación de pago (POST) ───────────────────────────────
Route::post('/pago/qr',      [PagoController::class, 'procesarQr'])->name('pago.qr.procesar');
Route::post('/pago/tarjeta', [PagoController::class, 'procesarTarjeta'])->name('pago.tarjeta.procesar');
Route::post('/pago/tigo',    [PagoController::class, 'procesarTigo'])->name('pago.tigo.procesar');

// ── Contacto ─────────────────────────────────────────────────
Route::get('/contacto',  [ContactoController::class, 'index'])->name('contacto');
Route::post('/contacto', [ContactoController::class, 'enviar'])->name('contacto.enviar');

// ── Autenticación ────────────────────────────────────────────
Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');
Route::get('/registro',  [AuthController::class, 'showRegistro'])->name('registro');
Route::post('/registro', [AuthController::class, 'registro'])->name('registro.store');

// ── Panel de administración (Módulo 6) ───────────────────────
// El control de acceso se aplica dentro de AdminController::guard() (rol === 'admin').
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // Películas — CRUD
    Route::get('/peliculas',              [AdminController::class, 'peliculasIndex'])->name('peliculas.index');
    Route::get('/peliculas/crear',        [AdminController::class, 'peliculasCreate'])->name('peliculas.create');
    Route::post('/peliculas',             [AdminController::class, 'peliculasStore'])->name('peliculas.store');
    Route::get('/peliculas/{id}/editar',  [AdminController::class, 'peliculasEdit'])->name('peliculas.edit');
    Route::put('/peliculas/{id}',         [AdminController::class, 'peliculasUpdate'])->name('peliculas.update');
    Route::delete('/peliculas/{id}',      [AdminController::class, 'peliculasDestroy'])->name('peliculas.destroy');

    // Confitería — CRUD
    Route::get('/confiteria',             [AdminController::class, 'confiteriaIndex'])->name('confiteria.index');
    Route::get('/confiteria/crear',       [AdminController::class, 'confiteriaCreate'])->name('confiteria.create');
    Route::post('/confiteria',            [AdminController::class, 'confiteriaStore'])->name('confiteria.store');
    Route::get('/confiteria/{id}/editar', [AdminController::class, 'confiteriaEdit'])->name('confiteria.edit');
    Route::put('/confiteria/{id}',        [AdminController::class, 'confiteriaUpdate'])->name('confiteria.update');
    Route::delete('/confiteria/{id}',     [AdminController::class, 'confiteriaDestroy'])->name('confiteria.destroy');
});
