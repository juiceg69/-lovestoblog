<?php
ini_set('display_errors', 0); // Desactiva errores en pantalla en producción
ini_set('log_errors', 1);     // Habilita el registro de errores
ini_set('error_log', __DIR__ . '/error.log'); // Guarda errores en un archivo
error_reporting(E_ALL);

// Sanitiza y valida el parámetro url
$page_name = isset($_GET['url']) ? basename($_GET['url']) : 'index';

// Asegúrate de que solo se permitan nombres de archivo válidos
$page_name = preg_replace('/[^a-zA-Z0-9\-]/', '', $page_name);
$page_name = $page_name ? $page_name : 'index';

// Construye la ruta
$filename = __DIR__ . "/views/" . $page_name . ".php";

if (file_exists($filename) && is_readable($filename)) {
    require_once $filename; // Ejecuta el archivo directamente
} else {
    $error_filename = __DIR__ . "/views/404.php";
    if (file_exists($error_filename) && is_readable($error_filename)) {
        require_once $error_filename;
    } else {
        http_response_code(500);
    }
}
