<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Control de acceso: Solo administradores pueden agregar usuarios
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    $_SESSION['error'] = "No tienes permisos para acceder a esta página.";
    header('Location: ' . ADMIN_URL . '/index.php');
    exit();
}

require_once __DIR__ . "/../config_adm/dataBase.php";

// Verificar si el formulario fue enviado
if (isset($_POST['submit'])) {
    // Sanitizar y recopilar datos del formulario
    $name = filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $usuario = filter_var($_POST['usuario'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $createPassword = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $userrole = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT); // 0 para Author, 1 para Admin

    // Validar campos
    if (!$name) {
        $_SESSION['add-user'] = "Please enter your Name.";
    } elseif (!$lastname) {
        $_SESSION['add-user'] = "Please enter your Last Name.";
    } elseif (!$email) {
        $_SESSION['add-user'] = "Please enter a valid Email address.";
    } elseif (!$usuario) {
        $_SESSION['add-user'] = "Please enter a Username.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $usuario)) {
        $_SESSION['add-user'] = "Username can only contain letters, numbers, and underscores.";
    } elseif (!$createPassword) {
        $_SESSION['add-user'] = "Please create a password.";
    } elseif (strlen($createPassword) < 6) {
        $_SESSION['add-user'] = "Password must be at least 6 characters long.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/', $createPassword)) {
        $_SESSION['add-user'] = "Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
    } elseif ($createPassword !== $confirmPassword) {
        $_SESSION['add-user'] = "Passwords do not match.";
    } elseif ($userrole === "") {
        $_SESSION['add-user'] = "Please select user role.";
    } else {
        // Hash de la contraseña
        $hashed_password = password_hash($createPassword, PASSWORD_DEFAULT);

        try {
            // Crear conexión PDO
            $pdo = new PDO("mysql:host=localhost;dbname=khloe", "admin18", "admin18021978");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Verificar si el username o email ya existen
            $stmt = $pdo->prepare("
                SELECT usuario FROM users_login WHERE usuario = :usuario 
                UNION 
                SELECT email FROM users_data WHERE email = :email
            ");
            $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $_SESSION['add-user'] = "Username or Email already exists.";
            } else {
                // Insertar los datos en la tabla `users_data`
                $insert_user_query = $pdo->prepare("INSERT INTO users_data (name, last_name, email, phone, date_birth, address, gender, is_admin) 
                    VALUES (:name, :last_name, :email, :phone, :date_birth, :address, :gender, :is_admin)");

                $insert_user_query->bindParam(':name', $name, PDO::PARAM_STR);
                $insert_user_query->bindParam(':last_name', $lastname, PDO::PARAM_STR);
                $insert_user_query->bindParam(':email', $email, PDO::PARAM_STR);
                $insert_user_query->bindValue(':phone', '0000000000', PDO::PARAM_STR); // Valor predeterminado para phone
                $insert_user_query->bindValue(':date_birth', '1900-01-01', PDO::PARAM_STR); // Valor predeterminado para date_birth
                $insert_user_query->bindValue(':address', '', PDO::PARAM_STR); // Valor predeterminado para address
                $insert_user_query->bindValue(':gender', 'other', PDO::PARAM_STR); // Valor predeterminado para gender
                $insert_user_query->bindParam(':is_admin', $userrole, PDO::PARAM_INT);

                if ($insert_user_query->execute()) {
                    // Obtener el ID del usuario recién insertado
                    $user_id = $pdo->lastInsertId();

                    // Insertar la contraseña en la tabla `users_login`
                    $insert_login_query = $pdo->prepare("INSERT INTO users_login (idUser, usuario, password, rol) 
                        VALUES (:idUser, :usuario, :password, :rol)");

                    $insert_login_query->bindParam(':idUser', $user_id, PDO::PARAM_INT);
                    $insert_login_query->bindParam(':usuario', $usuario, PDO::PARAM_STR);
                    $insert_login_query->bindParam(':password', $hashed_password, PDO::PARAM_STR);
                    $insert_login_query->bindValue(':rol', ($userrole == 1) ? 'admin' : 'user', PDO::PARAM_STR);

                    if ($insert_login_query->execute()) {
                        $_SESSION['add-user-success'] = "New user $name $lastname added successfully.";
                        header('Location: ' . ADMIN_URL . '/manage-users.php');
                        exit();
                    } else {
                        $_SESSION['add-user'] = "Error adding user. Please try again later.";
                    }
                } else {
                    $_SESSION['add-user'] = "Error adding user. Please try again later.";
                }
            }
        } catch (PDOException $e) {
            $_SESSION['add-user'] = "Database error: " . $e->getMessage();
        }
    }

    // Guardar datos del formulario en la sesión para mostrarlos en caso de error
    $_SESSION['add-user-data'] = $_POST;

    // Redirigir de vuelta a la página de agregar usuario si hay algún problema
    header('Location: ' . ADMIN_URL . '/add-user.php');
    exit();
} else {
    header('Location: ' . ADMIN_URL . '/add-user.php');
    exit();
}
?>
