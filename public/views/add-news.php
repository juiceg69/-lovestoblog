<?php
session_start();
include_once __DIR__ . "/../templates/header.php";
require_once __DIR__ . "/../config/database.php";

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['idUser'])) {
    header('Location: ' . BASE_URL . '/login');
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

    <section class="form-signin" style="margin-top: 90px;">
        <div class="form-container">
            <a href="<?= BASE_URL ?>" class="text-center mb-4 d-block">
                <img src="<?= IMAGES_PATH ?>/logo.png" alt="Logo" class="logo-image img-fluid mx-auto d-block" style="max-width: 70px; height: auto;">
            </a>
            <h2 class="h3 mb-3 fw-normal">Post News</h2>

            <form action="<?= BASE_URL ?>/controllers/news-logic.php" method="POST" enctype="multipart/form-data">
                <div class="form-floating mb-2">
                    <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;" name="titulo" type="text" class="form-control w-100" id="floatingTitle" placeholder="Título de la noticia" required>
                    <label style="color: #d3d1d1;" for="floatingTitle"><strong>Title of the News</strong></label>
                </div>
                <div class="form-floating mb-2">
                    <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;" name="fecha" type="date" class="form-control w-100" id="floatingDate" placeholder=" (Insert Date Here)" required>
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
                    <label for="thumbnail" class="form-label"><strong style="color: #d3d1d1;">Image of the News</strong></label>
                </div>
                <button class="btn btn-primary w-100 py-2" name="submit" type="submit"><strong>Post News</strong></button>
                <div class="my-3">
                    <strong style="color: #d3d1d1;">Back to</strong> 
                    <a href="<?= BASE_URL ?>" class="text-success fw-bold"><strong>Home Page</strong></a>
                </div>
                <p class="mt-3 mb-3 text-muted">© <span><?php echo date("Y"); ?></span></p>
            </form>
        </div>
    </section>

    <?php if (isset($_SESSION['add-news-success'])) : ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '<?= addslashes($_SESSION['add-news-success']) ?>',
                    confirmButtonText: 'OK'
                });
            });
        </script>
        <?php unset($_SESSION['add-news-success']); ?>
    <?php elseif (isset($_SESSION['add-news-error'])) : ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '<?= addslashes($_SESSION['add-news-error']) ?>',
                    confirmButtonText: 'OK'
                });
            });
        </script>
        <?php unset($_SESSION['add-news-error']); ?>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php include_once __DIR__ . "/../templates/footer.php"; ?>
