<?php
require_once __DIR__ . "/../config_adm/dataBase.php";
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si el usuario es administrador
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ' . BASE_URL . '/login');
    exit();
}

include_once __DIR__ . "/../templates_adm/header.php";


try {
    // Crear una conexión PDO
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener las citas de la base de datos
    $query = "SELECT * FROM citas ORDER BY fecha_cita";
    $stmt = $pdo->query($query);
    $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Manejar errores de la base de datos
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    $citas = []; // En caso de error, inicializar como un array vacío
}
?>

<section class="dashboard">
    <div class="container dashboard__container">
        <!------ SIDE BAR BTN --->
        <button id="show__sidebar-btn" class="sidebar__toggle">
            <i class="uil uil-angle-right-b"></i>
        </button>
        <button id="hide__sidebar-btn" class="sidebar__toggle">
            <i class="uil uil-angle-left-b"></i>
        </button>
        <aside>
            <ul>
                <li>
                    <a href="<?= ADMIN_URL ?>/add-post.php"><i class="uil uil-pen"></i>
                    <h5>Add News</h5>
                    </a>
                </li>
                <li>
                    <a href="<?= ADMIN_URL ?>/index.php"><i class="uil uil-postcard"></i>
                    <h5>Manage News</h5>
                    </a>
                </li>
               
                <li>
                    <a href="<?= ADMIN_URL ?>/add-user.php"><i class="uil uil-user-plus"></i>
                    <h5>Manage User</h5>
                    </a>
                </li>
                <li>
                    <a href="<?= ADMIN_URL ?>/manage-users.php"><i class="uil uil-users-alt"></i>
                    <h5>Manage Users</h5>
                    </a>
                </li>
                <li>
                    <a href="<?= ADMIN_URL ?>/add-category.php"><i class="uil uil-edit"></i>
                    <h5>Add Appointment</h5>
                    </a>
                </li>
                <li>
                    <a href="<?= ADMIN_URL ?>/manage-categories.php" class="active"><i class="uil uil-list-ul"></i>
                    <h5>Manage Appointments</h5>
                    </a>
                </li>
              
            </ul>
        </aside>
        <main>
            <h2 style="color: #d3d1d1;">Manage Appointments</h2>

            <!-- Mostrar mensajes de éxito o error -->
            <!---<?php if (isset($_SESSION['add-appointment-success'])) : ?>
                <div class="alert alert-success">
                    <p><?= $_SESSION['add-appointment-success'] ?></p>
                </div>
                <?php unset($_SESSION['add-appointment-success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['add-appointment-error'])) : ?>
                <div class="alert alert-danger">
                    <p><?= $_SESSION['add-appointment-error'] ?></p>
                </div>
                <?php unset($_SESSION['add-appointment-error']); ?>
            <?php endif; ?>--->

            <table class="table">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Appointment Date</th>
                        <th>Reason</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($citas)) : ?>
                        <?php foreach ($citas as $cita) : ?>
                            <tr>
                                <td><?= htmlspecialchars($cita['idUser']) ?></td>
                                <td><?= htmlspecialchars($cita['fecha_cita']) ?></td>
                                <td><?= htmlspecialchars($cita['motivo_cita']) ?></td>
                                <td>
                                    <a href="<?= ADMIN_URL ?>/../edit/edit-category.php?id=<?= $cita['idCita'] ?>" class="btn btn-warning btn-sm w-30 rounded">Edit</a>
                                </td>
                                <td>
                                    <a href="<?= ADMIN_URL ?>/../delete/delete-category.php?id=<?= $cita['idCita'] ?>" class="btn btn-danger btn-sm w-30 rounded">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="text-center">No appointments found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </main>
    </div>
</section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>                  

    <!-- SweetAlert para mostrar mensajes de éxito o error -->
    <script>
        <?php if (isset($_SESSION['add-appointment-success'])) : ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?= $_SESSION['add-appointment-success'] ?>',
                confirmButtonText: 'OK'
            });
            <?php unset($_SESSION['add-appointment-success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['add-appointment-error'])) : ?>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?= $_SESSION['add-appointment-error'] ?>',
                confirmButtonText: 'OK'
            });
            <?php unset($_SESSION['add-appointment-error']); ?>
        <?php endif; ?>

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

        <?php if (isset($_SESSION['delete-appointment-success'])) : ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?= $_SESSION['delete-appointment-success'] ?>',
                confirmButtonText: 'OK'
            });
            <?php unset($_SESSION['delete-appointment-success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['delete-appointment-error'])) : ?>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?= $_SESSION['delete-appointment-error'] ?>',
                confirmButtonText: 'OK'
            });
            <?php unset($_SESSION['delete-appointment-error']); ?>
        <?php endif; ?>
    </script>

<?php
include_once __DIR__ . "/../../public/templates/footer.php";
?>


