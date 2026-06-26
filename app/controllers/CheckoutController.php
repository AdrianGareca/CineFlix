<?php
/**
 * CheckoutController — Proceso de compra: selección de butacas,
 * datos de facturación y métodos de pago.
 *
 * Estas vistas eran páginas HTML estáticas; aquí se integran al MVC.
 * Requieren sesión iniciada.
 */
class CheckoutController extends Controller
{
    /** Mapa de selección de butacas. */
    public function asientos(): void
    {
        requerirLogin();
        $this->vista('asientos/index');
    }

    /** Resumen de compra y datos de facturación. */
    public function factura(): void
    {
        requerirLogin();
        $this->vista('factura/index');
    }

    /** Pantalla de pago según el método (?ruta=pago&metodo=qr|tarjeta|tigo). */
    public function pago(): void
    {
        requerirLogin();
        $metodo = $_GET['metodo'] ?? 'qr';

        $vistas = [
            'qr'      => 'pago/qr',
            'tarjeta' => 'pago/tarjeta',
            'tigo'    => 'pago/tigo',
        ];

        $vista = $vistas[$metodo] ?? $vistas['qr'];
        $this->vista($vista);
    }
}
