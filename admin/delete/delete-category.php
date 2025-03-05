<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si el usuario está logueado
if (!isset($_SESSION['idUser'])) {
    $_SESSION['unauthorized'] = "You must be logged in to access this page.";
    header('Location: ' . BASE_URL . 'login.php');
    exit();
}

require_once __DIR__ . "/../config_adm/dataBase.php";

if (isset($_GET['id'])) {
    $idCita = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    try {
        // Crear conexión PDO
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Verificar que la cita no se haya realizado (fecha futura)
        $query = "SELECT fecha_cita FROM citas WHERE idCita = :idCita";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':idCita', $idCita, PDO::PARAM_INT);
        $stmt->execute();
        $cita = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cita && $cita['fecha_cita'] >= date('Y-m-d')) {
            // Borrar la cita
            $query = "DELETE FROM citas WHERE idCita = :idCita";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':idCita', $idCita, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $_SESSION['delete-appointment-success'] = "Appointment deleted successfully.";
            } else {
                $_SESSION['delete-appointment-error'] = "Failed to delete the appointment.";
            }
        } else {
            $_SESSION['delete-appointment-error'] = "You can only delete appointments with future dates.";
        }
    } catch (PDOException $e) {
        // Manejar errores de la base de datos
        $_SESSION['delete-appointment-error'] = "Database error: " . $e->getMessage();
    }
} else {
    $_SESSION['delete-appointment-error'] = "Invalid appointment ID.";
}

// Redirigir de vuelta a la página de gestión de citas
header('Location: ' . ADMIN_URL . '/manage-categories.php');
exit();
?>