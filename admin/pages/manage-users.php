<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../config_adm/dataBase.php";
include_once __DIR__ . "/../templates_adm/header.php";

// Verificar si el usuario es administrador
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ' . BASE_URL . '/login');
    exit();
}

try {
    // Crear conexión PDO
    $pdo = new PDO("mysql:host=localhost;dbname=khloe", "admin18", "admin18021978");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener los datos de los usuarios
    $stmt = $pdo->prepare("
        SELECT users_data.idUser, users_data.name, users_data.last_name, users_data.email, users_login.usuario, users_login.rol 
        FROM users_data 
        JOIN users_login ON users_data.idUser = users_login.idUser
    ");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    $users = []; // En caso de error, inicializar como un array vacío
}
?>

    <section class="dashboard">
        <div class="container dashboard__container">
            <!------ SIDE BAR BTN --->
            <button id="show__sidebar-btn" class="sidebar__toggle">
                <i class="uil uil-angle-right-b"></i>
            </button>
            <button id="hide__sidebar-btn" class="sidebar__toggle" style="display: none;">
                <i class="uil uil-angle-left-b"></i>
            </button>

            <aside>
                <ul>
                    <li>
                        <a href="<?= ADMIN_URL ?>/add-post.php"><i class="uil uil-pen"></i>
                        <h5>Add News</h5></a>
                    </li>
                    <li>
                        <a href="<?= ADMIN_URL ?>/index.php"><i class="uil uil-postcard"></i>
                        <h5>Manage News</h5></a>
                    </li>
                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) : ?>
                        <li>
                            <a href="<?= ADMIN_URL ?>/add-user.php"><i class="uil uil-user-plus"></i>
                            <h5>Add User</h5></a>
                        </li>
                        <li>
                            <a href="<?= ADMIN_URL ?>/manage-users.php" class="active"><i class="uil uil-users-alt"></i>
                            <h5>Manage Users</h5></a>
                        </li>
                        <li>
                            <a href="<?= ADMIN_URL ?>/add-category.php"><i class="uil uil-edit"></i>
                            <h5>Add Appointment</h5></a>
                        </li>
                        <li>
                            <a href="<?= ADMIN_URL ?>/manage-categories.php"><i class="uil uil-list-ul"></i>
                            <h5>Manage Appointments</h5></a>
                        </li>
                    <?php endif; ?>
                </ul>
            </aside>

            <main>
                <h2 style="color: #d3d1d1;">Manage Users</h2>
                <?php if (isset($_SESSION['error'])) : ?>
                    <div class="alert alert-danger">
                        <p><?= $_SESSION['error'] ?></p>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <!--<th>Email</th>-->
                            <th>Edit</th>
                            <th>Delete</th>
                            <th>Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)) : ?>
                            <?php foreach ($users as $user) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($user['name'] . ' ' . $user['last_name']) ?></td>
                                    <td><?= htmlspecialchars($user['usuario']) ?></td>
                                    <!--<td><?= htmlspecialchars($user['email']) ?></td>--->
                                    <td>
                                        <a href="<?= ADMIN_URL ?>/../edit/edit-user.php?id=<?= $user['idUser'] ?>" class="btn btn-warning btn-sm w-40 rounded">Edit</a>
                                    </td>
                                    <td>
                                        <a href="<?= ADMIN_URL ?>/../delete/delete-user.php?id=<?= $user['idUser'] ?>" class="btn btn-danger btn-sm w-40 rounded">Delete</a>
                                    </td>
                                    <td><?= ($user['rol'] === 'admin') ? 'Yes' : 'No' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6" class="text-center">No users found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </main>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Mostrar SweetAlert si hay un mensaje de éxito
        <?php if (isset($_SESSION['add-user-success'])) : ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?= $_SESSION['add-user-success'] ?>',
                confirmButtonText: 'OK'
            });
            <?php unset($_SESSION['add-user-success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['edit-user-success'])) : ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?= $_SESSION['edit-user-success'] ?>',
                confirmButtonText: 'OK'
            });
            <?php unset($_SESSION['edit-user-success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['delete-user-success'])) : ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?= $_SESSION['delete-user-success'] ?>',
                confirmButtonText: 'OK'
            });
            <?php unset($_SESSION['delete-user-success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['delete-user-error'])) : ?>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?= $_SESSION['delete-user-error'] ?>',
                confirmButtonText: 'OK'
            });
            <?php unset($_SESSION['delete-user-error']); ?>
        <?php endif; ?>
    </script>
    
    <!--<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>                      
    <script>
    
    <?php if (isset($_SESSION['add-user-success'])) : ?>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '<?= $_SESSION['add-user-success'] ?>',
            confirmButtonText: 'OK'
        });
        <?php unset($_SESSION['add-user-success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['edit-user-success'])) : ?>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '<?= $_SESSION['edit-user-success'] ?>',
            confirmButtonText: 'OK'
        });
        <?php unset($_SESSION['edit-user-success']); ?>
    <?php endif; ?>
</script>-->

<?php
 include "../../public/templates/footer.php";
?>
