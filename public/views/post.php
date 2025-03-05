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

    $idNoticia = $_GET['id'] ?? null;
    if (!$idNoticia || !is_numeric($idNoticia)) {
        header('Location: ' . BASE_URL . '/public/views/index.php');
        exit();
    }

    $query = "SELECT noticias.*, users_data.name, users_data.last_name 
              FROM noticias 
              LEFT JOIN users_data ON noticias.idUser = users_data.idUser 
              WHERE noticias.idNoticia = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':id' => $idNoticia]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        header('Location: ' . BASE_URL . '/public/views/index.php');
        exit();
    }
} catch (PDOException $e) {
    die("Error al obtener la noticia: " . $e->getMessage());
}
?>

    <!-- SINGLE POST -->
    <section class="singlepost">
        <div class="container singlepost__container">
            <h2><?= htmlspecialchars($post['titulo']) ?></h2>
            <div class="post__author">
                <div class="post__author-avatar">
                    <img src="<?= IMAGES_PATH ?>/avatar-default.jpg" alt="avatar">
                </div>
                <div class="post__author-info">
                    <h5 style="color: #d3d1d1; font-size: 1rem; margin: 0;">By: <?= htmlspecialchars($post['name'] . ' ' . $post['last_name'] ?? 'Unknown') ?></h5>
                    <small><?= date('F j, Y - H:i', strtotime($post['fecha'])) ?></small>
                </div>
            </div>
            <div class="singlepost__thumbnail">
                <img src="<?= BASE_URL ?>/assets/images/<?= htmlspecialchars($post['imagen']) ?>" alt="<?= htmlspecialchars($post['titulo']) ?>">
            </div>
            <p><?= nl2br(htmlspecialchars($post['texto'])) ?></p>
            <div class="d-flex justify-content-center mt-3">
                <a href="<?= BASE_URL ?>/views/index.php" class="btn btn-primary">Back to Home</a>
            </div>
        </div>
    </section>
    <!-- END OF SINGLE POST -->

<?php
include_once __DIR__ . "/../templates/footer.php";
?>