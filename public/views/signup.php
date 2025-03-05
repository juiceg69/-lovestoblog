<?php
session_start();
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../config/constants.php";

// Recuperar datos del formulario si hubo un error previo
$name            = $_SESSION['signup-data']['name'] ?? null;
$lastname        = $_SESSION['signup-data']['lastname'] ?? null;
$username        = $_SESSION['signup-data']['username'] ?? null;
$email           = $_SESSION['signup-data']['email'] ?? null;
$createpassword  = $_SESSION['signup-data']['password'] ?? null;
$confirmpassword = $_SESSION['signup-data']['confirmpassword'] ?? null;
$phone           = $_SESSION['signup-data']['phone'] ?? null;
$gender          = $_SESSION['signup-data']['gender'] ?? null;
$address         = $_SESSION['signup-data']['address'] ?? null;
$date_birth      = $_SESSION['signup-data']['date_birth'] ?? null;

// Limpiar datos de sesión después de usarlos
unset($_SESSION['signup-data']);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Form</title>

    <!-- Favicon -->
    <link rel="icon" type="favicon/ico" href="<?= IMAGES_PATH?>/favicon.ico">
  
    <!-- FontAwesome -->
    <link rel="stylesheet" href="<?= FONTAWESOME_5_CSS?>">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?= BOOTSTRAP_ICONS_CSS ?>">  
    
    <!-- UNICONS CSS -->
    <link rel="stylesheet" href="<?= UNICONS_CSS ?>">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="<?= SWEETALERT_CSS; ?>">

    <!-- Bootstrap 4 CSS -->
    <link href="<?= BOOTSTRAP_4_CSS ?>" rel="stylesheet">

    <!-- GOOGLE FONT (MONTSERRAT) -->
    <link href="<?= MONTSERRAT_FONT ?>" rel="stylesheet">

    <!-- CUSTOM STYLES -->
    <link rel="stylesheet" href="<?= CSS_PATH ?>/style.css">
</head>
<body>
    <section class="form-signin w-100 m-auto">
        <div class="form-container">
            <!-- Logo -->
            <a href="<?= BASE_URL ?>" class="text-center mb-4 d-block">
                <img src="<?= IMAGES_PATH ?>/logo.png" alt="Logo" class="logo-image img-fluid mx-auto d-block"
                style="max-width: 70px; height: auto;">
            </a>
            <h2 class="h3 mb-3 fw-normal">Sign Up</h2>

            <!-- Formulario -->
            <form method="POST" action="<?= BASE_URL ?>/controllers/signup-logic.php" id="signupForm" enctype="multipart/form-data">
                <!-- Campos del formulario (sin cambios) -->
                <div class="form-floating mb-2">
                    <input name="name" type="text" value="<?= $name ?>" class="form-control w-100" id="floatingName" placeholder="Name" >
                </div>
                <div class="form-floating mb-2">
                    <input name="lastname" type="text" value="<?= $lastname ?>" class="form-control w-100" id="floatingLastname" placeholder="Last Name" >
                </div>
                <div class="form-floating mb-2">
                    <input name="email" type="email" value="<?= $email ?>" class="form-control w-100" id="floatingEmail" placeholder="name@example.com" >
                </div>
                <div class="form-floating mb-2">
                    <input name="username" type="text" value="<?= $username ?>" class="form-control w-100" id="floatingUsername" placeholder="Username" >
                </div>
                <div class="form-floating mb-2 position-relative">
                    <input name="password" type="password" value="<?= $createpassword ?>" class="form-control w-100" id="floatingPassword" placeholder="Password" >
                    <span class="input-icon" onclick="togglePasswordVisibility('floatingPassword', this)">
                        <i class="fas fa-eye dark-icon" id="passwordIcon"></i>
                    </span>
                </div>
                <div class="form-floating mb-2 position-relative">
                    <input name="confirmPassword" type="password" value="<?= $confirmpassword ?>" class="form-control w-100" id="floatingRetypePassword" placeholder="Confirm Password" >
                    <span class="input-icon" onclick="togglePasswordVisibility('floatingRetypePassword', this)">
                        <i class="fas fa-eye dark-icon" id="retypePasswordIcon"></i>
                    </span>
                </div>
                <div class="form-floating mb-2">
                    <input name="phone" type="tel" value="<?= $phone ?>" class="form-control w-100" id="floatingPhone" placeholder="Phone Number" >
                </div>
                <div class="form-floating mb-2">
                    <input name="date_birth" type="date" value="<?= $date_birth ?>" class="form-control" id="floatingBirthdate" >
                </div>
                <div class="form-floating mb-2">
                    <input name="address" type="text" value="<?= $address ?>" class="form-control" id="floatingAddress" placeholder="Address" >
                </div>
                <div class="form-floating mb-2">
                    <select name="gender" class="form-select custom-select" id="floatingGender" >
                        <option value="">Select Gender</option>
                        <option value="male" <?= $gender === 'male' ? 'selected' : '' ?>>Male</option>
                        <option value="female" <?= $gender === 'female' ? 'selected' : '' ?>>Female</option>
                        <option value="other" <?= $gender === 'other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
                <div style="color: #d3d1d1;" class="my-2">Already have an account? 
                    <a href="<?= BASE_URL ?>/login" style="color: #d3d1d1;" class="text-success fw-bold">LogIn here!</a>
                </div>
                <div class="form-check mb-3">
                    <input name="terms" type="checkbox" class="form-check-input" id="floatingTerms" >
                    <label style="color: #d3d1d1;" class="form-check-label" for="floatingTerms">Accept terms and conditions</label>
                </div>
                <button style="color: #d3d1d1;" class="btn btn-primary w-100 py-2" name="submit" type="submit">Sign Up</button>
            </form>
        </div>
    </section>

    <!-- SweetAlert2 para éxito o error -->
    <?php if (isset($_SESSION['signup-success'])) : ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '<?= addslashes($_SESSION['signup-success']) ?>',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_URL ?>/views/login.php';
                    }
                });
            });
        </script>
        <?php unset($_SESSION['signup-success']); ?>
    <?php elseif (isset($_SESSION['signup-error'])) : ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '<?= addslashes($_SESSION['signup-error']) ?>',
                    confirmButtonText: 'OK'
                });
            });
        </script>
        <?php unset($_SESSION['signup-error']); ?>
    <?php endif; ?>

    <!-- Scripts -->
    <script src="<?= BOOTSTRAP_JS ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Asegúrate de incluir SweetAlert2 -->
    <script src="<?= JS_PATH ?>/main.js"></script>
</body>
</html>

