<?php
session_start();
include_once __DIR__ . "/../templates/header.php";

require_once __DIR__ . "/../config/database.php";

// Obtener el término de búsqueda si existe
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

try {
    // Consulta para obtener noticias de usuarios normales con filtro de búsqueda
    $sql = "
        SELECT n.idNoticia, n.titulo, n.imagen, n.texto, n.fecha, u.name 
        FROM noticias n 
        JOIN users_data u ON n.idUser = u.idUser 
        WHERE u.is_admin = 0 
        AND (n.titulo LIKE :search OR n.texto LIKE :search)
        ORDER BY n.fecha DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':search' => "%$search_term%"]);
    $noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("❌ Error al obtener las noticias: " . $e->getMessage());
}
?>

<!-- Barra de búsqueda -->
<div class="search__bar py-4">
    <form action="<?= BASE_URL ?>/blog" method="GET" class="container d-flex justify-content-center">
        <div class="input-group" style="width: 24rem;">
            <span class="input-group-text bg-white">
                <i class="uil uil-search"></i>
            </span>
            <input type="text" name="search" class="form-control" placeholder="Search..." aria-label="Search" value="<?= htmlspecialchars($search_term) ?>">
            <button type="submit" class="btn btn-primary">Go</button>
        </div>
    </form>
</div>

<!-- Sección de noticias -->
<section class="posts">
    <div class="container posts__container">
        <?php if (!empty($noticias)): ?>
            <?php foreach ($noticias as $noticia): ?>
                <article class="post">
                    <div class="post__thumbnail">
                        <img src="<?= BASE_URL ?>/assets/images/<?= $noticia['imagen']; ?>" alt="<?= $noticia['titulo']; ?>">
                    </div>
                    <div class="post__info">
                        <a href="#" class="category__button">Categoría</a>
                        <h3 class="post__title">
                            <a href="<?= BASE_URL ?>/post/<?= $noticia['idNoticia']; ?>"><?= $noticia['titulo']; ?></a>
                        </h3>
                        <p class="post__body">
                            <?= substr($noticia['texto'], 0, 150); ?>...
                        </p>
                        <div class="post__author">
                            <div class="post__author-avatar">
                                <!-- <img src="<?= IMAGES_PATH ?>/avatar3.jpg" alt="Avatar"> -->
                            </div>
                            <div class="post__author-info">
                                <h5>By: <?= $noticia['name']; ?></h5>
                                <small><?= date('F j, Y', strtotime($noticia['fecha'])); ?></small>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay noticias disponibles para esa búsqueda.</p>
        <?php endif; ?>
    </div>
</section>

<?php
include_once __DIR__ . "/../templates/footer.php";
?>
