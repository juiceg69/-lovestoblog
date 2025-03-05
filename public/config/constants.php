<?php
// ------------------------------
// 1. RUTA Y CONFIGURACIÓN BÁSICA, URL BASE DEL PROYECTO
// ------------------------------
define('BASE_URL', 'http://localhost/rutas/public'); 
define('PUBLIC_PATH', __DIR__ . '/../../public');  
define('ADMIN_PATH', __DIR__ . '/../../admin');

// Ruta base del panel de administración (URL)
define('ADMIN_URL', BASE_URL . '/../admin/pages'); // URL del panel de administración
define('DASHBOARD_URL', ADMIN_URL . '/dashboard'); // URL del dashboard

// ------------------------------
// 2. RECURSOS EXTERNOS (CDN, Fuentes, etc.)
// ------------------------------
define('BOOTSTRAP_ICONS_CSS', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css');
define('BOOTSTRAP_JS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js');
define('JQUERY_JS', 'https://code.jquery.com/jquery-3.6.0.min.js');
define('UNICONS_CSS', "https://unicons.iconscout.com/release/v4.0.8/css/line.css");
define('MONTSERRAT_FONT', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap');
define('BOOTSTRAP_4_CSS', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
define('FONTAWESOME_5_CSS', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
define('BOOTSTRAP_5_CSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css');
define('SWEETALERT_CSS', 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css');
define('SWEETALERT_JS', 'https://cdn.jsdelivr.net/npm/sweetalert2@11');

// ------------------------------
// 3. RUTAS DE ARCHIVOS LOCALES (CSS, JS, IMÁGENES)
// ------------------------------
define('CSS_PATH', BASE_URL . '/assets/css'); 
define('JS_PATH', BASE_URL . '/assets/js'); 
define('IMAGES_PATH', BASE_URL . '/assets/imgs'); 
define('UPLOADS_PATH', PUBLIC_PATH . '/assets/uploads/');

// ------------------------------
// 4. BASE DE DATOS
// ------------------------------
define('DB_HOST', 'localhost');
define('DB_USER', 'admin18');
define('DB_PASS', 'admin18021978');
define('DB_NAME', 'khloe');
?>