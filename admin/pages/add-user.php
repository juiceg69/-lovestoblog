<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../config_adm/dataBase.php";
include __DIR__ . "/../templates_adm/header.php";

// Recuperar datos del formulario si hubo un error
$name = $_SESSION['add-user-data']['name'] ?? null;
$last_name = $_SESSION['add-user-data']['last_name'] ?? null;
$usuario = $_SESSION['add-user-data']['usuario'] ?? null;
$email = $_SESSION['add-user-data']['email'] ?? null;
$createPassword = $_SESSION['add-user-data']['password'] ?? null;
$confirmPassword = $_SESSION['add-user-data']['confirmPassword'] ?? null;
$userrole = $_SESSION['add-user-data']['category'] ?? null;

// Eliminar datos de sesión después de usarlos
unset($_SESSION['add-user-data']);
?>

<section class="form-signin" style="margin-top: 90px;">
    <div class="form-container">
        <!-- Logo -->
        <a href="<?= ADMIN_URL ?>/index.php" class="text-center mb-4 d-block">
            <img src="<?= IMAGES_PATH ?>/logo.png" alt="Logo" class="logo-image img-fluid mx-auto d-block" style="max-width: 70px; height: auto;">
        </a>
        <h2 class="h3 mb-3 fw-normal">Add User</h2>

        <!-- Mostrar mensajes de error o éxito con SweetAlert -->
        <?php if (isset($_SESSION['add-user-error'])): ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: '<?= addslashes($_SESSION['add-user-error']) ?>',
                                confirmButtonText: 'OK'
                            });
                        });
                    </script>
                    <?php unset($_SESSION['add-user-error']); ?>
                <?php elseif (isset($_SESSION['add-user-success'])): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: '<?= addslashes($_SESSION['add-user-success']) ?>',
                            confirmButtonText: 'OK'
                        });
                    });
                </script>
                <?php unset($_SESSION['add-user-success']); ?>
            <?php endif; ?>

        <form method="POST" action="<?= ADMIN_URL ?>/../admin_controllers/add-user-logic.php"enctype="multipart/form-data">
            <div class="form-floating mb-2">
                <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;" 
                    name="name" type="text" class="form-control w-100" id="floatingTitle" placeholder="Name" 
                    value="<?= htmlspecialchars($name) ?>" required>    
                <label style="color: #d3d1d1;" for="floatingName">Name</label>
            </div>

            <div class="form-floating mb-2">
                <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;"
                    name="lastname" type="text" class="form-control w-100" id="floatingLastname" placeholder="Last Name" 
                    value="<?= htmlspecialchars($last_name) ?>" required>
                <label style="color: #d3d1d1;" for="floatingName">Last Name</label>
            </div>

            <div class="form-floating mb-2">
                <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;"
                    name="email" type="email" class="form-control w-100" id="floatingEmail" placeholder="name@example.com" 
                    value="<?= htmlspecialchars($email) ?>" required>
                <label style="color: #d3d1d1;" for="floatingName">Email</label>
            </div>

            <div class="form-floating mb-2">
                <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;"
                    name="usuario" type="text" class="form-control w-100" id="floatingUsername" placeholder="Username" 
                    value="<?= htmlspecialchars($usuario) ?>" required>
                <label style="color: #d3d1d1;" for="floatingName">Username</label>
            </div>

            <div class="form-floating mb-2 position-relative">
                <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;"
                    name="password" type="password" class="form-control w-100" id="floatingPassword" placeholder="Password"
                    value="<?= htmlspecialchars($createPassword) ?>" required>
                <label style="color: #d3d1d1;" for="floatingName">Password</label>
                <span class="input-icon" onclick="togglePasswordVisibility('floatingPassword', this)">
                    <i class="fas fa-eye" id="passwordIcon"></i>
                </span>
            </div>

            <div class="form-floating mb-2 position-relative">
                <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;"
                    name="confirmPassword" type="password" class="form-control w-100" id="floatingRetypePassword" placeholder="Confirm Password" 
                    value="<?= htmlspecialchars($confirmPassword) ?>" required>
                <label style="color: #d3d1d1;" for="floatingName">Confirm Password</label>
                <span class="input-icon" onclick="togglePasswordVisibility('floatingConfirmPassword', this)">
                    <i class="fas fa-eye" id="confirmPasswordIcon"></i>
                </span>
            </div>

            <select style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;"
                name="category" class="form-select custom-select" id="floatingCategory" required>
                <option value="">Select User</option>
                <option value="0" <?= ($userrole == 0) ? 'selected' : '' ?>>Author</option>
                <option value="1" <?= ($userrole == 1) ? 'selected' : '' ?>>Admin</option>
            </select>

            <div style="color: #d3d1d1;" class="my-2">Back to <a href="<?= ADMIN_URL ?>/index.php" class="text-success fw-bold"> Admin Panel</a></div>
            <button style="color: #d3d1d1;" class="btn btn-primary w-100 py-2" name="submit" type="submit">Add User</button>
            <p class="mt-3 mb-3 text-muted text-center">&copy; <?php echo date("Y"); ?></p>
        </form>
    </div>
</section>

<?php
include_once __DIR__ . "/../../public/templates/footer.php";
?>