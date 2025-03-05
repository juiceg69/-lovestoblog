<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . "/../config/database.php";

if (!isset($_SESSION['idUser'])) {
    header('Location: ' . BASE_URL . '/login');
    exit();
}

$user_id = $_SESSION['idUser'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $date_birth = $_POST['date_birth'];
    $address = filter_var($_POST['address'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $gender = filter_var($_POST['gender'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    if (!$name || !preg_match("/^[a-zA-Z\s'-]+$/", $name)) {
        $_SESSION['error'] = "El nombre solo puede contener letras, espacios, apóstrofos o guiones.";
    } elseif (!$lastname || !preg_match("/^[a-zA-Z\s'-]+$/", $lastname)) {
        $_SESSION['error'] = "El apellido solo puede contener letras, espacios, apóstrofos o guiones.";
    } elseif (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Por favor ingrese un email válido.";
    } elseif (!$phone || !preg_match('/^[0-9]{9,}$/', $phone)) {
        $_SESSION['error'] = "El teléfono debe contener solo números y tener al menos 9 dígitos.";
    } elseif (!$date_birth) {
        $_SESSION['error'] = "Por favor seleccione una fecha de nacimiento.";
    } elseif (!$address || !preg_match('/^[a-zA-Z0-9\s.,-]+$/', $address)) {
        $_SESSION['error'] = "La dirección solo puede contener letras, números, espacios, puntos, comas y guiones.";
    } elseif (!$gender || !in_array($gender, ['male', 'female', 'other'])) {
        $_SESSION['error'] = "Por favor seleccione un género válido.";
    } elseif ($password && (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/', $_POST['password']) || strlen($_POST['password']) < 6)) {
        $_SESSION['error'] = "La contraseña debe tener al menos 6 caracteres, incluyendo una mayúscula, una minúscula, un número y un carácter especial.";
    } else {
        try {
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if ($password) {
                $stmt = $pdo->prepare("UPDATE users_data SET name = :name, last_name = :lastname, email = :email, phone = :phone, date_birth = :date_birth, address = :address, gender = :gender WHERE idUser = :idUser");
                $stmt->execute([
                    ':name' => $name,
                    ':lastname' => $lastname,
                    ':email' => $email,
                    ':phone' => $phone,
                    ':date_birth' => $date_birth,
                    ':address' => $address,
                    ':gender' => $gender,
                    ':idUser' => $user_id
                ]);

                $stmt = $pdo->prepare("UPDATE users_login SET password = :password WHERE idUser = :idUser");
                $stmt->execute([':password' => $password, ':idUser' => $user_id]);
            } else {
                $stmt = $pdo->prepare("UPDATE users_data SET name = :name, last_name = :lastname, email = :email, phone = :phone, date_birth = :date_birth, address = :address, gender = :gender WHERE idUser = :idUser");
                $stmt->execute([
                    ':name' => $name,
                    ':lastname' => $lastname,
                    ':email' => $email,
                    ':phone' => $phone,
                    ':date_birth' => $date_birth,
                    ':address' => $address,
                    ':gender' => $gender,
                    ':idUser' => $user_id
                ]);
            }

            $_SESSION['success'] = "Perfil actualizado correctamente.";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error al actualizar el perfil: " . $e->getMessage();
        }
    }

    header('Location: ' . BASE_URL . '/profile');
    exit();
}
?>