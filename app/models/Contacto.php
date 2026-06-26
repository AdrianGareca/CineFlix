<?php
/**
 * Modelo Contacto — Tabla `contactos`
 * Almacena los mensajes enviados desde el formulario de contacto.
 */
class Contacto
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /** Inserta un nuevo mensaje de contacto. */
    public function guardar(string $nombre, string $correo, string $mensaje): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO contactos (nombre, correo, mensaje) VALUES (?, ?, ?)"
        );
        return $stmt->execute([$nombre, $correo, $mensaje]);
    }
}
