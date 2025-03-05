<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../config_adm/dataBase.php";

if (isset($_POST['submit'])) {
    $idUser = filter_var($_POST['idUser'], FILTER_SANITIZE_NUMBER_INT);

    // Verificar que idUser sea válido
    if (!$idUser || !is_numeric($idUser)) {
        $_SESSION['error'] = "Invalid user ID.";
        header('Location: ' . ADMIN_URL . '/manage-users.php');
        exit();
    }

    $name = trim($_POST['name']);
    $lastname = trim($_POST['lastname']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $userrole = filter_var($_POST['userrole'], FILTER_SANITIZE_NUMBER_INT);

    // Validar campos obligatorios
    if (!$name || !$lastname || !$username) {
        $_SESSION['error'] = "Please fill in all required fields.";
        header('Location: ' . ADMIN_URL . '/edit-user.php?id=' . $idUser);
        exit();
    }

    // Validar caracteres permitidos
    if (!preg_match('/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/', $name)) {
        $_SESSION['error'] = "The name can only contain letters and spaces.";
        header('Location: ' . ADMIN_URL . '/edit-user.php?id=' . $idUser);
        exit();
    }

    if (!preg_match('/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/', $lastname)) {
        $_SESSION['error'] = "The last name can only contain letters and spaces.";
        header('Location: ' . ADMIN_URL . '/edit-user.php?id=' . $idUser);
        exit();
    }

    if (!preg_match('/^[A-Za-z0-9_]+$/', $username)) {
        $_SESSION['error'] = "The username can only contain letters, numbers, and underscores.";
        header('Location: ' . ADMIN_URL . '/edit-user.php?id=' . $idUser);
        exit();
    }

    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $update_user_query = $pdo->prepare("
            UPDATE users_data 
            SET name = :name, last_name = :last_name 
            WHERE idUser = :idUser
        ");
        $update_user_query->bindParam(':name', $name, PDO::PARAM_STR);
        $update_user_query->bindParam(':last_name', $lastname, PDO::PARAM_STR);
        $update_user_query->bindParam(':idUser', $idUser, PDO::PARAM_INT);
        $update_user_query->execute();

        $update_login_query = $pdo->prepare("
            UPDATE users_login 
            SET usuario = :usuario, rol = :rol 
            WHERE idUser = :idUser
        ");
        $update_login_query->bindParam(':usuario', $username, PDO::PARAM_STR);
        $update_login_query->bindValue(':rol', ($userrole == 1) ? 'admin' : 'user', PDO::PARAM_STR);
        $update_login_query->bindParam(':idUser', $idUser, PDO::PARAM_INT);
        $update_login_query->execute();

        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $update_password_query = $pdo->prepare("
                UPDATE users_login 
                SET password = :password 
                WHERE idUser = :idUser
            ");
            $update_password_query->bindParam(':password', $hashed_password, PDO::PARAM_STR);
            $update_password_query->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $update_password_query->execute();
        }

        $_SESSION['edit-user-success'] = "User updated successfully.";
        header('Location: ' . ADMIN_URL . '/manage-users.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header('Location: ' . ADMIN_URL . '/edit-user.php?id=' . $idUser);
        exit();
    }
} else {
    header('Location: ' . ADMIN_URL . '/manage-users.php');
    exit();
}
?>