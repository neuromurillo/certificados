<?php
session_start();
if (!isset($_SESSION['autenticado'])) {
    header('Location: login.php');
    exit;
}
?>
<?php
require_once('tcpdf/tcpdf.php');
require_once __DIR__ . '/vendor/autoload.php'; // Autoload de Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'config.php';


$curso_id = intval($_GET['curso_id']);
$asistentes = $conexion->query("SELECT a.*, c.nombre_curso, c.fecha FROM asistentes a JOIN cursos c ON a.curso_id=c.id WHERE c.id=$curso_id");

while ($datos = $asistentes->fetch_assoc()) {
    // ✅ Generar PDF con TCPDF
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetMargins(20, 20, 20);
    $pdf->AddPage();

    // Logo centrado
    if (file_exists(__DIR__ . '/logo.png')) {
        $pdf->Image(__DIR__ . '/logo.png', 85, 15, 40);
    }

    // Título
    $pdf->SetFont('dejavusans', 'B', 28);
    $pdf->SetTextColor(0, 102, 204);
    $pdf->SetXY(20, 50);
    $pdf->Cell(0, 15, 'CERTIFICADO DE FINALIZACIÓN', 0, 1, 'C');

$pdf->SetTextColor(0, 0, 0); // negro

    // Texto
    $pdf->SetFont('dejavusans', '', 16);
    $pdf->SetXY(20, 80);
    $pdf->MultiCell(0, 10, 'La Academia Mexicana de Neurología certifica que:', 0, 'C');

    // Nombre
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
    $pdf->SetFont('dejavusans', '', 14);
    $pdf->SetXY(20, 205);
    $pdf->MultiCell(0, 10, 'Presidente de la Academia: Dr. Carlos Cantú Brito', 0, 'C');

// ✅ QR más abajo
$qrContent = $qrContent = 'http://192.168.100.36/certificados/verificar.php?codigo='.$datos['codigo'];
$pdf->write2DBarcode($qrContent, 'QRCODE,H', 95, 225, 25, 25);

$pdf->SetFont('helvetica', '', 10);
$pdf->SetXY(88, 252); // posición debajo del QR
$pdf->Cell(40, 5, 'Código: ' . $datos['codigo'], 0, 1, 'C');

// ✅ Pie más abajo
$pdf->SetFont('dejavusans', 'I', 10);
$pdf->SetXY(20, 260);
$pdf->Cell(0, 8, 'Academia Mexicana de Neurología - Certificado válido en línea', 0, 0, 'C');

    // Guardar PDF temporal
    $pdfFile = __DIR__ . "/certificado_{$datos['id']}.pdf";
    $pdf->Output($pdfFile, 'F');

    // ✅ Enviar correo con PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp-relay.brevo.com'; // Cambia por tu servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'a07ea1001@smtp-brevo.com'; // Cambia por tu email
        $mail->Password = getenv('SMTP_KEY'); // Cambia por tu contraseña
        $mail->SMTPSecure = 'tls'; // O 'ssl' según tu servidor
        $mail->Port = 2525; // O 465 si usas SSL

        $mail->setFrom('luismurillo@ipao.com.mx', 'Academia Mexicana de Neurología');
        $mail->addAddress($datos['email']); // Email del asistente
        $mail->Subject = 'Tu certificado del curso '.$datos['nombre_curso'];
        $mail->Body = "Estimado {$datos['nombre']},\n\nAdjunto encontrarás tu certificado del curso {$datos['nombre_curso']}.\n\nSaludos,\nAcademia Mexicana de Neurología";

	$mail->addAttachment($pdfFile);
        $mail->send();
        echo "✅ Certificado enviado a {$datos['email']}<br>";
    } catch (Exception $e) {
        echo "❌ Error al enviar a {$datos['email']}: {$mail->ErrorInfo}<br>";
    }

    // Eliminar PDF temporal
    unlink($pdfFile);
}

echo "<!DOCTYPE html>
<html lang='es'>
<head>
<meta charset='UTF-8'>
<title>Envío completado</title>
<style>
body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
h1 { color: #0066cc; }
p { font-size: 18px; }
a { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #0066cc; color: #fff; text-decoration: none; border-radius: 5px; }
a:hover { background: #004999; }
</style>
</head>
<body>
<h1>✅ Envío completado</h1>
<p>Todos los certificados han sido enviados correctamente.</p>
<a href='index.php'>Volver al panel</a>
</body>
</html>";

?>