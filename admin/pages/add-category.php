<?php
require_once __DIR__ . "/../config_adm/dataBase.php";
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ' . BASE_URL . '/login');
    exit();
}

include_once __DIR__ . "/../templates_adm/header.php";
?>

<section class="form-signin" style="margin-top: 90px;">
    <div class="form-container">
        <a href="<?= ADMIN_URL ?>/index.php" class="text-center mb-4 d-block">
            <img src="<?= IMAGES_PATH ?>/logo.png" alt="Logo" class="logo-image img-fluid mx-auto d-block"
                 style="max-width: 70px; height: auto;">
        </a>
        <h2 class="h3 mb-3 fw-normal">Add Appointment</h2>

        <form method="POST" action="<?= ADMIN_URL ?>/../admin_controllers/add-category-logic.php" enctype="multipart/form-data">
            <div class="form-floating mb-2">
                <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;"
                       name="idUser" type="number" class="form-control w-100" id="floatingUserId" placeholder="User ID" required>
                <label style="color: #d3d1d1;" for="floatingUserId">User ID</label>
            </div>

            <div class="form-floating mb-2">
                <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;"
                       name="fecha_cita" type="date" class="form-control w-100" id="floatingDate" placeholder="Appointment Date" required>
                <!--<label style="color: #d3d1d1;" for="floatingDate">Appointment Date</label>-->
                <label for="floatingDescription"><strong >Appointment Date</strong></label>
            </div>

            <div class="form-floating mb-2">
                    <textarea 
                        style="color: #d3d1d1; background: #003366; border: 1px solid #007bff; width: 100%; height: 200px; resize: vertical;" 
                        name="texto" 
                        id="texto" 
                        class="form-control" 
                        placeholder="Texto de la noticia..." 
                        rows="10" 
                        required
                    ></textarea>
                    <label for="floatingDescription"><strong style="color: #d3d1d1;">Reason for the appointment</strong></label>
                </div>

            <div style="color: #d3d1d1;" class="my-2">
                Back to <a href="<?= ADMIN_URL ?>/index.php" class="text-success fw-bold">Admin Panel</a>
            </div>

            <button style="color: #d3d1d1;" class="btn btn-primary w-100 py-2" type="submit">
                Add Appointment
            </button>

            <p class="mt-3 mb-3 text-muted">© <?php echo date("Y"); ?></p>
        </form>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (isset($_SESSION['add-appointment-success'])): ?>
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '<?= addslashes($_SESSION['add-appointment-success']) ?>',
                confirmButtonText: 'OK'
            });
            <?php unset($_SESSION['add-appointment-success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['add-appointment-error'])): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?= addslashes($_SESSION['add-appointment-error']) ?>',
                confirmButtonText: 'OK'
            });
            <?php unset($_SESSION['add-appointment-error']); ?>
        <?php endif; ?>
    });
</script>

<?php include_once __DIR__ . "/../../public/templates/footer.php"; ?>
