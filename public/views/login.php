<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../config/database.php";

// Recuperar datos del formulario si hubo un error previo
$username = $_SESSION['signin-data']['username'] ?? '';
unset($_SESSION['signin-data']);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="icon" type="favicon/ico" href="<?= IMAGES_PATH ?>/favicon.ico">
    <link rel="stylesheet" href="<?= SWEETALERT_CSS; ?>">
    <link rel="stylesheet" href="<?= FONTAWESOME_5_CSS ?>">
    <link rel="stylesheet" href="<?= BOOTSTRAP_ICONS_CSS ?>">
    <link rel="stylesheet" href="<?= UNICONS_CSS ?>">
    <link href="<?= BOOTSTRAP_4_CSS ?>" rel="stylesheet">
    <link href="<?= MONTSERRAT_FONT ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= CSS_PATH ?>/style.css">
</head>
<body>
    <div class="d-flex justify-content-center align-items-center vh-100" style="background: #02264a;">
        <div class="card p-4 shadow" style="width: 100%; max-width: 400px; background: #0056b3;">
            <a href="<?= BASE_URL ?>" class="text-center mb-4 d-block">
                <img src="<?= IMAGES_PATH ?>/logo.png" alt="Logo" class="logo-image img-fluid mx-auto d-block" style="max-width: 70px; height: auto;">
            </a>
            <h2 style="color: #d3d1d1;" class="h3 mb-3 fw-normal text-center">Log In</h2>

            <!-- Mostrar mensaje de error con SweetAlert2 -->
            <?php if (isset($_SESSION['login-error'])) : ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: '<?= addslashes($_SESSION['login-error']) ?>',
                            confirmButtonText: 'OK'
                        });
                    });
                </script>
                <?php unset($_SESSION['login-error']); ?>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>/controllers/login-logic.php">
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" style="background-color: #d3d1d1;">
                            <i class="fas fa-user"></i>
                        </span>
                    </div>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="username" 
                        value="<?= htmlspecialchars($username) ?>" 
                        name="username" 
                        placeholder="Enter username or email" 
                        required>
                </div>
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="lock-icon" style="background-color: #d3d1d1;">
                            <i class="fas fa-lock"></i> 
                        </span>
                    </div>
                    <input 
                        type="password" 
                        class="form-control" 
                        id="password" 
                        name="password" 
                        placeholder="Enter password" 
                        required>
                    <div class="input-group-append">
                        <button 
                            class="btn btn-outline-secondary" 
                            type="button" 
                            onclick="togglePasswordVisibility('password')">
                            <i class="fas fa-eye" style="background-color: #d3d1d1;"></i> 
                        </button>
                    </div>
                </div>
                <div style="color: #d3d1d1;" class="my-2">Don't have an account? <a href="<?= BASE_URL ?>/signup" class="text-success fw-bold">Sign up!</a></div>
                <button style="color: #d3d1d1;" type="submit" name="submit" class="btn btn-primary btn-block">Log In</button>
            </form>
            <p class="mt-3 mb-3 text-muted">© <?php echo date("Y"); ?></p>
        </div>
    </div>

    <script src="<?= SWEETALERT_JS; ?>"></script>
    <script src="<?= BOOTSTRAP_JS ?>"></script>
    <script src="<?= JS_PATH ?>/main.js"></script>
</body>
</html>
