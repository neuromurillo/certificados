<?php
$conexion = new mysqli("localhost", "root", "", "certificados_db_dev");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
