<?php
// Habilitar la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar la sesión
//session_start();

// Incluir la configuración de la base de datos
require_once __DIR__ . '/config/database.php';

// Recolecta el valor de 'url' o asigna 'home' por defecto
$url = $_GET['url'] ?? 'index'; // Asignar 'home' si 'url' no está definida
$url = strtolower($url); // Convertir la URL a minúsculas
$url = explode("/", $url); // Dividir la URL en un array basado en "/"

// Determinar si la solicitud es para la parte de administración
$is_admin = ($url[0] === 'admin');

if ($is_admin) {
    // Eliminar 'admin' del array de la URL
    array_shift($url);

    // Asignar 'dashboard' como página por defecto para la parte de administración
    $page_name = trim($url[0] ?? 'dashboard');

    // La ruta al archivo PHP en la carpeta admin
    $filename = __DIR__ . "../pages/admin/" . $page_name . ".php";

    // Verificar si el usuario ha iniciado sesión
    if (!isset($_SESSION['user_id'])) {
        header('Location: login'); // Redirigir al login si no está autenticado
        exit();
    }
} else {
    // Asignar 'home' como página por defecto para la parte pública
    $page_name = trim($url[0]);

    // La ruta al archivo PHP en la carpeta views
    $filename = __DIR__ . "/views/" . $page_name . ".php";
}

// Verifica si el archivo existe y lo incluye
if (file_exists($filename)) {
    require_once $filename;
} else {
    // Si no existe, carga la página de error 404
    require_once __DIR__ . "/views/404.php";
}
?>