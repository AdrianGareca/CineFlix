<?php
// Configuración de conexión a la base de datos
$host     = 'localhost';
$dbname   = 'cineboom';
$usuario  = 'root';
$contrasena = '';  // En XAMPP por defecto es vacía

$conn = new mysqli($host, $usuario, $contrasena, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
