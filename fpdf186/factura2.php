<?php
echo realpath('C:/xampp/htdocs/trabajos/consultas nuevas/fpdf186/fpdf186.php');
// Conexión a la base de datos
$servername = "localhost"; // Cambia esto si es necesario
$username = "root"; // Cambia esto si es necesario
$password = ""; // Cambia esto si es necesario
$dbname = "posibleproyecto2.0"; // Cambia esto por tu base de datos

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

    // Consulta SQL
$sql = "SELECT m.nombre, COUNT(*) AS Cotizaciones_Completadas
        FROM mecánicos m
        INNER JOIN cotizaciones c ON m.id_mecanico = c.id_mecanico
        WHERE c.estado = 'completado'
        GROUP BY m.id_mecanico";

// Ejecutar la consulta
$result = $conn->query($sql);

// Crear el PDF
require('fpdf186/fpdf.php'); // Asegúrate de tener la librería FPDF en la ruta correcta

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Reporte de Cotizaciones Completadas por Mecánico', 0, 1, 'C');

// Crear encabezados de la tabla
$pdf->Cell(40, 10, 'Nombre del Mecánico', 1, 0, 'C');
$pdf->Cell(40, 10, 'Cotizaciones Completadas', 1, 1, 'C');

// Recorrer los resultados y agregarlos a la tabla
while ($row = $result->fetch_assoc()) {
    $pdf->Cell(40, 10, $row['nombre'], 1, 0, 'L');
    $pdf->Cell(40, 10, $row['Cotizaciones_Completadas'], 1, 1, 'C');
}

$pdf->Output(); // Genera el PDF
?>