<?php
require_once __DIR__ . "/../config_adm/dataBase.php";
// Habilitar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión
session_start();

// Verificar si el usuario es administrador
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ' . BASE_URL . '/login');
    exit();
}

try {
     // Crear conexión PDO
     $pdo = new PDO("mysql:host=localhost;dbname=khloe", "admin18", "admin18021978");
     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener todas las noticias de la base de datos
    $query = "SELECT * FROM noticias ORDER BY fecha DESC";
    $stmt = $pdo->query($query);

    // Obtener los resultados como un array asociativo
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener las noticias: " . $e->getMessage());
}

// Incluir el header
include_once __DIR__ . "/../templates_adm/header.php";
?>

<section class="dashboard">
    <div class="container dashboard__container">
        <button id="show__sidebar-btn" class="sidebar__toggle">
            <i class="uil uil-angle-right-b"></i>
        </button>
        <button id="hide__sidebar-btn" class="sidebar__toggle" style="display: none;">
            <i class="uil uil-angle-left-b"></i>
        </button>
        <aside>
            <ul>
                <li>
                    <a href="<?= ADMIN_URL ?>/add-post.php">
                        <i class="uil uil-pen"></i>
                        <h5>Add News</h5>
                    </a>
                </li>
                <li>
                    <a href="<?= ADMIN_URL ?>/index.php" class="active">
                        <i class="uil uil-postcard"></i>
                        <h5>Manage News</h5>
                    </a>
                </li>
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) : ?>
                    <li>
                        <a href="<?= ADMIN_URL ?>/add-user.php">
                            <i class="uil uil-user-plus"></i>
                            <h5>Add User</h5>
                        </a>
                    </li>
                    <li>
                        <a href="<?= ADMIN_URL ?>/manage-users.php">
                            <i class="uil uil-users-alt"></i>
                            <h5>Manage Users</h5>
                        </a>
                    </li>
                    <li>
                        <a href="<?= ADMIN_URL ?>/add-category.php">
                            <i class="uil uil-edit"></i>
                            <h5>Add Appointment</h5>
                        </a>
                    </li>
                    <li>
                        <a href="<?= ADMIN_URL ?>/manage-categories.php">
                            <i class="uil uil-list-ul"></i>
                            <h5>Manage Appointments</h5>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </aside>

        <main>
        <h2 style="color: #d3d1d1;">Dashboard</h2>
        
            <!-- Mostrar mensajes de SweetAlert -->
            <?php if (isset($_SESSION['add-post-success'])): ?>
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
            <?php endif; ?>
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
            <?php if (isset($_SESSION['delete-post-success'])): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: '<?= addslashes($_SESSION['delete-post-success']) ?>',
                            confirmButtonText: 'OK'
                        });
                    });
                </script>
                <?php unset($_SESSION['delete-post-success']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['add-post-error']) || isset($_SESSION['edit-post-error']) || isset($_SESSION['delete-post-error'])): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: '<?= addslashes($_SESSION['add-post-error'] ?? $_SESSION['edit-post-error'] ?? $_SESSION['delete-post-error']) ?>',
                            confirmButtonText: 'OK'
                        });
                    });
                </script>
                <?php 
                    unset($_SESSION['add-post-error']);
                    unset($_SESSION['edit-post-error']);
                    unset($_SESSION['delete-post-error']);
                ?>
            <?php endif; ?>
            
            <!-- Contenido del dashboard -->
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Image</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post) : ?>
                        <tr>
                            <td><?= htmlspecialchars($post['titulo']) ?></td>
                            <td><?= htmlspecialchars($post['fecha']) ?></td>
                            <td>
                                <img src="<?= BASE_URL ?>/assets/images/<?= htmlspecialchars($post['imagen']) ?>" 
                                     alt="<?= htmlspecialchars($post['titulo']) ?>" 
                                     style="max-width: 100px; height: auto;">
                            </td>
                            <td>
                                <a href="<?= ADMIN_URL ?>/../edit/edit-post.php?id=<?= $post['idNoticia'] ?>" 
                                   class="btn btn-warning btn-sm">Edit</a>
                            </td>
                            <td>
                                <a href="<?= ADMIN_URL ?>/../delete/delete-post.php?id=<?= $post['idNoticia'] ?>" 
                                   class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</section>

<?php
include_once __DIR__ . "/../../public/templates/footer.php";
?>

