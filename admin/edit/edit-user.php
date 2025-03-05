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

// Obtener el ID del usuario a editar
if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    try {
        // Crear conexión PDO
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Obtener los datos del usuario
        $query = "SELECT users_data.*, users_login.usuario, users_login.rol 
                  FROM users_data 
                  JOIN users_login ON users_data.idUser = users_login.idUser 
                  WHERE users_data.idUser = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $_SESSION['edit-user-error'] = "User not found.";
            header('Location: ' . ADMIN_URL . '/manage-users.php');
            exit();
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    header('Location: ' . ADMIN_URL . '/manage-users.php');
    exit();
}
?>

<section class="form-signin" style="margin-top: 90px;">
    <div class="form-container">
        <a href="<?= ADMIN_URL ?>/index.php" class="text-center mb-4 d-block">
            <img src="<?= IMAGES_PATH ?>/logo.png" alt="Logo" class="logo-image img-fluid mx-auto d-block"
            style="max-width: 70px; height: auto;">
        </a>
        <h2 class="h3 mb-3 fw-normal">Edit User</h2>

        <form method="POST" action="<?= ADMIN_URL ?>/../admin_controllers/edit-user-logic.php" enctype="multipart/form-data">
            <input type="hidden" name="idUser" value="<?= $user['idUser'] ?>">
            <div class="form-floating mb-2">
                <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;" 
                name="name" type="text" class="form-control w-100" id="floatingName" placeholder="Name" 
                value="<?= htmlspecialchars($user['name']) ?>" required>
                <label for="floatingName">Name</label>
            </div>
            <div class="form-floating mb-2">
                <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;" 
                name="lastname" type="text" class="form-control w-100" id="floatingLastname" placeholder="Last Name" 
                       value="<?= htmlspecialchars($user['last_name']) ?>" required>
                <label  for="floatingLastname">Last Name</label>
            </div>
            <div class="form-floating mb-2">
                <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;"
                 name="username" type="text" class="form-control w-100" id="floatingUsername" placeholder="Username" 
                    value="<?= htmlspecialchars($user['usuario']) ?>" required>
                <label  for="floatingUsername">Username</label>
            </div>
            <div class="form-floating mb-2">
                <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;"
                 name="password" type="password" class="form-control w-100" id="floatingPassword" placeholder="New Password">
                <label  for="floatingPassword">New Password (blank to keep current)</label>
            </div>
            <div class="form-floating mb-2">
                <select style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;"
                    name="userrole" class="form-select custom-select" id="floatingCategory" required>
                    <option value="0" <?= ($user['rol'] === 'user') ? 'selected' : '' ?>>User</option>
                    <option value="1" <?= ($user['rol'] === 'admin') ? 'selected' : '' ?>>Admin</option>
                </select>
                <label  for="floatingCategory">User Role</label>
            </div>
            <div class="my-3">
                Back to <a href="<?= ADMIN_URL ?>/index.php" class="text-success fw-bold">Admin Panel</a>
            </div>
            <button class="btn btn-primary w-100 py-2" type="submit" name="submit">Update User</button>
            <p class="mt-3 mb-3 text-muted">&copy; <?php echo date("Y"); ?></p>
        </form>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Mostrar SweetAlert si hay un mensaje de éxito
    <?php if (isset($_SESSION['edit-user-success'])) : ?>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '<?= $_SESSION['edit-user-success'] ?>',
            confirmButtonText: 'OK'
        });
        <?php unset($_SESSION['edit-user-success']); ?>
    <?php endif; ?>

    // Mostrar SweetAlert si hay un mensaje de error
    <?php if (isset($_SESSION['edit-user-error'])) : ?>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '<?= $_SESSION['edit-user-error'] ?>',
            confirmButtonText: 'OK'
        });
        <?php unset($_SESSION['edit-user-error']); ?>
    <?php endif; ?>
</script>

<?php
include_once __DIR__ . "/../../public/templates/footer.php";
?>