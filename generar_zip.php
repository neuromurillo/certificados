<?php
session_start();
if (!isset($_SESSION['autenticado'])) {
    header('Location: login.php');
    exit;
}
?>
<?php
require_once('tcpdf/tcpdf.php');
include 'config.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$curso_id = intval($_GET['curso_id']);
$asistentes = $conexion->query("SELECT a.*, c.nombre_curso, c.fecha 
                                FROM asistentes a 
                                JOIN cursos c ON a.curso_id=c.id 
                                WHERE c.id=$curso_id");

$zip = new ZipArchive();
$zipFile = __DIR__ . '/certificados.zip';
if ($zip->open($zipFile, ZipArchive::CREATE) !== TRUE) {
    exit("No se pudo crear el archivo ZIP");
}

while ($datos = $asistentes->fetch_assoc()) {
    // Crear PDF con el mismo diseño que generar_certificado.php
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetMargins(20, 20, 20);
    $pdf->AddPage();

    // ✅ Logo centrado
if (file_exists(__DIR__ . '/logo.png')) {
    $pdf->Image(__DIR__ . '/logo.png', 70, 15, 70);
}

    // Título principal
    $pdf->SetFont('dejavusans', 'B', 28);
    $pdf->SetTextColor(0, 102, 204);
    $pdf->SetXY(20, 50);
    $pdf->Cell(0, 15, 'CERTIFICADO DE FINALIZACIÓN', 0, 1, 'C');

    // Texto introductorio
    $pdf->SetFont('dejavusans', '', 16);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetXY(20, 80);
    $pdf->MultiCell(0, 10, 'La Academia Mexicana de Neurología certifica que:', 0, 'C');

    // Nombre del participante
    $pdf->SetFont('dejavusans', 'B', 26);
    $pdf->SetXY(20, 100);
    $pdf->Cell(0, 15, strtoupper($datos['nombre']), 0, 1, 'C');

    // Curso y fecha
    $pdf->SetFont('dejavusans', '', 16);
    $pdf->SetXY(20, 125);
    $pdf->MultiCell(0, 10, 'Ha completado satisfactoriamente el curso:', 0, 'C');

    $pdf->SetFont('dejavusans', 'B', 20);
    $pdf->SetXY(20, 140);
    $pdf->Cell(0, 15, $datos['nombre_curso'], 0, 1, 'C');

    $pdf->SetFont('dejavusans', '', 14);
    $pdf->SetXY(20, 160);
    $pdf->MultiCell(0, 10, 'Fecha de finalización: '.$datos['fecha'], 0, 'C');

 // ✅ Firma centrada
if (file_exists(__DIR__ . '/firma.png')) {
    $pdf->Image(__DIR__ . '/firma.png', 78, 185, 70);
}

    // Nombre del presidente
    $pdf->SetFont('dejavusans', '', 14);
    $pdf->SetXY(20, 205);
    $pdf->MultiCell(0, 10, 'Presidente de la Academia: Dr. Carlos Cantú Brito', 0, 'C');

// ✅ QR más abajo
$qrContent = $_ENV['APP_URL'] . '/verificar.php?codigo=' . $datos['codigo'];
$pdf->write2DBarcode($qrContent, 'QRCODE,H', 95, 225, 25, 25);

$pdf->SetFont('helvetica', '', 10);
$pdf->SetXY(88, 252); // posición debajo del QR
$pdf->Cell(40, 5, 'Código: ' . $datos['codigo'], 0, 1, 'C');

    // ✅ Pie más abajo
$pdf->SetFont('dejavusans', 'I', 10);
$pdf->SetXY(20, 260);
$pdf->Cell(0, 8, 'Academia Mexicana de Neurología - Certificado válido en línea', 0, 0, 'C');


    // Guardar PDF temporal y añadir al ZIP
    $pdfFile = __DIR__ . "/certificado_{$datos['id']}.pdf";
    $pdf->Output($pdfFile, 'F');
    $zip->addFile($pdfFile, "certificado_{$datos['id']}.pdf");
}

$zip->close();

// Descargar ZIP
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="certificados.zip"');
header('Content-Length: ' . filesize($zipFile));
readfile($zipFile);

// Limpiar archivos temporales
unlink($zipFile);
foreach (glob(__DIR__ . "/certificado_*.pdf") as $file) {
    unlink($file);
}
?>