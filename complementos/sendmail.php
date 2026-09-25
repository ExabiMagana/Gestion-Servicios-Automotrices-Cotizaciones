<?php
// Información extra
$empresa = $_GET['empresa'];
$vehiculo = $_GET['vehiculo'];
$cliente = $_GET['cliente'];
$correo = str_replace('\'',"",$_GET['correo']);

// Ruta al archivo PDF que deseas enviar
$pdfFile = "../fpdf/" . $_GET['ruta'];

// Datos del correo
$to = $correo;
$subject = "Cotización - Taller: " . $empresa;
$message = "Estimad@: " . str_replace('\'', "", $cliente) . "\n\n";
$message .= "A continuación se le adjunta la Cotización realizada para el vehículo: " . str_replace('\'', "", $vehiculo) . "\n\n";
$message .= "Gracias por su preferencia.";

// Verificar la existencia del archivo
if (file_exists($pdfFile)) {
    echo 'El archivo PDF existe. Ruta: ' . realpath($pdfFile) . '<br>';
    // Crear un encabezado que permita adjuntar el archivo
    $separator = md5(time());
    $eol = PHP_EOL;

    // Cabeceras
    $headers  = "From: tecnicotercero2024@gmail.com" . $eol;
    $headers .= "MIME-Version: 1.0" . $eol;
    $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;

    // Mensaje
    $body = "--" . $separator . $eol;
    $body .= "Content-Type: text/plain; charset=\"utf-8\"" . $eol;
    $body .= "Content-Transfer-Encoding: 7bit" . $eol . $eol;
    $body .= $message . $eol . $eol;  // Asegurar que el mensaje esté separado

    // Adjuntar el archivo PDF
    $file_content = chunk_split(base64_encode(file_get_contents($pdfFile)));
    $body .= "--" . $separator . $eol;
    $body .= "Content-Type: application/pdf; name=\"" . basename($pdfFile) . "\"" . $eol;
    $body .= "Content-Disposition: attachment; filename=\"" . basename($pdfFile) . "\"" . $eol;
    $body .= "Content-Transfer-Encoding: base64" . $eol . $eol;
    $body .= $file_content . $eol;

    // Finalizar el mensaje
    $body .= "--" . $separator . "--" . $eol;

    // Enviar el correo
    if (mail($to, $subject, $body, $headers)) {
        header("location:../paginas/cotizaciones.php?opcion=mostrar&exito=si");
    } else {
        echo 'Error al enviar el correo.';
    }
} else {
    die('El archivo PDF no existe. Ruta proporcionada: ' . $pdfFile . ' -- Ruta real: ' . realpath($pdfFile));
}
?>
