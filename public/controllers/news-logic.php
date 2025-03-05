<?php
session_start();
require_once __DIR__ . "/../config/database.php";

// Verificar si el usuario está logueado
if (!isset($_SESSION['idUser'])) {
    header('Location: ' . BASE_URL . '/login.php');
    exit();
}

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $texto = $_POST['texto'];
    $idUser = $_SESSION['idUser'];
    $imagen = $_FILES['imagen'];

    // Validar campos obligatorios
    if (empty($titulo) || empty($texto) || empty($imagen['name'])) {
        $_SESSION['add-news-error'] = "All fields are required.";
        header('Location: ' . BASE_URL . '/add-news');
        exit();
    }

    // Validar la imagen
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    $file_extension = strtolower(pathinfo($imagen['name'], PATHINFO_EXTENSION));

    if (!in_array($file_extension, $allowed_types)) {
        $_SESSION['add-news-error'] = "Only JPG, JPEG, PNG, GIF are allowed .";
        header('Location: ' . BASE_URL . '/add-news');
        exit();
    }

    if ($imagen['size'] > 2 * 1024 * 1024) { // 2MB máximo
        $_SESSION['add-news-error'] = "The image is too big. The maximum size is 2MB.";
        header('Location: ' . BASE_URL . '/add-news');
        exit();
    }

    // Generar un nombre único para la imagen
    $image_name = time() . '_' . uniqid() . '.' . $file_extension;
    $upload_path = __DIR__ . "/../../public/assets/images/" . $image_name;

    // Verificar permisos de la carpeta
    if (!is_writable(dirname($upload_path))) {
        $_SESSION['add-news-error'] = "The folder does not have write permission.";
        exit();
    }

    // Mover la imagen a la carpeta de uploads
    if (!move_uploaded_file($imagen['tmp_name'], $upload_path)) {
        $_SESSION['add-news-error'] = "Error uploading image. Route: " . $upload_path;
        exit();
    }

    // Insertar la noticia en la base de datos
    try {
        $sql = "
            INSERT INTO noticias (titulo, imagen, texto, fecha, idUser)
            VALUES (:titulo, :imagen, :texto, :fecha, :idUser)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':titulo' => $titulo,
            ':imagen' => $image_name,
            ':texto' => $texto,
            ':fecha' => $_POST['fecha'], // Usa la fecha del formulario
            ':idUser' => $idUser
        ]);

        $_SESSION['add-news-success'] = "News posted correctly.";
        header('Location: ' . BASE_URL . '/blog');
        exit();
    } catch (PDOException $e) {
        $_SESSION['add-news-error'] = "Error posting the news: " . $e->getMessage();
        error_log("Error in inserting news: " . $e->getMessage()); // Depuración adicional
        header('Location: ' . BASE_URL . '/add-news');
        exit();
    }
}
?>