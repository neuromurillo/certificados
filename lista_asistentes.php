<?php
session_start();
if (!isset($_SESSION['autenticado'])) {
    header('Location: login.php');
    exit;
}
?>
<?php
include 'config.php';

$curso_id = intval($_GET['curso_id']);
$registrosPorPagina = 8;
$paginaActual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$offset = ($paginaActual - 1) * $registrosPorPagina;

$totalRegistros = $conexion->query("SELECT COUNT(*) AS total FROM asistentes WHERE curso_id=$curso_id")->fetch_assoc()['total'];
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

$asistentes = $conexion->query("SELECT * FROM asistentes WHERE curso_id=$curso_id LIMIT $registrosPorPagina OFFSET $offset");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Lista de asistentes</title>
<style>
body { font-family: Arial, sans-serif; background: #f4f6f8; margin: 20px; }
h1 { color: #0066cc; text-align: center; }
table { width: 100%; border-collapse: collapse; margin-top: 20px; background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
th, td { border: 1px solid #ccc; padding: 12px; text-align: center; }
th { background-color: #0066cc; color: #fff; }
a.btn { background: #0066cc; color: #fff; padding: 8px 12px; text-decoration: none; border-radius: 4px; }
a.btn:hover { background: #004999; }
.paginacion { text-align: center; margin-top: 20px; }
.paginacion a { margin: 0 5px; padding: 8px 12px; background: #eee; color: #333; text-decoration: none; border-radius: 4px; }
.paginacion a:hover { background: #0066cc; color: #fff; }
</style>
</head>
<body>
<h1>Lista de asistentes del curso</h1>
<center>
<a href="index.php" class="btn">Volver al panel</a>
</center>
<table>
<tr><th>Nombre</th><th>Email</th><th>Acciones</th></tr>
<?php while ($a = $asistentes->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($a['nombre']) ?></td>
<td><?= htmlspecialchars($a['email']) ?></td>
<td>
<a href="generar_certificado.php?id=<?= $a['id'] ?>" target="_blank" class="btn">Descargar PDF</a>
</td>
</tr>
<?php endwhile; ?>
</table>

<div class="paginacion">
<?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
    <a href="lista_asistentes.php?curso_id=<?= $curso_id ?>&pagina=<?= $i ?>"><?= $i ?></a>
<?php endfor; ?>
</div>
</body>
</html>
