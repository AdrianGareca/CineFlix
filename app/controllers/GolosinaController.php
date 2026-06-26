<?php
/**
 * GolosinaController — Tienda de confitería (cara al público).
 */
class GolosinaController extends Controller
{
    public function index(): void
    {
        $modelo = $this->modelo('Confiteria');
        $this->vista('golosinas/index', [
            'productos' => $modelo->todas('ASC'),
        ]);
    }
}
