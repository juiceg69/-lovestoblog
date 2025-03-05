<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../config_adm/dataBase.php";

// Verificar si el usuario es administrador
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ' . BASE_URL . '/login');
    exit();
}

// Verificar si se proporcionó un ID de usuario
if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    try {
        // Crear conexión PDO
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Iniciar una transacción para asegurar la atomicidad
        $pdo->beginTransaction();

        // 1. Eliminar el usuario de la tabla `users_login` (dependiente)
        $query = "DELETE FROM users_login WHERE idUser = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // 2. Eliminar el usuario de la tabla `users_data` (principal)
        $query = "DELETE FROM users_data WHERE idUser = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Confirmar la transacción
        $pdo->commit();

        // Establecer el mensaje de éxito
        $_SESSION['delete-user-success'] = "User deleted successfully.";
    } catch (PDOException $e) {
        // Revertir la transacción en caso de error
        if (isset($pdo)) {
            $pdo->rollBack();
        }

        // Establecer el mensaje de error
        $_SESSION['delete-user-error'] = "Error deleting user: " . $e->getMessage();
    }
} else {
    // Si no se proporcionó un ID, establecer un mensaje de error
    $_SESSION['delete-user-error'] = "User ID not provided.";
}

// Redirigir de vuelta a la página de gestión de usuarios
header('Location: ' . ADMIN_URL . '/manage-users.php');
exit();
?>