<?php
session_start();
if (!isset($_SESSION['autenticado'])) {
    header('Location: login.php');
    exit;
}
?>
<?php
include 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 0);

if (isset($_GET['id'])) {
    $curso_id = intval($_GET['id']);

    // Eliminar asistentes del curso
    $conexion->query("DELETE FROM asistentes WHERE curso_id = $curso_id");

    // Eliminar el curso
    $conexion->query("DELETE FROM cursos WHERE id = $curso_id");

    // Mensaje de confirmación con diseño
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
    <meta charset='UTF-8'>
    <title>Curso eliminado</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f8; text-align: center; padding: 50px; }
        h1 { color: #0066cc; margin-bottom: 20px; }
        p { font-size: 18px; color: #333; }
        a.btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background: #0066cc;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        a.btn:hover { background: #004999; }
    </style>
    </head>
    <body>
        <h1>✅ Curso eliminado correctamente</h1>
        <p>El curso y sus asistentes han sido eliminados de la base de datos.</p>
        <a href='index.php' class='btn'>Volver al panel</a>
    </body>
    </html>";
} else {
    echo "❌ No se especificó el curso.";
}
?>