<?php
session_start();
if (!isset($_SESSION['autenticado'])) {
    header('Location: login.php');
    exit;
}
?>
<?php
include 'config.php';

$id = intval($_GET['id']);
$curso = $conexion->query("SELECT * FROM cursos WHERE id=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre_curso']);
    $fecha = trim($_POST['fecha']);

    if (!empty($nombre) && !empty($fecha)) {
        $conexion->query("UPDATE cursos SET nombre_curso='$nombre', fecha='$fecha' WHERE id=$id");
        header("Location: index.php");
        exit;
    } else {
        $error = "Por favor, completa todos los campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar curso</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f4f6f8;
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 500px;
        margin: 50px auto;
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    h1 {
        text-align: center;
        color: #0066cc;
        margin-bottom: 20px;
    }
    form label {
        display: block;
        font-weight: bold;
        margin-bottom: 8px;
        color: #333;
    }
    form input {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }
    .btn {
        display: inline-block;
        width: 100%;
        padding: 12px;
        background: #0066cc;
        color: #fff;
        font-size: 18px;
        font-weight: bold;
        text-align: center;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    .btn:hover {
        background: #004999;
    }
    .error {
        color: red;
        text-align: center;
        margin-bottom: 15px;
    }
    .back-link {
        display: block;
        text-align: center;
        margin-top: 20px;
        color: #0066cc;
        text-decoration: none;
        font-weight: bold;
    }
    .back-link:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>
<div class="container">
    <h1>Editar curso</h1>
    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post">
        <label for="nombre_curso">Nombre del curso:</label>
        <input type="text" id="nombre_curso" name="nombre_curso" value="<?= htmlspecialchars($curso['nombre_curso']) ?>" required>

        <label for="fecha">Fecha del curso:</label>
        <input type="date" id="fecha" name="fecha" value="<?= htmlspecialchars($curso['fecha']) ?>" required>

        <button type="submit" class="btn">Guardar cambios</button>
    </form>
</div>
</body>
</html>