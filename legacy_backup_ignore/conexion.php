<?php
// Compatibilidad: la conexión ahora vive en app/core/Database.php (PDO).
require_once __DIR__."/app/config/config.php";
require_once __DIR__."/app/core/Database.php";
$pdo = Database::getConnection();
