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
}
