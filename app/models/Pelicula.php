<?php
/**
 * Modelo Pelicula — Tabla `peliculas`
 */
class Pelicula
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /** Todas las películas, más recientes primero. */
    public function todas(): array
    {
        return $this->db->query(
            "SELECT * FROM peliculas ORDER BY creado_en DESC"
        )->fetchAll();
    }

    /** Las N películas más recientes (para el carrusel). */
    public function recientes(int $limite = 4): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM peliculas ORDER BY creado_en DESC LIMIT ?"
        );
        $stmt->bindValue(1, $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** Una película por su id. */
    public function porId(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM peliculas WHERE id = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch();
        return $fila ?: null;
    }

    /** Inserta una nueva película. */
    public function crear(string $titulo, string $descripcion, string $duracion,
                          string $genero, int $calificacion, string $imagen): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO peliculas (titulo, descripcion, duracion, genero, calificacion, imagen)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$titulo, $descripcion, $duracion, $genero, $calificacion, $imagen]);
    }

    /** Elimina una película por id. */
    public function eliminar(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM peliculas WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Actualiza una película. Si $imagen es '' se conserva la imagen existente.
     */
    public function actualizar(int $id, string $titulo, string $descripcion, string $duracion,
                               string $genero, int $calificacion, string $imagen): bool
    {
        if ($imagen !== '') {
            $stmt = $this->db->prepare(
                "UPDATE peliculas SET titulo=?, descripcion=?, duracion=?, genero=?, calificacion=?, imagen=? WHERE id=?"
            );
            return $stmt->execute([$titulo, $descripcion, $duracion, $genero, $calificacion, $imagen, $id]);
        }

        $stmt = $this->db->prepare(
            "UPDATE peliculas SET titulo=?, descripcion=?, duracion=?, genero=?, calificacion=? WHERE id=?"
        );
        return $stmt->execute([$titulo, $descripcion, $duracion, $genero, $calificacion, $id]);
    }
}
