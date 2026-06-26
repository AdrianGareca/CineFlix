<?php
/**
 * Modelo Confiteria — Tabla `confiteria`
 */
class Confiteria
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /** Todos los productos (orden de creación ascendente para la tienda). */
    public function todas(string $orden = 'ASC'): array
    {
        $orden = strtoupper($orden) === 'DESC' ? 'DESC' : 'ASC';
        return $this->db->query(
            "SELECT * FROM confiteria ORDER BY creado_en $orden"
        )->fetchAll();
    }

    /** Inserta un nuevo producto. */
    public function crear(string $titulo, string $descripcion, float $precio, string $imagen): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO confiteria (titulo, descripcion, precio, imagen)
             VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$titulo, $descripcion, $precio, $imagen]);
    }

    /** Elimina un producto por id. */
    public function eliminar(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM confiteria WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /** Un producto por su id. */
    public function porId(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM confiteria WHERE id = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch();
        return $fila ?: null;
    }

    /**
     * Actualiza un producto. Si $imagen es '' se conserva la imagen existente.
     */
    public function actualizar(int $id, string $titulo, string $descripcion, float $precio, string $imagen): bool
    {
        if ($imagen !== '') {
            $stmt = $this->db->prepare(
                "UPDATE confiteria SET titulo=?, descripcion=?, precio=?, imagen=? WHERE id=?"
            );
            return $stmt->execute([$titulo, $descripcion, $precio, $imagen, $id]);
        }

        $stmt = $this->db->prepare(
            "UPDATE confiteria SET titulo=?, descripcion=?, precio=? WHERE id=?"
        );
        return $stmt->execute([$titulo, $descripcion, $precio, $id]);
    }
}
