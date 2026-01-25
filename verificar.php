<?php
include 'config.php';
if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];
    $resultado = $conexion->query("SELECT a.nombre, c.nombre_curso, c.fecha FROM asistentes a JOIN cursos c ON a.curso_id=c.id WHERE a.codigo='$codigo'");
    if ($resultado->num_rows > 0) {
        $datos = $resultado->fetch_assoc();
        echo "<h1>✅ Certificado válido</h1>";
        echo "<p>Nombre: {$datos['nombre']}</p>";
        echo "<p>Curso: {$datos['nombre_curso']}</p>";
        echo "<p>Fecha: {$datos['fecha']}</p>";
    } else {
        echo "<h1>❌ Código no válido</h1>";
    }
} else {
    echo "<form method='get'>
            <label>Introduce el código del certificado:</label>
            <input type='text' name='codigo'>
            <button type='submit'>Verificar</button>
          </form>";
}
?>
