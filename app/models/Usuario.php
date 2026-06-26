<?php
/**
 * Modelo Usuario — Tabla `usuarios`
 * Maneja autenticación y registro de cuentas.
 */
class Usuario
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /** Busca un usuario por su nombre de usuario (login). */
    public function buscarPorUsuario(string $usuario): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT id, nombre, usuario, contrasena, rol FROM usuarios WHERE usuario = ?"
        );
        $stmt->execute([$usuario]);
        $fila = $stmt->fetch();
        return $fila ?: null;
    }

    /** ¿Existe ya un usuario con ese nombre de usuario o correo? */
    public function existe(string $usuario, string $correo): bool
    {
        $stmt = $this->db->prepare(
            "SELECT id FROM usuarios WHERE usuario = ? OR correo = ? LIMIT 1"
        );
        $stmt->execute([$usuario, $correo]);
        return (bool) $stmt->fetch();
    }

    /** Crea un usuario normal. Devuelve true/false. */
    public function crear(string $nombre, string $correo, string $usuario, string $contrasenaPlano): bool
    {
        $hash = password_hash($contrasenaPlano, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare(
            "INSERT INTO usuarios (nombre, correo, usuario, contrasena, rol)
             VALUES (?, ?, ?, ?, 'usuario')"
        );
        return $stmt->execute([$nombre, $correo, $usuario, $hash]);
    }

    /**
     * Verifica credenciales. Si son correctas devuelve el arreglo del usuario;
     * en caso contrario devuelve null.
     */
    public function autenticar(string $usuario, string $contrasenaPlano): ?array
    {
        $fila = $this->buscarPorUsuario($usuario);
        if ($fila && password_verify($contrasenaPlano, $fila['contrasena'])) {
            return $fila;
        }
        return null;
    }
}
