<?php
// Habilitar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../config_adm/dataBase.php";

// Verificar si el usuario está autenticado
$is_authenticated = isset($_SESSION['idUser']);
$is_admin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

//Control access
if (isset($_SESSION['idUser'])) {
    $id = filter_var($_SESSION['idUser'], FILTER_SANITIZE_NUMBER_INT);

    // Validar que el ID sea un número entero válido
    if (!filter_var($id, FILTER_VALIDATE_INT)) {
        header("Location: " . BASE_URL . "/login");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- Favicon -->
    <link rel="icon" type="favicon/ico" href="<?= IMAGES_PATH ?>/favicon.ico">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="<?= SWEETALERT_CSS; ?>">

    <!-- Bootstrap 5 CSS -->
    <link href="<?= BOOTSTRAP_5_CSS ?>" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?= BOOTSTRAP_ICONS_CSS ?>">  

    <!-- UNICONS CSS -->
    <link rel="stylesheet" href="<?= UNICONS_CSS ?>">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= FONTAWESOME_5_CSS ?>">

    <!-- Google Font (Montserrat) -->
    <link rel="stylesheet" href="<?= MONTSERRAT_FONT ?>">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="<?= CSS_PATH ?>/style.css">
</head>
<body>
    <!------- NAVBAR ----->
    <nav>
        <div class="container nav__container">
            <!-- Logo -->
            <a href="<?= BASE_URL ?>" class="nav__logo d-flex align-items-center">
                <img src="<?= IMAGES_PATH ?>/logo.png" alt="Logo" class="logo-image img-fluid"
                     style="max-width: 70px; height: auto;">
            </a>

            <!-- Opciones del Navbar -->
            <ul class="nav__items">
                <?php if ($is_authenticated) : ?>
                    <!-- Si el usuario ha iniciado sesión -->
                    <?php if ($is_admin) : ?>
                        <!-- Opciones para administradores -->
                        <li><a href="<?= ADMIN_URL ?>/index.php">Dashboard</a></li>
                        <li><a href="<?= ADMIN_URL ?>/add-post.php">Admin News</a></li>
                        <li><a href="<?= ADMIN_URL ?>/manage-users.php">Admin Users</a></li>
                        <li><a href="<?= ADMIN_URL ?>/manage-categories.php">Admin Appointments</a></li>
                    <?php else : ?>
                        <!-- Opciones para usuarios comunes -->
                        <li><a href="<?= BASE_URL ?>">Home</a></li>
                        <li><a href="<?= BASE_URL ?>/add-news">News</a></li>
                    <?php endif; ?>
                    <li><a href="<?= BASE_URL ?>/profile">Profile</a></li>
                    <li>
                        <!-- Cerrar Sesión -->
                        <a class="nav-link fw-bold" href="<?= BASE_URL ?>/logout"
                            style="background-color: #dc3545; color: #d3d1d1; border-radius: 5px; padding: 0.5rem 1rem; transition: background-color 0.3s, color 0.3s;"
                            onmouseover="this.style.backgroundColor='#bb2d3b'; this.style.color='white';"
                            onmouseout="this.style.backgroundColor='#dc3545'; this.style.color='#d3d1d1';">
                            Logout
                        </a>
                    </li>
                <?php else : ?>
                    <!-- Si el usuario no ha iniciado sesión -->
                    <li>
                        <!-- Log In -->
                        <a class="nav-link fw-bold" href="<?= BASE_URL ?>/login"
                            style="background-color: #198754; color: #d3d1d1; border-radius: 5px; padding: 0.5rem 1rem; transition: background-color 0.3s, color 0.3s;"
                            onmouseover="this.style.backgroundColor='#157347'; this.style.color='white';"
                            onmouseout="this.style.backgroundColor='#198754'; this.style.color='#d3d1d1';">
                            Log In
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <!-- Botón para mostrar/ocultar el menú en dispositivos móviles -->
            <button id="toggle__nav-btn"><i class="uil uil-bars"></i></button>
        </div>
    </nav>
    <!----- END OF NAV ----->

    <?php if (isset($_SESSION['login-success'])) : ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '<?= addslashes($_SESSION['login-success']) ?>',
                    confirmButtonText: 'OK'
                });
            });
        </script>
    <?php unset($_SESSION['login-success']); ?>
    <?php endif; ?>
    <?php
    // Mostrar mensaje de éxito si existe
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
        // Limpiar el mensaje de éxito de la sesión
        unset($_SESSION['login-success']);
    }
    ?>

