<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . "/../config/database.php";
include_once __DIR__ . "/../templates/header.php";

if (!isset($_SESSION['idUser'])) {
    header('Location: ' . BASE_URL . '/login');
    exit();
}

$user_id = $_SESSION['idUser'];

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT * FROM users_data WHERE idUser = :idUser");
    $stmt->bindParam(':idUser', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user_data) {
        die("No se encontraron datos para el usuario con ID: $user_id");
    }

    $stmt = $pdo->prepare("SELECT usuario FROM users_login WHERE idUser = :idUser");
    $stmt->bindParam(':idUser', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user_login = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user_login) {
        die("No se encontró el nombre de usuario para el ID: $user_id");
    }
} catch (PDOException $e) {
    die("Error al obtener los datos del usuario: " . $e->getMessage());
}

$name = $_SESSION['profile-data']['name'] ?? $user_data['name'];
$lastname = $_SESSION['profile-data']['lastname'] ?? $user_data['last_name'];
$email = $_SESSION['profile-data']['email'] ?? $user_data['email'];
$phone = $_SESSION['profile-data']['phone'] ?? $user_data['phone'];
$date_birth = $_SESSION['profile-data']['date_birth'] ?? $user_data['date_birth'];
$address = $_SESSION['profile-data']['address'] ?? $user_data['address'];
$gender = $_SESSION['profile-data']['gender'] ?? $user_data['gender'];

unset($_SESSION['profile-data']);
?>

    <div class="d-flex justify-content-center align-items-center" style="background: #02264a; margin-top: 6rem; padding: 2rem 0;">
        <div class="card p-4 shadow" style="width: 100%; max-width: 400px; background: #0056b3;">
            <a href="<?= BASE_URL ?>" class="text-center mb-4 d-block">
                <img src="<?= IMAGES_PATH ?>/logo.png" alt="Logo" class="logo-image img-fluid mx-auto d-block" style="max-width: 70px; height: auto;">
            </a>
            <h2 style="color: #d3d1d1; text-align: center; margin-bottom: 2rem;">User Profile</h2>

            <form method="POST" action="<?= BASE_URL ?>/controllers/profile-logic.php" id="profileForm">
                <!-- Campos del formulario (sin cambios) -->
                <div class="form-floating mb-2">
                    <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;" name="name" type="text" class="form-control w-100" id="floatingName" placeholder="Name" value="<?= htmlspecialchars($name) ?>" required>
                    <label for="floatingName">Name</label>
                </div>
                <div class="form-floating mb-2">
                    <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;" name="lastname" type="text" class="form-control w-100" id="floatingLastname" placeholder="Last Name" value="<?= htmlspecialchars($lastname) ?>" required>
                    <label for="floatingLastname">Last Name</label>
                </div>
                <div class="form-floating mb-2">
                    <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;" name="email" type="email" class="form-control w-100" id="floatingEmail" placeholder="Email" value="<?= htmlspecialchars($email) ?>" required>
                    <label for="floatingEmail">Email</label>
                </div>
                <div class="form-floating mb-2">
                    <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;" name="phone" type="tel" class="form-control w-100" id="floatingPhone" placeholder="Phone" value="<?= htmlspecialchars($phone) ?>" required>
                    <label for="floatingPhone">Phone</label>
                </div>
                <div class="form-floating mb-2">
                    <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;" name="date_birth" type="date" class="form-control w-100" id="floatingDateBirth" value="<?= htmlspecialchars($date_birth) ?>" required>
                    <label for="floatingDateBirth">Date of Birth</label>
                </div>
                <div class="form-floating mb-2">
                    <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;" name="address" type="text" class="form-control w-100" id="floatingAddress" placeholder="Address" value="<?= htmlspecialchars($address) ?>">
                    <label style="color: #fff" for="floatingAddress">Address</label>
                </div>
                <div class="form-floating mb-2">
                    <select style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;" name="gender" class="form-control w-100" id="floatingGender">
                        <option value="male" <?= $gender === 'male' ? 'selected' : '' ?>>Male</option>
                        <option value="female" <?= $gender === 'female' ? 'selected' : '' ?>>Female</option>
                        <option value="other" <?= $gender === 'other' ? 'selected' : '' ?>>Other</option>
                    </select>
                    <label for="floatingGender">Gender</label>
                </div>
                <div class="form-floating mb-2">
                    <input style="color: #d3d1d1; background: #003366; border: 1px solid #007bff;" name="password" type="password" class="form-control w-100" id="floatingPassword" placeholder="New Password">
                    <label style="color: #d3d1d1;" for="floatingPassword">New Password (blank to keep current)</label>
                </div>
                <button style="color: #d3d1d1;" class="btn btn-primary w-100 py-2" type="submit">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- SweetAlert2 para mensajes -->
    <?php if (isset($_SESSION['success'])) : ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '<?= addslashes($_SESSION['success']) ?>',
                    confirmButtonText: 'OK'
                });
            });
        </script>
        <?php unset($_SESSION['success']); ?>
    <?php elseif (isset($_SESSION['error'])) : ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '<?= addslashes($_SESSION['error']) ?>',
                    confirmButtonText: 'OK'
                });
            });
        </script>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 JS -->
    <?php include_once __DIR__ . "/../templates/footer.php"; ?>

