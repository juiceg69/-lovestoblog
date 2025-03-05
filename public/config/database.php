<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/constants.php'; 

try {
    // Conectar a MySQL y seleccionar la base de datos
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    //echo "✅ Conexión a la base de datos '" . DB_NAME . "' establecida correctamente.\n";

} catch (PDOException $e) {
    die("❌ Error al conectar a MySQL: " . $e->getMessage());
}
?>