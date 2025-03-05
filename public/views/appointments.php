<?php
session_start();
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../templates/header.php";

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['idUser'])) {
    header('Location: ' . BASE_URL . '/login');
    exit();
}

$user_id = $_SESSION['idUser'];
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT * FROM citas WHERE idUser = :idUser AND fecha_cita >= CURDATE()");
    $stmt->bindParam(':idUser', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener las citas: " . $e->getMessage());
}
?>

    <div class="d-flex justify-content-center align-items-center" style="background: #02264a; margin-top: 7rem; min-height: 100vh;">
        <div class="card p-4 shadow" style="width: 100%; max-width: 600px; background: #0056b3;">
            <a href="<?= BASE_URL ?>" class="text-center mb-4 d-block">
                <img src="<?= IMAGES_PATH ?>/logo.png" alt="Logo" class="logo-image img-fluid mx-auto d-block" style="max-width: 70px; height: auto;">
            </a>
            <h2 style="color: #d3d1d1; text-align: center; margin-bottom: 2rem;">Appointments</h2>

            <form method="POST" action="<?= BASE_URL ?>/controllers/appointments-logic.php">
                <div class="form-floating mb-3">
                    <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;" name="fecha_cita" type="date" class="form-control w-100" id="floatingFecha" required>
                </div>
                <div class="form-floating mb-3">
                    <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;" name="motivo_cita" type="text" class="form-control w-100" id="floatingMotivo" placeholder="Reason" required>
                    <label style="color: #d3d1d1;" for="floatingMotivo">Reason</label>
                </div>
                <button type="submit" name="add_cita" class="btn btn-primary w-100 py-2 mb-3" style="background: #007bff; border: none;">Add Appointment</button>
            </form>

            <h2 style="color: #d3d1d1; text-align: center; margin-top: 2rem;">Scheduled Appointments</h2>
            <div style="flex: 1; overflow-y: auto;">
                <ul class="list-group" style="color: #d3d1d1;">
                    <?php foreach ($citas as $cita) : ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center" style="background: #003366; border: 1px solid #007bff; margin-bottom: 10px;">
                            <div>
                                <strong style="color: #d3d1d1;">Date:</strong>
                                <span style="color: #d3d1d1; margin-left: 7px;"><?= htmlspecialchars($cita['fecha_cita']) ?></span> |
                                <strong style="color: #d3d1d1;">Reason:</strong>
                                <span style="color: #d3d1d1; margin-left: 7px;"><?= htmlspecialchars($cita['motivo_cita']) ?></span>
                            </div>
                            <div>
                                <a href="<?= BASE_URL ?>/edit/edit-appointment.php?id=<?= $cita['idCita'] ?>" class="btn btn-warning btn-sm" style="background: #ffc107; border: none; margin-right: 5px;">Edit</a>
                                <form method="POST" action="<?= BASE_URL ?>/controllers/appointments-logic.php" style="display: inline;">
                                    <input type="hidden" name="idCita" value="<?= $cita['idCita'] ?>">
                                    <button type="submit" name="delete_cita" class="btn btn-danger btn-sm" style="background: #dc3545; border: none;">Delete</button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])) : ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '<?= addslashes($_SESSION['success']) ?>',
                    confirmButtonText: 'OK'
                });
            });
        </script>
        <?php unset($_SESSION['success']); ?>
    <?php elseif (isset($_SESSION['error'])) : ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '<?= addslashes($_SESSION['error']) ?>',
                    confirmButtonText: 'OK'
                });
            });
        </script>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php require_once __DIR__ . "/../templates/footer.php"; ?>
