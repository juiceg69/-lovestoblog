<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//session_start(); 
$current_url = strtok($_SERVER['REQUEST_URI'], '?') ?? '/';
require_once __DIR__ . '/../config/database.php';

function normalizeUrl($url) {
    $url = trim($url, '/');
    $url = strtolower($url);
    return $url ?: '';
}

$current_url = normalizeUrl($current_url);

// Definir las URLs de cada sección según el rol
$nav_items = [
    'visitor' => [
        'Home' => BASE_URL . '/',
        'News' => BASE_URL . '/blog',
        'Sign Up' => BASE_URL . '/signup',
        'Log In' => BASE_URL . '/login'
    ],
    'user' => [
        'Home' => BASE_URL . '/',
        'News' => BASE_URL . '/blog',
        'Add News' => BASE_URL . '/add-news',
        'Profile' => BASE_URL . '/profile',
        'Appointments' => BASE_URL . '/appointments',
        'Logout' => BASE_URL . '/logout'
    ],
    'admin' => [
        'Dashboard' => ADMIN_URL . '/index.php',
        'Profile' => BASE_URL . '/profile',
        'Logout' => BASE_URL . '/logout'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP & MySQL Blog App with Admin Panel</title>
    <link rel="icon" type="favicon/ico" href="<?= IMAGES_PATH ?>/favicon.ico">
    <link rel="stylesheet" href="<?= SWEETALERT_CSS; ?>">
    <link href="<?= BOOTSTRAP_5_CSS ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= BOOTSTRAP_ICONS_CSS ?>">
    <link rel="stylesheet" href="<?= UNICONS_CSS ?>">
    <link rel="stylesheet" href="<?= FONTAWESOME_5_CSS ?>">
    <link rel="stylesheet" href="<?= MONTSERRAT_FONT ?>">
    <link rel="stylesheet" href="<?= CSS_PATH ?>/style.css">
</head>
<body>
    <nav>
        <div class="container nav__container">
            <a href="<?= BASE_URL ?>" class="nav__logo d-flex align-items-center">
                <img src="<?= IMAGES_PATH ?>/logo.png" alt="Logo" class="logo-image img-fluid" style="max-width: 70px; height: auto;">
            </a>
            <ul class="nav__items">
                <?php
                // Determinar el rol del usuario
                if (!isset($_SESSION['idUser'])) {
                    $role = 'visitor';
                } elseif ($_SESSION['is_admin'] == 0) {
                    $role = 'user';
                } else {
                    $role = 'admin';
                }

                // Generar los ítems de navegación según el rol
                foreach ($nav_items[$role] as $label => $url) {
                    $normalized_url = normalizeUrl($url);
                    $is_active = ($current_url === $normalized_url || 
                                 ($label === 'Home' && $current_url === normalizeUrl(BASE_URL . '/index'))) ? 'active' : '';
                    
                    // Estilos especiales para "Log In" y "Logout"
                    if ($label === 'Log In') {
                        echo "<li>
                            <a class='nav-link fw-bold' href='$url'
                               style='background-color: #198754; color: #d3d1d1; border-radius: 5px; padding: 0.5rem 1rem; transition: background-color 0.3s, color 0.3s;'
                               onmouseover='this.style.backgroundColor=\"#157347\"; this.style.color=\"white\";'
                               onmouseout='this.style.backgroundColor=\"#198754\"; this.style.color=\"#d3d1d1\";'>
                               $label
                            </a>
                        </li>";
                    } elseif ($label === 'Logout') {
                        echo "<li>
                            <a class='nav-link fw-bold' href='$url'
                               style='background-color: #dc3545; color: #d3d1d1; border-radius: 5px; padding: 0.5rem 1rem; transition: background-color 0.3s, color 0.3s;'
                               onmouseover='this.style.backgroundColor=\"#bb2d3b\"; this.style.color=\"white\";'
                               onmouseout='this.style.backgroundColor=\"#dc3545\"; this.style.color=\"#d3d1d1\";'>
                               $label
                            </a>
                        </li>";
                    } else {
                        echo "<li class='$is_active'><a href='$url'>$label</a></li>";
                    }
                }
                ?>
            </ul>
            <button id="toggle__nav-btn"><i class="uil uil-bars"></i></button>
        </div>
    </nav>

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

    <!-- Mensaje de éxito para logout -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if (isset($_GET['logout']) && $_GET['logout'] === 'success') : ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '¡Has cerrado sesión correctamente!',
                    confirmButtonText: 'OK'
                });
            });
        </script>
    <?php endif; ?>

    <!----- END OF NAV ----->