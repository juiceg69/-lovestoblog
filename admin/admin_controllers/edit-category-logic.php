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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que los campos del formulario estén presentes
    if (!isset($_POST['idCita']) || !isset($_POST['fecha_cita']) || !isset($_POST['motivo_cita'])) {
        $_SESSION['edit-appointment-error'] = "Missing form data.";
        header('Location: ' . ADMIN_URL . '/manage-categories.php');
        exit();
    }

    // Sanitizar los datos del formulario
    $idCita = filter_var($_POST['idCita'], FILTER_SANITIZE_NUMBER_INT);
    $fecha_cita = filter_var($_POST['fecha_cita'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $motivo_cita = filter_var($_POST['motivo_cita'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Validar los campos
    if (empty($idCita)) {
        $_SESSION['edit-appointment-error'] = "Invalid appointment ID.";
    } elseif (empty($fecha_cita)) {
        $_SESSION['edit-appointment-error'] = "Please select an appointment date.";
    } elseif (empty($motivo_cita)) {
        $_SESSION['edit-appointment-error'] = "Please enter a reason for the appointment.";
    } else {
        try {
            // Crear conexión PDO
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Actualizar la cita en la tabla `citas`
            $query = "UPDATE citas SET fecha_cita = :fecha_cita, motivo_cita = :motivo_cita WHERE idCita = :idCita";
            $stmt = $pdo->prepare($query);

            // Vincular los parámetros
            $stmt->bindParam(':fecha_cita', $fecha_cita, PDO::PARAM_STR);
            $stmt->bindParam(':motivo_cita', $motivo_cita, PDO::PARAM_STR);
            $stmt->bindParam(':idCita', $idCita, PDO::PARAM_INT);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                $_SESSION['edit-appointment-success'] = "Appointment updated successfully.";
            } else {
                $_SESSION['edit-appointment-error'] = "Failed to update the appointment.";
            }
        } catch (PDOException $e) {
            // Manejar errores de la base de datos
            $_SESSION['edit-appointment-error'] = "Database error: " . $e->getMessage();
        }
    }
} else {
    // Si no es una solicitud POST, redirigir con un mensaje de error
    $_SESSION['edit-appointment-error'] = "Invalid request method.";
}

// Redirigir de vuelta a la página de gestión de citas
header('Location: ' . ADMIN_URL . '/manage-categories.php');
exit();
?>