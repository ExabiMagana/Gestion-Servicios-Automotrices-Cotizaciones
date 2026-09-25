<?php
require('fpdf.php');
include '../database/reportesConsultas.php';
 if (isset($_GET['generar'])) {

    $result=consulta1PDF();

    // PDF
    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Reporte de Ventas por Cliente y Mes', 0, 1, 'C');

    // Crear encabezados de la tabla
    $pdf->Cell(25, 10, 'Año', 1, 0, 'C');
    $pdf->Cell(25, 10, 'Mes', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Ingresos Totales', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Número de Cotizaciones', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Cliente', 1, 1, 'C');

    // Recorrer los resultados y agregarlos a la tabla
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(25, 10, $row['Año'], 1, 0, 'C');
        $pdf->Cell(25, 10, $row['Mes'], 1, 0, 'C');
        $pdf->Cell(40, 10, number_format($row['Ingresos_Totales'], 2, ',', '.'), 1, 0, 'C');
        $pdf->Cell(40, 10, $row['Numero_Cotizaciones'], 1, 0, 'C');
        $pdf->Cell(40, 10, $row['cliente'], 1, 1, 'L');
    }

    
    // Cerrar conexión
    conectarse()->close();

    // Generar el PDF
   
        $pdf->Output('I', str_replace(" ","",'Cotizacion.pdf'));
 }


?>