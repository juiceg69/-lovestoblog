<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . "/../config/database.php";

if (isset($_POST['submit'])) {
    $username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = $_POST['password'];

    $_SESSION['signin-data']['username'] = $username;

    if (empty($username) && empty($password)) {
        $_SESSION['login-error'] = "Username and password are required.";
    } elseif (empty($username)) {
        $_SESSION['login-error'] = "Username is required.";
    } elseif (empty($password)) {
        $_SESSION['login-error'] = "Password is required.";
    } else {
        try {
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $query = "SELECT users_login.*, users_data.is_admin 
                      FROM users_login 
                      JOIN users_data ON users_login.idUser = users_data.idUser 
                      WHERE users_login.usuario = :username";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() == 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (password_verify($password, $user['password'])) {
                    $_SESSION['idUser'] = $user['idUser'];
                    $_SESSION['username'] = $user['usuario'];
                    $_SESSION['is_admin'] = $user['is_admin'];
                    unset($_SESSION['signin-data']);

                    // Establecer mensaje de éxito
                    $_SESSION['login-success'] = ((int)$user['is_admin'] === 1) 
                        ? "You have successfully logged in as Admin!"
                        : "You have successfully logged in as user!";

                    // Redirección según el rol
                    if ((int)$user['is_admin'] === 1) {
                        header('Location: ' . ADMIN_URL . '/index.php');
                    } else {
                        header('Location: ' . BASE_URL . '/index');
                    }
                    exit();
                } else {
                    $_SESSION['login-error'] = "Incorrect password.";
                }
            } else {
                $_SESSION['login-error'] = "User not found.";
            }
        } catch (PDOException $e) {
            $_SESSION['login-error'] = "Database error: " . $e->getMessage();
        }
    }

    header('Location: ' . BASE_URL . '/views/login.php');
    exit();
}
?>




