<?php
error_reporting(E_ERROR | E_PARSE);

session_start();
if (!isset($_SESSION['autenticado'])) {
    header('Location: login.php');
    exit;
}

require_once('tcpdf/tcpdf.php');
include 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 0);

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Validar ID y obtener datos
$id = intval($_GET['id']);
$resultado = $conexion->query("SELECT a.*, c.nombre_curso, c.fecha FROM asistentes a JOIN cursos c ON a.curso_id=c.id WHERE a.id=$id");

if ($resultado && $resultado->num_rows > 0) {
    $asistente = $resultado->fetch_assoc();
} else {
    die("❌ No se encontró el asistente con el ID proporcionado.");
}

// Crear PDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetMargins(20, 20, 20);
$pdf->AddPage();

// ✅ Logo centrado
if (file_exists(__DIR__ . '/logo.png')) {
    $pdf->Image(__DIR__ . '/logo.png', 70, 15, 70);
}

// ✅ Título principal
$pdf->SetFont('dejavusans', 'B', 28);
$pdf->SetTextColor(0, 102, 204);
$pdf->SetXY(20, 50);
$pdf->Cell(0, 15, 'CERTIFICADO DE FINALIZACIÓN', 0, 1, 'C');

// ✅ Texto introductorio
$pdf->SetFont('dejavusans', '', 16);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(20, 80);
$pdf->MultiCell(0, 10, 'La Academia Mexicana de Neurología certifica que:', 0, 'C');

// ✅ Nombre del participante
$pdf->SetFont('dejavusans', 'B', 26);
$pdf->SetXY(20, 100);
$pdf->Cell(0, 15, strtoupper($asistente['nombre']), 0, 1, 'C');

// ✅ Curso y fecha
$pdf->SetFont('dejavusans', '', 16);
$pdf->SetXY(20, 125);
$pdf->MultiCell(0, 10, 'Ha completado satisfactoriamente el curso:', 0, 'C');

$pdf->SetFont('dejavusans', 'B', 20);
$pdf->SetXY(20, 140);
$pdf->Cell(0, 15, $asistente['nombre_curso'], 0, 1, 'C');

$pdf->SetFont('dejavusans', '', 14);
$pdf->SetXY(20, 160);
$pdf->MultiCell(0, 10, 'Fecha de finalización: '.$asistente['fecha'], 0, 'C');

// ✅ Firma centrada
if (file_exists(__DIR__ . '/firma.png')) {
    $pdf->Image(__DIR__ . '/firma.png', 78, 185, 70);
}

// ✅ Nombre del presidente justo debajo
$pdf->SetFont('dejavusans', '', 14);
$pdf->SetXY(20, 205);
$pdf->MultiCell(0, 10, 'Presidente de la Academia: Dr. Carlos Cantú Brito', 0, 'C');


// ✅ QR más abajo
$qrContent = $_ENV['APP_URL'] . '/verificar.php?codigo=' . $asistente['codigo'];
$pdf->write2DBarcode($qrContent, 'QRCODE,H', 95, 225, 25, 25);

$pdf->SetFont('helvetica', '', 10);
$pdf->SetXY(88, 252); // posición debajo del QR
$pdf->Cell(40, 5, 'Código: ' . $asistente['codigo'], 0, 1, 'C');

// ✅ Pie más abajo
$pdf->SetFont('dejavusans', 'I', 10);
$pdf->SetXY(20, 260);
$pdf->Cell(0, 8, 'Academia Mexicana de Neurología - Certificado válido en línea', 0, 0, 'C');

// Salida
$pdf->Output('certificado.pdf', 'I');
?>