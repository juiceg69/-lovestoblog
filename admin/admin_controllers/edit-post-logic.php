<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . "/../../public/config/database.php";

// Verificar si el usuario es administrador
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ' . BASE_URL . '/login');
    exit();
}

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . ADMIN_URL . '/index.php');
    exit();
}

$idNoticia = $_POST['idNoticia'] ?? null;
$titulo = $_POST['titulo'] ?? '';
$fecha = $_POST['fecha'] ?? '';
$texto = $_POST['texto'] ?? '';

if (empty($idNoticia) || empty($titulo) || empty($fecha) || empty($texto)) {
    $_SESSION['edit-post-error'] = "Todos los campos son obligatorios.";
    header('Location: ' . ADMIN_URL . '/edit-post.php?id=' . $idNoticia);
    exit();
}

try {
    global $pdo;
    if (!$pdo) {
        die("La conexión a la base de datos no está definida en dataBase.php");
    }

    // Manejar la carga de la imagen si se proporciona una nueva
    $imagen_url = null;
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));

        if (!in_array($file_extension, $allowed_types)) {
            $_SESSION['edit-post-error'] = "Solo se permiten archivos JPG, JPEG, PNG o GIF.";
            header('Location: ' . ADMIN_URL . '/edit-post.php?id=' . $idNoticia);
            exit();
        }

        if ($_FILES['imagen']['size'] > 2 * 1024 * 1024) {
            $_SESSION['edit-post-error'] = "La imagen es demasiado grande. Tamaño máximo: 2MB.";
            header('Location: ' . ADMIN_URL . '/edit-post.php?id=' . $idNoticia);
            exit();
        }

        $image_name = time() . '_' . uniqid() . '.' . $file_extension;
        $upload_path = __DIR__ . "/../../public/assets/images/" . $image_name;

        if (!is_writable(dirname($upload_path))) {
            $_SESSION['edit-post-error'] = "La carpeta no tiene permisos de escritura.";
            header('Location: ' . ADMIN_URL . '/edit-post.php?id=' . $idNoticia);
            exit();
        }

        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $upload_path)) {
            $_SESSION['edit-post-error'] = "Error al subir la imagen.";
            header('Location: ' . ADMIN_URL . '/edit-post.php?id=' . $idNoticia);
            exit();
        }

        $imagen_url = $image_name; // Guardar solo el nombre del archivo
    } else {
        // Mantener la imagen existente si no se sube una nueva
        $query = "SELECT imagen FROM noticias WHERE idNoticia = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id' => $idNoticia]);
        $noticia = $stmt->fetch(PDO::FETCH_ASSOC);
        $imagen_url = $noticia['imagen'];
    }

    // Actualizar la noticia en la base de datos
    $query = "UPDATE noticias SET titulo = :titulo, imagen = :imagen, texto = :texto, fecha = :fecha WHERE idNoticia = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':titulo' => $titulo,
        ':imagen' => $imagen_url,
        ':texto' => $texto,
        ':fecha' => $fecha,
        ':id' => $idNoticia
    ]);

    $_SESSION['edit-post-success'] = "Noticia actualizada correctamente.";
    header('Location: ' . ADMIN_URL . '/index.php'); // Redirigir al dashboard
    exit();
} catch (PDOException $e) {
    $_SESSION['edit-post-error'] = "Error al actualizar la noticia: " . $e->getMessage();
    header('Location: ' . ADMIN_URL . '/edit-post.php?id=' . $idNoticia);
    exit();
}
?>