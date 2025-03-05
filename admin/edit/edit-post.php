<?php
session_start();
require_once __DIR__ . "/../../public/config/database.php";

// Verificar si el usuario es administrador
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ' . BASE_URL . '/login');
    exit();
}

// Obtener el ID de la noticia a editar
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: ' . ADMIN_URL . '/index.php');
    exit();
}

$idNoticia = $_GET['id'];

try {
    global $pdo;
    if (!$pdo) {
        die("The DataBase connection is not defined in the dataBase.php");
    }

    // Obtener la noticia de la base de datos
    $query = "SELECT * FROM noticias WHERE idNoticia = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':id' => $idNoticia]);
    $noticia = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$noticia) {
        header('Location: ' . ADMIN_URL . '/index.php');
        exit();
    }
} catch (PDOException $e) {
    die("Error getting the news: " . $e->getMessage());
}

include __DIR__ . "/../templates_adm/header.php";
?>

<section class="form-signin" style="margin-top: 90px;">
    <div class="form-container">
        <a href="<?= ADMIN_URL ?>/index.php" class="text-center mb-4 d-block">
            <img src="<?= IMAGES_PATH ?>/logo.png" alt="Logo" class="logo-image img-fluid mx-auto d-block" style="max-width: 70px; height: auto;">
        </a>
        <h2 class="h3 mb-3 fw-normal">Edit News</h2>

        <!-- Mostrar mensajes de SweetAlert -->
        <?php if (isset($_SESSION['edit-post-success'])): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: '<?= addslashes($_SESSION['edit-post-success']) ?>',
                        confirmButtonText: 'OK'
                    });
                });
            </script>
            <?php unset($_SESSION['edit-post-success']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['edit-post-error'])): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: '<?= addslashes($_SESSION['edit-post-error']) ?>',
                        confirmButtonText: 'OK'
                    });
                });
            </script>
            <?php unset($_SESSION['edit-post-error']); ?>
        <?php endif; ?>

        <form method="POST" action="<?= ADMIN_URL ?>>/../admin_controllers/edit-post-logic.php"  enctype="multipart/form-data">
            <input type="hidden" name="idNoticia" value="<?= $noticia['idNoticia'] ?>">

            <!-- Título -->
            <div class="form-floating mb-2">
                <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;" name="titulo" type="text" class="form-control w-100" id="floatingTitle" placeholder="Title" value="<?= htmlspecialchars($noticia['titulo']) ?>" required>
                <label for="floatingTitle">Title</label>
            </div>

            <!-- Fecha -->
            <div class="form-floating mb-2">
                <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;" name="fecha" type="date" class="form-control w-100" id="floatingDate" value="<?= $noticia['fecha'] ?>" required>
                <label for="floatingDate">Date of Post</label>
            </div>

            <!-- Contenido -->
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
                <label for="floatingDescription"><strong style="color: #d3d1d1;">Description</strong></label>
            </div>

            <!-- Imagen -->
            <div class="form_control mb-2">
                <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;" type="file" name="imagen" id="imagen" accept="image/*" class="form-control">
                <label style="color: #d3d1d1;" for="imagen" class="form-label">Change Thumbnail (leave empty to keep current)</label>
            </div>

            <!-- Botón de envío -->
            <button style="color: #d3d1d1;" class="btn btn-primary w-100 py-2" type="submit">Update Post</button>
        </form>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
include __DIR__ . "/../../public/templates/footer.php";
?>