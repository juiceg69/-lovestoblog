<?php
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['idUser'])) {
    header('Location: ' . BASE_URL . '/login');
    exit();
}

require_once __DIR__ . "/../config/database.php";

// Add a new appointment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_cita'])) {
    $fecha_cita = filter_var($_POST['fecha_cita'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $motivo_cita = filter_var($_POST['motivo_cita'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $user_id = $_SESSION['idUser'];

    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("INSERT INTO citas (idUser, fecha_cita, motivo_cita) VALUES (:idUser, :fecha_cita, :motivo_cita)");
        $stmt->bindParam(':idUser', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':fecha_cita', $fecha_cita, PDO::PARAM_STR);
        $stmt->bindParam(':motivo_cita', $motivo_cita, PDO::PARAM_STR);
        $stmt->execute();

        $_SESSION['success'] = "Appointment added successfully.";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error adding appointment: " . $e->getMessage();
    }

    header('Location: ' . BASE_URL . '/appointments');
    exit();
}

// Delete an appointment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_cita'])) {
    $idCita = filter_var($_POST['idCita'], FILTER_SANITIZE_NUMBER_INT);
    $user_id = $_SESSION['idUser'];

    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch the appointment to check its date
        $stmt = $pdo->prepare("SELECT fecha_cita FROM citas WHERE idCita = :idCita AND idUser = :idUser");
        $stmt->bindParam(':idCita', $idCita, PDO::PARAM_INT);
        $stmt->bindParam(':idUser', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $cita = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cita) {
            // Check if the appointment date is in the past
            if (strtotime($cita['fecha_cita']) < strtotime(date('Y-m-d'))) {
                $_SESSION['error'] = "You cannot delete past appointments.";
            } else {
                // Delete the appointment
                $stmt = $pdo->prepare("DELETE FROM citas WHERE idCita = :idCita AND idUser = :idUser");
                $stmt->bindParam(':idCita', $idCita, PDO::PARAM_INT);
                $stmt->bindParam(':idUser', $user_id, PDO::PARAM_INT);
                $stmt->execute();

                $_SESSION['success'] = "Appointment deleted successfully.";
            }
        } else {
            $_SESSION['error'] = "Appointment not found or you do not have permission to delete it.";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error deleting appointment: " . $e->getMessage();
    }

    header('Location: ' . BASE_URL . '/appointments');
    exit();
}

// Edit an appointment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_cita'])) {
    $idCita = filter_var($_POST['idCita'], FILTER_SANITIZE_NUMBER_INT);
    $fecha_cita = filter_var($_POST['fecha_cita'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $motivo_cita = filter_var($_POST['motivo_cita'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $user_id = $_SESSION['idUser'];

    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch the appointment to check its date
        $stmt = $pdo->prepare("SELECT fecha_cita FROM citas WHERE idCita = :idCita AND idUser = :idUser");
        $stmt->bindParam(':idCita', $idCita, PDO::PARAM_INT);
        $stmt->bindParam(':idUser', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $cita = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cita) {
            // Check if the appointment date is in the past
            if (strtotime($cita['fecha_cita']) < strtotime(date('Y-m-d'))) {
                $_SESSION['error'] = "You cannot edit past appointments.";
            } else {
                // Update the appointment
                $stmt = $pdo->prepare("UPDATE citas SET fecha_cita = :fecha_cita, motivo_cita = :motivo_cita WHERE idCita = :idCita AND idUser = :idUser");
                $stmt->bindParam(':fecha_cita', $fecha_cita, PDO::PARAM_STR);
                $stmt->bindParam(':motivo_cita', $motivo_cita, PDO::PARAM_STR);
                $stmt->bindParam(':idCita', $idCita, PDO::PARAM_INT);
                $stmt->bindParam(':idUser', $user_id, PDO::PARAM_INT);
                $stmt->execute();

                $_SESSION['success'] = "Appointment updated successfully.";
            }
        } else {
            $_SESSION['error'] = "Appointment not found or you do not have permission to edit it.";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error updating appointment: " . $e->getMessage();
    }

    header('Location: ' . BASE_URL . '/appointments');
    exit();
}
?>