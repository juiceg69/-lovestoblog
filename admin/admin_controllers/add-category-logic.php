<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . "/../../public/config/database.php";

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ' . BASE_URL . '/login');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . ADMIN_URL . '/add-category.php');
    exit();
}

$idUser = $_POST['idUser'] ?? '';
$fecha_cita = $_POST['fecha_cita'] ?? '';
$motivo_cita = $_POST['motivo_cita'] ?? '';

if (empty($idUser) || empty($fecha_cita) || empty($motivo_cita)) {
    $_SESSION['add-appointment-error'] = "All fields are required.";
    header('Location: ' . ADMIN_URL . '/add-category.php');
    exit();
}

try {
    global $pdo;
    if (!$pdo) {
        $pdo = new PDO("mysql:host=localhost;dbname=khloe", "admin18", "admin18021978");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Verificar si el usuario existe (opcional, para integridad)
    $query = "SELECT idUser FROM users_data WHERE idUser = :idUser";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':idUser' => $idUser]);
    if (!$stmt->fetch()) {
        $_SESSION['add-appointment-error'] = "The user ID does not exists.";
        header('Location: ' . ADMIN_URL . '/add-category.php');
        exit();
    }

    // Insertar la cita
    $query = "INSERT INTO citas (idUser, fecha_cita, motivo_cita) VALUES (:idUser, :fecha_cita, :motivo_cita)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':idUser' => $idUser,
        ':fecha_cita' => $fecha_cita,
        ':motivo_cita' => $motivo_cita
    ]);

    // Confirmar que la inserción fue exitosa
    if ($stmt->rowCount() > 0) {
        $_SESSION['add-appointment-success'] = "Appointment successfully added.";
        header('Location: ' . ADMIN_URL . '/add-category.php'); // Redirige al formulario
        exit();
    } else {
        $_SESSION['add-appointment-error'] = "The appointment was not inserted. Try it again.";
        header('Location: ' . ADMIN_URL . '/add-category.php');
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['add-appointment-error'] = "Error adding appointment: " . $e->getMessage();
    header('Location: ' . ADMIN_URL . '/add-category.php');
    exit();
}
?>