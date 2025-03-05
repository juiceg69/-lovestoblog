<?php
require_once __DIR__ . '/../../public/config/constants.php'; 

try {
    // Conectar a MySQL sin seleccionar una base de datos
    $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Crear la base de datos si no existe
    $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
    $pdo->exec($sql);
    
    //echo "✅ Base de datos '" . DB_NAME . "' creada correctamente.\n";

} catch (PDOException $e) {
    die("❌ Error al conectar a MySQL: " . $e->getMessage());
}