<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../config_adm/dataBase.php";
include_once __DIR__ . "/../templates_adm/header.php";

// Obtener los datos de la cita si se proporciona un ID
$cita = [];

if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    try {
        // Crear conexión PDO
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Obtener los datos de la cita
        $query = "SELECT * FROM citas WHERE idCita = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $cita = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cita) {
            $_SESSION['error'] = "Appointment not found.";
            header('Location: ' . ADMIN_URL . '/manage-categories.php');
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header('Location: ' . ADMIN_URL . '/manage-categories.php');
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid appointment ID.";
    header('Location: ' . ADMIN_URL . '/manage-categories.php');
    exit();
}
?>

<section class="form-signin" style="margin-top: 90px;">
    <div class="form-container">
        <a href="<?= ADMIN_URL ?>/index.php" class="text-center mb-4 d-block">
            <img src="<?= IMAGES_PATH ?>/logo.png" alt="Logo" class="logo-image img-fluid mx-auto d-block"
            style="max-width: 70px; height: auto;">
        </a>
        <h2 class="h3 mb-3 fw-normal">Edit Appointment</h2>

        <!-- Formulario de edición -->
        <form method="POST" action="<?= ADMIN_URL ?>/../admin_controllers/edit-category-logic.php">
            <input type="hidden" name="idCita" value="<?= $cita['idCita'] ?>">

            <div class="form-floating mb-2">
                <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;"
                name="fecha_cita" type="date" class="form-control w-100" id="floatingDate" placeholder="Appointment Date" 
                value="<?= htmlspecialchars($cita['fecha_cita']) ?>" required>
                <label for="floatingDate">Appointment Date</label>
            </div>

            <div class="form-floating mb-2">
                <textarea style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;"
                name="motivo_cita" class="form-control w-100" id="floatingReason" placeholder="Reason for Appointment" required><?= htmlspecialchars($cita['motivo_cita']) ?></textarea>
                <label for="floatingReason">Reason for Appointment</label>
            </div>

            <div class="my-3">
                Back to <a href="<?= ADMIN_URL ?>/index.php" class="text-success fw-bold">Admin Panel</a>
            </div>

            <button class="btn btn-primary w-100 py-2" type="submit">Update Appointment</button>
            <p class="mt-3 mb-3 text-muted">&copy; <?php echo date("Y"); ?></p>
        </form>
    </div>
</section>

    <!-- SweetAlert para mostrar mensajes de éxito o error -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        <?php if (isset($_SESSION['edit-appointment-success'])) : ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?= $_SESSION['edit-appointment-success'] ?>',
                confirmButtonText: 'OK'
            });
            <?php unset($_SESSION['edit-appointment-success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['edit-appointment-error'])) : ?>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?= $_SESSION['edit-appointment-error'] ?>',
                confirmButtonText: 'OK'
            });
            <?php unset($_SESSION['edit-appointment-error']); ?>
        <?php endif; ?>
    </script>

<?php
include_once __DIR__ . "/../../public/templates/footer.php";
?>