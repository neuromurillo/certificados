<?php

// Cargar autoload de Composer (si no se ha cargado antes)
require_once __DIR__ . '/vendor/autoload.php';

// Cargar variables del archivo .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Crear conexión usando variables de entorno
$conexion = new mysqli(
    $_ENV['DB_HOST'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS'],
    $_ENV['DB_NAME']
);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
