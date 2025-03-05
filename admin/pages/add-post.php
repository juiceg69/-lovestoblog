<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include __DIR__ . "/../templates_adm/header.php";

// Verificar si el usuario es administrador
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ' . BASE_URL . '/login');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add News - Admin</title>
    <link rel="stylesheet" href="<?= SWEETALERT_CSS; ?>"> <!-- SweetAlert2 CSS -->
</head>
<body>
    <section class="form-signin" style="margin-top: 90px;">
        <div class="form-container">
            <a href="<?= ADMIN_URL ?>/index.php" class="text-center mb-4 d-block">
                <img src="<?= IMAGES_PATH ?>/logo.png" alt="Logo" class="logo-image img-fluid mx-auto d-block" style="max-width: 70px; height: auto;">
            </a>
            <h2 class="h3 mb-3 fw-normal">Add News</h2>

            <form method="POST" action="<?= ADMIN_URL ?>/../admin_controllers/add-post-logic.php" enctype="multipart/form-data">
                <div class="form-floating mb-2">
                    <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;" name="titulo" type="text" class="form-control w-100" id="floatingTitle" placeholder="Title" required>
                    <label for="floatingTitle">Title</label>
                </div>
                <div class="form-floating mb-2">
                    <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;" name="fecha" type="date" class="form-control w-100" id="floatingDate" required>
                    <label for="floatingDate">Date of Post</label>
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
                    <label for="floatingDescription"><strong style="color: #d3d1d1;">Text of the News</strong></label>
                </div>
                <div class="form_control mb-2">
                    <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;" type="file" name="imagen" id="imagen" accept="image/*" class="form-control" required>
                    <label style="color: #d3d1d1;" for="imagen" class="form-label">Add Thumbnail</label>
                </div>
                <button style="color: #d3d1d1;" class="btn btn-primary w-100 py-2" type="submit">Add Post</button>
                <div style="color: #d3d1d1;" class="my-3">
                    Back to <a href="<?= ADMIN_URL ?>/index.php" class="text-success fw-bold">Admin Panel</a>
                </div>
                <p class="mt-3 mb-3 text-muted">© <?php echo date("Y"); ?></p>
            </form>
        </div>
    </section>

    <!-- SweetAlert para mensajes -->
    <?php if (isset($_SESSION['add-post-success'])) : ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '<?= addslashes($_SESSION['add-post-success']) ?>',
                    confirmButtonText: 'OK'
                });
            });
        </script>
        <?php unset($_SESSION['add-post-success']); ?>
    <?php elseif (isset($_SESSION['add-post-error'])) : ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '<?= addslashes($_SESSION['add-post-error']) ?>',
                    confirmButtonText: 'OK'
                });
            });
        </script>
        <?php unset($_SESSION['add-post-error']); ?>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php include __DIR__ . "/../../public/templates/footer.php"; ?>



