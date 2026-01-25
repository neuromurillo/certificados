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
ini_set('display_errors', 1);

$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES['archivo_csv']['tmp_name'])) {
        $curso_id = intval($_POST['curso_id']);
        $archivo = $_FILES['archivo_csv']['tmp_name'];

        if (($handle = fopen($archivo, "r")) !== FALSE) {
            $contador = 0;
            while (($datos = fgetcsv($handle, 1000, ",", '"', "\\")) !== FALSE) {
                if (empty($datos[0]) || trim($datos[0]) === '') {
                    continue;
                }
                $nombre = $conexion->real_escape_string(trim($datos[0]));
                $email = isset($datos[1]) ? $conexion->real_escape_string(trim($datos[1])) : '';
                $codigo = uniqid("CERT-");
                $conexion->query("INSERT INTO asistentes (nombre, email, curso_id, codigo) VALUES ('$nombre', '$email', '$curso_id', '$codigo')");
                $contador++;
            }
            fclose($handle);
            $mensaje = "✅ Se han cargado $contador asistentes correctamente.";
        } else {
            $error = "❌ Error al abrir el archivo CSV.";
        }
    } else {
        $error = "❌ No se ha seleccionado ningún archivo.";
    }
} else {
    $error = "❌ Método no permitido.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Resultado de carga CSV</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f4f6f8;
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 600px;
        margin: 50px auto;
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        text-align: center;
    }
    h1 {
        color: #0066cc;
        margin-bottom: 20px;
    }
    .message {
        font-size: 20px;
        color: green;
        margin-bottom: 20px;
    }
    .error {
        font-size: 20px;
        color: red;
        margin-bottom: 20px;
    }
    a.btn {
        display: inline-block;
        padding: 12px 20px;
        background: #0066cc;
        color: #fff;
        font-size: 18px;
        font-weight: bold;
        text-decoration: none;
        border-radius: 4px;
        margin-top: 20px;
    }
    a.btn:hover {
        background: #004999;
    }
</style>
</head>
<body>
<div class="container">
    <h1>Resultado de la carga</h1>
    <?php if (!empty($mensaje)): ?>
        <p class="message"><?= htmlspecialchars($mensaje) ?></p>
    <?php elseif (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <a href="index.php" class="btn">Volver al panel</a>
</div>
</body>
</html>