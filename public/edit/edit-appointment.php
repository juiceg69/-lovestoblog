<?php
session_start();
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../templates/header.php";

// Depuración: Verificar la sesión
/*echo "<pre>";
print_r($_SESSION);
echo "</pre>";*/

// Verificar si el usuario está logueado
if (!isset($_SESSION['idUser'])) {
    die("User not logged in. Redirecting to login...");
    header('Location: ' . BASE_URL . '/login');
    exit();
}

// Depuración: Verificar el ID de la cita
if (!isset($_GET['id'])) {
    die("Appointment ID not provideded. Redirecting to appointments...");
    header('Location: ' . BASE_URL . '/appointments');
    exit();
}

$idCita = $_GET['id'];
$user_id = $_SESSION['idUser'];

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener los datos de la cita
    $stmt = $pdo->prepare("SELECT * FROM citas WHERE idCita = :idCita AND idUser = :idUser");
    $stmt->bindParam(':idCita', $idCita);
    $stmt->bindParam(':idUser', $user_id);
    $stmt->execute();
    $cita = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cita) {
        die("Appointment not found or does not belong to user. Redirecting to appointments...");
        $_SESSION['error'] = "Appointment not found.";
        header('Location: ' . BASE_URL . '/appointments');
        exit();
    }
} catch (PDOException $e) {
    die("Error to get the appointment: " . $e->getMessage());
}
?>

<!-- Resto del código HTML/PHP -->

<div class="d-flex justify-content-center align-items-center" style="background: #02264a; margin-top: 7rem; min-height: 100vh;">
    <div class="card p-4 shadow" style="width: 100%; max-width: 600px; background: #0056b3;">
        <!-- Logo -->
        <a href="<?= BASE_URL ?>" class="text-center mb-4 d-block">
            <img src="<?= IMAGES_PATH ?>/logo.png" alt="Logo" class="logo-image img-fluid mx-auto d-block"
                style="max-width: 70px; height: auto;">
        </a>
        <h2 style="color: #d3d1d1; text-align: center; margin-bottom: 2rem;">Edit Appointment</h2>

        <!-- Mostrar mensajes de éxito o error -->
        <?php if (isset($_SESSION['error'])) : ?>
            <div class="alert alert-danger text-center">
                <p><?= $_SESSION['error'] ?></p>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Formulario para editar la cita -->
        <form method="POST" action="<?= BASE_URL ?>/controllers/appointments-logic.php">
            <input type="hidden" name="idCita" value="<?= $cita['idCita'] ?>">
            <div class="form-floating mb-3">
                <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;" name="fecha_cita" type="date" class="form-control w-100" id="floatingFecha" value="<?= htmlspecialchars($cita['fecha_cita']) ?>" required>
            </div>
            <div class="form-floating mb-3">
                <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;" name="motivo_cita" type="text" class="form-control w-100" id="floatingMotivo" placeholder="Reason" value="<?= htmlspecialchars($cita['motivo_cita']) ?>" required>
            </div>
            <button type="submit" name="edit_cita" class="btn btn-primary w-100 py-2 mb-3" style="background: #007bff; border: none;">Update Appointment</button>
        </form>
    </div>
</div>

<?php
require_once __DIR__ . "/../templates/footer.php";
?>