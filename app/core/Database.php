<?php
/**
 * Database — Conexión PDO singleton a MySQL (XAMPP)
 * ---------------------------------------------------
 * Devuelve siempre la MISMA instancia de PDO para toda la app.
 * Uso:  $pdo = Database::getConnection();
 */
class Database
{
    private static ?PDO $instancia = null;

    private function __construct() {}   // No instanciable

    public static function getConnection(): PDO
    {
        if (self::$instancia === null) {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

            $opciones = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,      // Lanza excepciones
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,           // Arreglos asociativos
                PDO::ATTR_EMULATE_PREPARES   => false,                     // Consultas preparadas reales
            ];

            try {
                self::$instancia = new PDO($dsn, DB_USER, DB_PASS, $opciones);
            } catch (PDOException $e) {
                die('Error de conexión a la base de datos: ' . $e->getMessage());
            }
        }

        return self::$instancia;
    }
}
