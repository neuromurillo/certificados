<?php
session_start();
if (!isset($_SESSION['autenticado'])) {
    header('Location: login.php');
    exit;
}
?>
<?php
include 'config.php';
$cursos = $conexion->query("SELECT * FROM cursos");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Gestión de Certificados</title>
<link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="user-info" align="right">Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?>
 <a href="logout.php" class="btn">Cerrar sesión</a>
</div>

<h1>Gestión de Cursos y Certificados</h1>

<!-- Botón para crear nuevo curso -->    
<center><a href="crear_curso.php" class="btn">Crear nuevo curso</a></center> 
<hr>
<h2>Cursos existentes</h2>
<table>
<tr><th>Curso</th><th>Fecha</th><th>Acciones</th></tr>
<?php while($curso = $cursos->fetch_assoc()): ?>
<tr>
<td><a href="lista_asistentes.php?curso_id=<?= $curso['id'] ?>" ><?= htmlspecialchars($curso['nombre_curso']) ?></a></td>

<td><?= htmlspecialchars($curso['fecha']) ?></td>
<td>
    <!-- Enlace para editar curso -->
    <a href="editar_curso.php?id=<?= $curso['id'] ?>" class="btn">Editar</a>
    <a href="eliminar_curso.php?id=<?= $curso['id'] ?>" class="btn" onclick="return confirm('¿Seguro que quieres eliminar este curso y todos sus certificados?');">Eliminar</a>
    
|
    <!-- Formulario para subir CSV -->
    <form action="procesar_csv.php" method="post" enctype="multipart/form-data" style="display:inline;">
        <input type="hidden" name="curso_id" value="<?= $curso['id'] ?>">
        <input type="file" name="archivo_csv" accept=".csv" required>
        <button type="submit">Subir CSV</button>
    </form> |

    <!-- Enlace para descargar ZIP -->
    <a href="generar_zip.php?curso_id=<?= $curso['id'] ?>" class="btn">ZIP</a>
<a href="enviar_certificados.php?curso_id=<?= $curso['id'] ?>" class="btn" 
   onclick="return confirm('¿Seguro que quieres enviar los certificados por correo?');">
   Email
</a>
</td>
</tr>
<?php endwhile; ?>
</table>
</body>
</html>
