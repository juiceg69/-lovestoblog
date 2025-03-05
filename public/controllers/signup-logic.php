Signup-logic

<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../config/database.php";

if (isset($_POST['submit'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $createPassword = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $confirmPassword = filter_var($_POST['confirmPassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $dateBirth = filter_var($_POST['date_birth'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $gender = filter_var($_POST['gender'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Validaciones con expresiones regulares
    if (!$name || !preg_match("/^[a-zA-Z\s'-]+$/", $name)) {
        $_SESSION['signup-error'] = "El nombre solo puede contener letras, espacios, apóstrofos o guiones.";
    } elseif (!$lastname || !preg_match("/^[a-zA-Z\s'-]+$/", $lastname)) {
        $_SESSION['signup-error'] = "El apellido solo puede contener letras, espacios, apóstrofos o guiones.";
    } elseif (!$email) {
        $_SESSION['signup-error'] = "Por favor ingrese un email válido.";
    } elseif (!$username || !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $_SESSION['signup-error'] = "El nombre de usuario solo puede contener letras, números y guiones bajos.";
    } elseif (!$createPassword || strlen($createPassword) < 6 || !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/', $createPassword)) {
        $_SESSION['signup-error'] = "La contraseña debe tener al menos 6 caracteres, incluyendo una mayúscula, una minúscula, un número y un carácter especial.";
    } elseif ($createPassword !== $confirmPassword) {
        $_SESSION['signup-error'] = "Las contraseñas no coinciden.";
    } elseif (!$phone || !preg_match('/^[0-9]{9,}$/', $phone)) {
        $_SESSION['signup-error'] = "El teléfono debe contener solo números y tener al menos 9 dígitos.";
    } elseif (!$dateBirth) {
        $_SESSION['signup-error'] = "Por favor seleccione una fecha de nacimiento.";
    } elseif (!$address || !preg_match('/^[a-zA-Z0-9\s.,-]+$/', $address)) {
        $_SESSION['signup-error'] = "La dirección solo puede contener letras, números, espacios, puntos, comas y guiones.";
    } elseif (!$gender || !in_array($gender, ['male', 'female', 'other'])) {
        $_SESSION['signup-error'] = "Por favor seleccione un género válido.";
    } else {
        $hashed_password = password_hash($createPassword, PASSWORD_DEFAULT);

        try {
            $pdo = new PDO("mysql:host=localhost;dbname=khloe", "admin18", "admin18021978");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("
                SELECT users_data.*, users_login.* 
                FROM users_data 
                JOIN users_login ON users_data.idUser = users_login.idUser 
                WHERE users_login.usuario = :username OR users_data.email = :email
            ");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $_SESSION['signup-error'] = "El nombre de usuario o email ya existe.";
            } else {
                $insert_user_query = $pdo->prepare("
                    INSERT INTO users_data (name, last_name, email, phone, date_birth, address, gender, is_admin) 
                    VALUES (:name, :last_name, :email, :phone, :date_birth, :address, :gender, 0)
                ");
                $insert_user_query->execute([
                    ':name' => $name,
                    ':last_name' => $lastname,
                    ':email' => $email,
                    ':phone' => $phone,
                    ':date_birth' => $dateBirth,
                    ':address' => $address,
                    ':gender' => $gender
                ]);

                $user_id = $pdo->lastInsertId();
                $insert_login_query = $pdo->prepare("
                    INSERT INTO users_login (idUser, usuario, password, rol) 
                    VALUES (:idUser, :usuario, :password, 'user')
                ");
                $insert_login_query->execute([
                    ':idUser' => $user_id,
                    ':usuario' => $username,
                    ':password' => $hashed_password
                ]);

                $_SESSION['signup-success'] = "Registro exitoso. Por favor inicia sesión.";
                header('location: ' . BASE_URL . '/views/signup.php');
                exit();
            }
        } catch (PDOException $e) {
            $_SESSION['signup-error'] = "Error en la base de datos: " . $e->getMessage();
        }
    }

    $_SESSION['signup-data'] = $_POST;
    header('location: ' . BASE_URL . '/views/signup.php');
    exit();
} else {
    header('location: ' . BASE_URL . '/views/signup.php');
    exit();
}
?>
