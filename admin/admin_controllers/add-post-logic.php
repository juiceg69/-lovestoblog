<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . "/../../public/config/database.php";

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ' . BASE_URL . '/login');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . ADMIN_URL . '/add-post.php');
    exit();
}

$titulo = $_POST['titulo'] ?? '';
$fecha = $_POST['fecha'] ?? '';
$texto = $_POST['texto'] ?? '';
$idUser = $_SESSION['idUser'];

if (empty($titulo) || empty($fecha) || empty($texto) || empty($_FILES['imagen']['name'])) {
    $_SESSION['add-post-error'] = "All fields are required.";
    header('Location: ' . ADMIN_URL . '/add-post.php');
    exit();
}

try {
    global $pdo;
    if (!$pdo) {
        $_SESSION['add-post-error'] = "Error: DataBase conection not available.";
        header('Location: ' . ADMIN_URL . '/add-post.php');
        exit();
    }

    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    $file_extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));

    if (!in_array($file_extension, $allowed_types)) {
        $_SESSION['add-post-error'] = "Only allowed JPG, JPEG, PNG o GIF.";
        header('Location: ' . ADMIN_URL . '/add-post.php');
        exit();
    }

    if ($_FILES['imagen']['size'] > 2 * 1024 * 1024) {
        $_SESSION['add-post-error'] = "The image is too big. Max Size: 2MB.";
        header('Location: ' . ADMIN_URL . '/add-post.php');
        exit();
    }

    $image_name = time() . '_' . uniqid() . '.' . $file_extension;
    $upload_path = __DIR__ . "/../../public/assets/images/" . $image_name;

    if (!is_writable(dirname($upload_path))) {
        $_SESSION['add-post-error'] = "The folder does not have write permissions.";
        header('Location: ' . ADMIN_URL . '/add-post.php');
        exit();
    }

    if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $upload_path)) {
        $_SESSION['add-post-error'] = "Error To upload the image.";
        header('Location: ' . ADMIN_URL . '/add-post.php');
        exit();
    }

    $query = "INSERT INTO noticias (titulo, imagen, texto, fecha, idUser) 
              VALUES (:titulo, :imagen, :texto, :fecha, :idUser)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':titulo' => $titulo,
        ':imagen' => $image_name,
        ':texto' => $texto,
        ':fecha' => $fecha,
        ':idUser' => $idUser
    ]);

    $_SESSION['add-post-success'] = "News added successfully.";
    header('Location: ' . ADMIN_URL . '/index.php');
    exit();
} catch (PDOException $e) {
    $_SESSION['add-post-error'] = "Error adding the news: " . $e->getMessage();
    header('Location: ' . ADMIN_URL . '/add-post.php');
    exit();
}
?>