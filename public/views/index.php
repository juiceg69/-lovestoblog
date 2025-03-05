<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../config/database.php";
include_once __DIR__ . "/../templates/header.php";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=khloe", "admin18", "admin18021978");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener solo noticias de administradores
    $query = "SELECT noticias.*, users_data.name, users_data.last_name 
              FROM noticias 
              LEFT JOIN users_data ON noticias.idUser = users_data.idUser 
              WHERE users_data.is_admin = 1 
              ORDER BY noticias.fecha DESC";
    $stmt = $pdo->query($query);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener las noticias: " . $e->getMessage());
}

// Mensaje de éxito (sin cambios)
if (isset($_SESSION['login-success'])) {
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '" . $_SESSION['login-success'] . "',
                    confirmButtonText: 'OK'
                });
            });
          </script>";
    unset($_SESSION['login-success']);
}
?>

    <!-- Carousel -->
    <div class="container-fluid mt-4">
        <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active" style="background-image: url('<?= IMAGES_PATH?>/japan.jpg');">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Man</h5>
                        <p>My slide caption text</p>
                    </div>
                </div>
                <div class="carousel-item" style="background-image: url('<?= IMAGES_PATH?>/cyberpunk.jpg');">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Cyber Punk Tokio</h5>
                        <p>My slide caption text</p>
                    </div>
                </div>
                <div class="carousel-item" style="background-image: url('<?= IMAGES_PATH?>/warehouse.jpg');">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>warehouse</h5>
                        <p>My slide caption text</p>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    <!-- END SLIDERS -->

<section class="posts">
    <div class="container posts__container">
        <?php foreach ($posts as $post): ?>
            <article class="post">
                <div class="post__thumbnail">
                    <img src="<?= BASE_URL ?>/assets/images/<?= htmlspecialchars($post['imagen']) ?>" alt="<?= htmlspecialchars($post['titulo']) ?>">
                </div>
                <div class="post__info">
                    <a href="#" class="category__button">News</a>
                    <h3 class="post__title">
                        <a href="<?= BASE_URL ?>/views/post.php?id=<?= $post['idNoticia'] ?>"><?= htmlspecialchars($post['titulo']) ?></a>
                    </h3>
                    <p class="post__body">
                        <?= htmlspecialchars(substr($post['texto'], 0, 150)) . '...' ?>
                    </p>
                    <div class="post__author">
                        <div class="post__author-avatar">
                            <img src="<?= IMAGES_PATH ?>/avatar-default.jpg" alt="avatar">
                        </div>
                        <div class="post__author-info">
                            <h5>By: <?= htmlspecialchars($post['name'] . ' ' . $post['last_name'] ?? 'Unknown') ?></h5>
                            <small><?= date('F j, Y - H:i', strtotime($post['fecha'])) ?></small>
                        </div>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php include_once __DIR__ . "/../templates/footer.php"; ?>
