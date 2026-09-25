<?php
require('fpdf.php');
include '../database/reportesConsultas.php';

class PDF extends FPDF {
    public $direccion_empresa;
    public $nombre;
    public $correo;

    function Footer() {
        $this->SetY(-20);
        $this->SetFont('FreeSerif', 'I', 10);
        $this->SetTextColor(137,137,137);
        $this->Cell(0, 4, $this->nombre, 0, 1, 'C');
        $this->Cell(0, 4, $this->direccion_empresa, 0, 1, 'C');
        $this->Cell(0, 4, $this->correo, 0, 1, 'C');
    }
}

if (isset($_POST['generar'])) {

    if ($_POST['tipo-reporte'] == "reporte1") {

        $sql_empresa = "SELECT * FROM empresa LIMIT 1";
        $result_empresa = conectarse()->query($sql_empresa);
        $empresa = $result_empresa->fetch_assoc();

        $result = consulta1PDF();

        // PDF
        $pdf = new PDF('P', 'mm', 'A4');
        $pdf->nombre = $empresa['nombre'];
        $pdf->slogan = ($empresa['slogan'] !== "Sin Especificar") ? ": " . $empresa['slogan'] : "";
        $pdf->direccion_empresa = $empresa['direccion'];
        $pdf->correo = ($empresa['correo'] !== "Sin Especificar") ? $empresa['correo'] : "- -";
        $pdf->AddPage();

        $pdf->SetMargins(10, 10, 10);
        $pdf->SetDrawColor(180,182,186);
        $pdf->SetFillColor(180,182,186);
        $pdf->Rect(0, 4, 1000, 30, "F");

        if (!empty($empresa['logo'])) {
            $pdf->Image($empresa['logo'], 6, 6, 25);
        }

        $pdf->AddFont('FreeSerif', '', 'FreeSerif.php');
        $pdf->AddFont('FreeSerif', 'B', 'FreeSerifBold.php');
        $pdf->AddFont('FreeSerif', 'I', 'FreeSerifItalic.php');
        $pdf->AddFont('FreeSerif', 'BI', 'FreeSerifBoldItalic.php');

        // Información del taller
        $pdf->SetY(7);
        $pdf->Cell(30);
        $pdf->SetFont('FreeSerif', 'B', 12);
        $pdf->Cell(30, 5, utf8_decode($empresa['nombre'] . " " . $pdf->slogan), 0, 1, 'L');
        $pdf->Cell(30);
        $pdf->SetFont('FreeSerif', '', 10);
        $pdf->Cell(30, 5, 'Telefono: ' . $empresa['fijo'], 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->Cell(30, 5, utf8_decode('Celular: ' . $empresa['celular']), 0, 1, 'L');
        $pdf->Cell(30);

        if ($empresa['correo'] !== "Sin Información") {
            $pdf->Cell(0, 5, utf8_decode('Correo: ' . $empresa['correo']), 0, 1, 'L');
            $pdf->Cell(30);
        }

        $pdf->MultiCell(150, 5, utf8_decode('Dirección: ' . $empresa['direccion']), 0, 'L');
        $pdf->Ln(20); // Espacio adicional

        $pdf->SetFont('FreeSerif', 'B', 18);
        $pdf->Cell(0, 10, utf8_decode('Reporte 1'), 0, 1, "C");
        $pdf->SetFont('FreeSerif', 'I', 18);
        $pdf->Cell(0, 10, utf8_decode('Reporte de Ingresos Anuales y Promedio Mensual por Estado'), 0, 1, "C");

        // Encabezado de fecha seleccionado
       	if ($_POST['opcionFecha'] == "general") {
            $pdf->Cell(0, 10, utf8_decode("Datos Generales"), 0, 1, "C");
        } elseif ($_POST['opcionFecha'] == "anio-actual") {
            $pdf->Cell(0, 10, date('Y'), 0, 1, "C");
        } elseif ($_POST['opcionFecha'] == "anio-especifico") {
        	if ($_POST['anio']=="") {
            	$pdf->Cell(0, 10, utf8_decode("Datos Generales"), 0, 1, "C");
        	}else {
        		$pdf->Cell(0, 10, utf8_decode($_POST['anio'] ), 0, 1, "C");
        	}
        } else {
        	if ($_POST['fechaInicio']=="" OR $_POST['fechaFin']=="") {
            	$pdf->Cell(0, 10, utf8_decode("Datos Generales"), 0, 1, "C");
        	}else {
        		$pdf->Cell(0, 10, utf8_decode($_POST['fechaInicio'] . " - " . $_POST['fechaFin']), 0, 1, "C");
        	}
        }
        $pdf->Ln(10);

        // Verifica si la consulta devolvió resultados
        if ($result->num_rows == 0) {
            $pdf->Cell(0, 10, utf8_decode("No se encontró ningún dato coincidente"), 1, 1, 'C');
        } else {
            // Crear encabezados de la tabla
            $pdf->SetFont('FreeSerif', 'B', 12);
            $pdf->SetDrawColor(180,182,186);
            $pdf->SetFillColor(34,40,49);
            $pdf->SetTextColor(256,256,256);
            $pdf->Cell(25, 10, utf8_decode('Año'), "LB", 0, 'C', 1);
            $pdf->Cell(40, 10, utf8_decode('Ingresos Anuales'), "B", 0, 'C', 1);
            $pdf->Cell(40, 10, utf8_decode('Promedio Mensual'), "B", 0, 'C', 1);
            $pdf->Cell(30, 10, utf8_decode('Cotizaciones'), "B", 0, 'C', 1);
            $pdf->Cell(50, 10, utf8_decode('Estado de Cotización'), "BR", 1, 'C', 1);

            $pdf->SetFont('FreeSerif', '', 12);
            $pdf->SetTextColor(1,1,1);

            while ($row = $result->fetch_assoc()) {
                $pdf->Cell(25, 10, $row['Año'], "LB", 0, 'C');
                $pdf->Cell(40, 10, number_format($row['Ingresos_Anuales'], 2, ',', '.'), "B", 0, 'C');
                $pdf->Cell(40, 10, number_format($row['Promedio_Mensual'], 2, ',', '.'), "B", 0, 'C');
                $pdf->Cell(30, 10, $row['Pedidos_Anuales'], "B", 0, 'C');
                $pdf->Cell(50, 10, $row['estado'], "BR", 1, 'C');
            }
        }

        // Cerrar conexión
        conectarse()->close();

        // Generar el PDF
        $pdf->Output("I", 'Reporte 1: IngresosEstado.pdf');
    }

    if ($_POST['tipo-reporte'] == "reporte2") { // Cambia a "reporte2" para este nuevo tipo de reporte

	    $sql_empresa = "SELECT * FROM empresa LIMIT 1";
	    $result_empresa = conectarse()->query($sql_empresa);
	    $empresa = $result_empresa->fetch_assoc();

	    // Llamar a la consulta2 para obtener los resultados
	    $result = consulta2PDF(); // Asegúrate de que esta función retorne el resultado como en la consulta original

	    // PDF
	    $pdf = new PDF('P', 'mm', 'A4');
	    $pdf->nombre = $empresa['nombre'];
	    $pdf->slogan = ($empresa['slogan'] !== "Sin Especificar") ? ": " . $empresa['slogan'] : "";
	    $pdf->direccion_empresa = $empresa['direccion'];
	    $pdf->correo = ($empresa['correo'] !== "Sin Especificar") ? $empresa['correo'] : "- -";
	    $pdf->AddPage();

	    $pdf->SetMargins(10, 10, 10);
	    $pdf->SetDrawColor(180,182,186);
	    $pdf->SetFillColor(180,182,186);
	    $pdf->Rect(0, 4, 1000, 30, "F");

	    if (!empty($empresa['logo'])) {
	        $pdf->Image($empresa['logo'], 6, 6, 25);
	    }

	    $pdf->AddFont('FreeSerif', '', 'FreeSerif.php');
	    $pdf->AddFont('FreeSerif', 'B', 'FreeSerifBold.php');
	    $pdf->AddFont('FreeSerif', 'I', 'FreeSerifItalic.php');
	    $pdf->AddFont('FreeSerif', 'BI', 'FreeSerifBoldItalic.php');

	    // Información del taller
	    $pdf->SetY(7);
	    $pdf->Cell(30);
	    $pdf->SetFont('FreeSerif', 'B', 12);
	    $pdf->Cell(30, 5, utf8_decode($empresa['nombre'] . " " . $pdf->slogan), 0, 1, 'L');
	    $pdf->Cell(30);
	    $pdf->SetFont('FreeSerif', '', 10);
	    $pdf->Cell(30, 5, 'Telefono: ' . $empresa['fijo'], 0, 0, 'L');
	    $pdf->Cell(5);
	    $pdf->Cell(30, 5, utf8_decode('Celular: ' . $empresa['celular']), 0, 1, 'L');
	    $pdf->Cell(30);

	    if ($empresa['correo'] !== "Sin Información") {
	        $pdf->Cell(0, 5, utf8_decode('Correo: ' . $empresa['correo']), 0, 1, 'L');
	        $pdf->Cell(30);
	    }

	    $pdf->MultiCell(150, 5, utf8_decode('Dirección: ' . $empresa['direccion']), 0, 'L');
	    $pdf->Ln(20); // Espacio adicional

	    $pdf->SetFont('FreeSerif', 'B', 18);
	    $pdf->Cell(0, 10, utf8_decode('Reporte 2'), 0, 1, "C");
	    $pdf->SetFont('FreeSerif', 'I', 18);
	    $pdf->Cell(0, 10, utf8_decode('Reporte 2: Reporte de Efectividad de los Mecánicos'), 0, 1, "C");

	    // Encabezado de fecha seleccionado
       	if ($_POST['opcionFecha'] == "general") {
            $pdf->Cell(0, 10, utf8_decode("Datos Generales"), 0, 1, "C");
        } elseif ($_POST['opcionFecha'] == "anio-actual") {
            $pdf->Cell(0, 10, date('Y'), 0, 1, "C");
        } elseif ($_POST['opcionFecha'] == "mes-actual") {
            $pdf->Cell(0, 10, date('M'), 0, 1, "C");
        } elseif ($_POST['opcionFecha'] == "mes-especifico") {
        	if ($_POST['mes']=="") {
            	$pdf->Cell(0, 10, utf8_decode("Datos Generales"), 0, 1, "C");
        	}else {
        		$mesNumero = $_POST['mes'];
				$nombresMeses = [
				    1 => 'Enero',
				    2 => 'Febrero',
				    3 => 'Marzo',
				    4 => 'Abril',
				    5 => 'Mayo',
				    6 => 'Junio',
				    7 => 'Julio',
				    8 => 'Agosto',
				    9 => 'Septiembre',
				    10 => 'Octubre',
				    11 => 'Noviembre',
				    12 => 'Diciembre'
				];
				$nombreMes = $nombresMeses[$mesNumero];

	            $pdf->Cell(0, 10, utf8_decode($nombreMes), 0, 1, "C");
        	}
        } elseif ($_POST['opcionFecha'] == "anio-especifico") {
        	if ($_POST['anio']=="") {
            	$pdf->Cell(0, 10, utf8_decode("Datos Generales"), 0, 1, "C");
        	}else {
        		$pdf->Cell(0, 10, utf8_decode($_POST['anio'] ), 0, 1, "C");
        	}
        } else {
        	if ($_POST['fechaInicio']=="" OR $_POST['fechaFin']=="") {
            	$pdf->Cell(0, 10, utf8_decode("Datos Generales"), 0, 1, "C");
        	}else {
        		$pdf->Cell(0, 10, utf8_decode($_POST['fechaInicio'] . " - " . $_POST['fechaFin']), 0, 1, "C");
        	}
        }
        $pdf->Ln(10);

	    // Verifica si la consulta devolvió resultados
	    if ($result->num_rows == 0) {
	        $pdf->Cell(0, 10, utf8_decode("No se encontró ningún dato coincidente"), 1, 1, 'C');
	    } else {
	        // Crear encabezados de la tabla
	        $pdf->SetFont('FreeSerif', 'B', 12);
	        $pdf->SetDrawColor(180,182,186);
	        $pdf->SetFillColor(34,40,49);
	        $pdf->SetTextColor(256,256,256);
	        $pdf->Cell(34, 10, utf8_decode('Mecánico'), "LB", 0, 'C', 1);
	        $pdf->Cell(24, 10, utf8_decode('Completadas'), "B", 0, 'C', 1);
	        $pdf->Cell(29, 10, utf8_decode('En Proceso'), "B", 0, 'C', 1);
	        $pdf->Cell(24, 10, utf8_decode('Cotizaciones'), "B", 0, 'C', 1);
	        $pdf->Cell(39, 10, utf8_decode('Ingresos Generados'), "B", 0, 'C', 1);
	        $pdf->Cell(39, 10, utf8_decode('Promedio Ingresos'), "BR", 1, 'C', 1);

	        $pdf->SetFont('FreeSerif', '', 12);
	        $pdf->SetTextColor(1,1,1);

	        while ($row = $result->fetch_assoc()) {
	            $pdf->Cell(34, 10, utf8_decode($row['nombre']), "LB", 0, 'C');
	            $pdf->Cell(24, 10, ($row['Cotizaciones_Completadas'] == 0 ? 'Ninguna' : $row['Cotizaciones_Completadas']), "B", 0, 'C');
	            $pdf->Cell(29, 10, ($row['Cotizaciones_Pendientes'] == 0 ? 'Ninguna' : $row['Cotizaciones_Pendientes']), "B", 0, 'C');
	            $pdf->Cell(24, 10, ($row['Total_Cotizaciones'] == 0 ? 'Ninguna' : $row['Total_Cotizaciones']), "B", 0, 'C');
	            $pdf->Cell(39, 10, "$" . number_format($row['Ingresos_Generados'], 2, ',', '.'), "B", 0, 'C');
	            $pdf->Cell(39, 10, "$" . number_format($row['Promedio_Ingreso'], 2, ',', '.'), "BR", 1, 'C');
	        }
	    }

	    // Cerrar conexión
	    conectarse()->close();

	    // Generar el PDF
	    $pdf->Output("I",'Reporte 2: EfectividadMecánicos.pdf');
	}

	if ($_POST['tipo-reporte'] == "reporte3") {

	    $sql_empresa = "SELECT * FROM empresa LIMIT 1";
	    $result_empresa = conectarse()->query($sql_empresa);
	    $empresa = $result_empresa->fetch_assoc();

	    // Llamar a la consulta2 para obtener los resultados
	    $result = consulta3PDF(); // Asegúrate de que esta función retorne el resultado como en la consulta original

	    // PDF
	    $pdf = new PDF('P', 'mm', 'A4');
	    $pdf->nombre = $empresa['nombre'];
	    $pdf->slogan = ($empresa['slogan'] !== "Sin Especificar") ? ": " . $empresa['slogan'] : "";
	    $pdf->direccion_empresa = $empresa['direccion'];
	    $pdf->correo = ($empresa['correo'] !== "Sin Especificar") ? $empresa['correo'] : "- -";
	    $pdf->AddPage();

	    $pdf->SetMargins(10, 10, 10);
	    $pdf->SetDrawColor(180,182,186);
	    $pdf->SetFillColor(180,182,186);
	    $pdf->Rect(0, 4, 1000, 30, "F");

	    if (!empty($empresa['logo'])) {
	        $pdf->Image($empresa['logo'], 6, 6, 25);
	    }

	    $pdf->AddFont('FreeSerif', '', 'FreeSerif.php');
	    $pdf->AddFont('FreeSerif', 'B', 'FreeSerifBold.php');
	    $pdf->AddFont('FreeSerif', 'I', 'FreeSerifItalic.php');
	    $pdf->AddFont('FreeSerif', 'BI', 'FreeSerifBoldItalic.php');

	    // Información del taller
	    $pdf->SetY(7);
	    $pdf->Cell(30);
	    $pdf->SetFont('FreeSerif', 'B', 12);
	    $pdf->Cell(30, 5, utf8_decode($empresa['nombre'] . " " . $pdf->slogan), 0, 1, 'L');
	    $pdf->Cell(30);
	    $pdf->SetFont('FreeSerif', '', 10);
	    $pdf->Cell(30, 5, 'Telefono: ' . $empresa['fijo'], 0, 0, 'L');
	    $pdf->Cell(5);
	    $pdf->Cell(30, 5, utf8_decode('Celular: ' . $empresa['celular']), 0, 1, 'L');
	    $pdf->Cell(30);

	    if ($empresa['correo'] !== "Sin Información") {
	        $pdf->Cell(0, 5, utf8_decode('Correo: ' . $empresa['correo']), 0, 1, 'L');
	        $pdf->Cell(30);
	    }

	    $pdf->MultiCell(150, 5, utf8_decode('Dirección: ' . $empresa['direccion']), 0, 'L');
	    $pdf->Ln(20); // Espacio adicional

	    $pdf->SetFont('FreeSerif', 'B', 18);
	    $pdf->Cell(0, 10, utf8_decode('Reporte 3'), 0, 1, "C");
	    $pdf->SetFont('FreeSerif', 'I', 18);
	    $pdf->Cell(0, 10, utf8_decode('Rentabilidad de Servicios'), 0, 1, "C");

	    // Encabezado de fecha seleccionado
       	if ($_POST['opcionFecha'] == "general") {
            $pdf->Cell(0, 10, utf8_decode("Datos Generales"), 0, 1, "C");
        } elseif ($_POST['opcionFecha'] == "anio-actual") {
            $pdf->Cell(0, 10, date('Y'), 0, 1, "C");
        } elseif ($_POST['opcionFecha'] == "mes-actual") {
            $pdf->Cell(0, 10, date('M'), 0, 1, "C");
        } elseif ($_POST['opcionFecha'] == "mes-especifico") {
        	if ($_POST['mes']=="") {
            	$pdf->Cell(0, 10, utf8_decode("Datos Generales"), 0, 1, "C");
        	}else {
        		$mesNumero = $_POST['mes'];
				$nombresMeses = [
				    1 => 'Enero',
				    2 => 'Febrero',
				    3 => 'Marzo',
				    4 => 'Abril',
				    5 => 'Mayo',
				    6 => 'Junio',
				    7 => 'Julio',
				    8 => 'Agosto',
				    9 => 'Septiembre',
				    10 => 'Octubre',
				    11 => 'Noviembre',
				    12 => 'Diciembre'
				];
				$nombreMes = $nombresMeses[$mesNumero];

	            $pdf->Cell(0, 10, utf8_decode($nombreMes), 0, 1, "C");
        	}
        } elseif ($_POST['opcionFecha'] == "anio-especifico") {
        	if ($_POST['anio']=="") {
            	$pdf->Cell(0, 10, utf8_decode("Datos Generales"), 0, 1, "C");
        	}else {
        		$pdf->Cell(0, 10, utf8_decode($_POST['anio'] ), 0, 1, "C");
        	}
        } else {
        	if ($_POST['fechaInicio']=="" OR $_POST['fechaFin']=="") {
            	$pdf->Cell(0, 10, utf8_decode("Datos Generales"), 0, 1, "C");
        	}else {
        		$pdf->Cell(0, 10, utf8_decode($_POST['fechaInicio'] . " - " . $_POST['fechaFin']), 0, 1, "C");
        	}
        }
        $pdf->Ln(10);

	    // Crear encabezados de la tabla
	    $pdf->SetFont('FreeSerif', 'B', 12);
	    $pdf->SetFillColor(34, 40, 49);
	    $pdf->SetTextColor(256, 256, 256);
	    $pdf->Cell(70, 10, utf8_decode('Servicio'), 1, 0, 'C', 1);
	    $pdf->Cell(50, 10, utf8_decode('Cantidad de Apariciones'), 1, 0, 'C', 1);
	    $pdf->Cell(70, 10, utf8_decode('Ingresos Generados'), 1, 1, 'C', 1);

	    // Añadir los datos de la consulta
	    $pdf->SetFont('FreeSerif', '', 12);
	    $pdf->SetTextColor(1, 1, 1);
	    if ($result->num_rows == 0) {
	        $pdf->Cell(0, 10, utf8_decode("No se encontró ningún dato coincidente"), 1, 1, 'C');
	    } else {
	        while ($row = $result->fetch_assoc()) {
	            $pdf->Cell(70, 10, utf8_decode($row['nombre_servicio']), 1, 0, 'C');
	            $pdf->Cell(50, 10, $row['total_cotizaciones'], 1, 0, 'C');
	            $pdf->Cell(70, 10, '$'.number_format($row['ingresos_generados'], 2, ',', '.'), 1, 1, 'C');
	        }
	    }
	    $pdf->Output('I', 'Reporte 3: ServiciosRentables.pdf');
	}

	if ($_POST['tipo-reporte'] == "reporte4") {

	    $sql_empresa = "SELECT * FROM empresa LIMIT 1";
	    $result_empresa = conectarse()->query($sql_empresa);
	    $empresa = $result_empresa->fetch_assoc();

	    // Llamar a la consulta2 para obtener los resultados
	    $result = consulta4PDF(); // Asegúrate de que esta función retorne el resultado como en la consulta original

	    // PDF
	    $pdf = new PDF('P', 'mm', 'A4');
	    $pdf->nombre = $empresa['nombre'];
	    $pdf->slogan = ($empresa['slogan'] !== "Sin Especificar") ? ": " . $empresa['slogan'] : "";
	    $pdf->direccion_empresa = $empresa['direccion'];
	    $pdf->correo = ($empresa['correo'] !== "Sin Especificar") ? $empresa['correo'] : "- -";
	    $pdf->AddPage();

	    $pdf->SetMargins(10, 10, 10);
	    $pdf->SetDrawColor(180,182,186);
	    $pdf->SetFillColor(180,182,186);
	    $pdf->Rect(0, 4, 1000, 30, "F");

	    if (!empty($empresa['logo'])) {
	        $pdf->Image($empresa['logo'], 6, 6, 25);
	    }

	    $pdf->AddFont('FreeSerif', '', 'FreeSerif.php');
	    $pdf->AddFont('FreeSerif', 'B', 'FreeSerifBold.php');
	    $pdf->AddFont('FreeSerif', 'I', 'FreeSerifItalic.php');
	    $pdf->AddFont('FreeSerif', 'BI', 'FreeSerifBoldItalic.php');

	    // Información del taller
	    $pdf->SetY(7);
	    $pdf->Cell(30);
	    $pdf->SetFont('FreeSerif', 'B', 12);
	    $pdf->Cell(30, 5, utf8_decode($empresa['nombre'] . " " . $pdf->slogan), 0, 1, 'L');
	    $pdf->Cell(30);
	    $pdf->SetFont('FreeSerif', '', 10);
	    $pdf->Cell(30, 5, 'Telefono: ' . $empresa['fijo'], 0, 0, 'L');
	    $pdf->Cell(5);
	    $pdf->Cell(30, 5, utf8_decode('Celular: ' . $empresa['celular']), 0, 1, 'L');
	    $pdf->Cell(30);

	    if ($empresa['correo'] !== "Sin Información") {
	        $pdf->Cell(0, 5, utf8_decode('Correo: ' . $empresa['correo']), 0, 1, 'L');
	        $pdf->Cell(30);
	    }

	    $pdf->MultiCell(150, 5, utf8_decode('Dirección: ' . $empresa['direccion']), 0, 'L');
	    $pdf->Ln(20); // Espacio adicional

	    $pdf->SetFont('FreeSerif', 'B', 18);
	    $pdf->Cell(0, 10, utf8_decode('Reporte 4'), 0, 1, "C");
	    $pdf->SetFont('FreeSerif', 'I', 18);
	    $pdf->Cell(0, 10, utf8_decode('Demanda de Servicios por Vehículo'), 0, 1, "C");

	    // Encabezado de fecha seleccionado
       	if ($_POST['opcionFecha'] == "general") {
            $pdf->Cell(0, 10, utf8_decode("Datos Generales"), 0, 1, "C");
        } elseif ($_POST['opcionFecha'] == "anio-actual") {
            $pdf->Cell(0, 10, date('Y'), 0, 1, "C");
        } elseif ($_POST['opcionFecha'] == "mes-actual") {
            $pdf->Cell(0, 10, date('M'), 0, 1, "C");
        } elseif ($_POST['opcionFecha'] == "mes-especifico") {
        	if ($_POST['mes']=="") {
            	$pdf->Cell(0, 10, utf8_decode("Datos Generales"), 0, 1, "C");
        	}else {
        		$mesNumero = $_POST['mes'];
				$nombresMeses = [
				    1 => 'Enero',
				    2 => 'Febrero',
				    3 => 'Marzo',
				    4 => 'Abril',
				    5 => 'Mayo',
				    6 => 'Junio',
				    7 => 'Julio',
				    8 => 'Agosto',
				    9 => 'Septiembre',
				    10 => 'Octubre',
				    11 => 'Noviembre',
				    12 => 'Diciembre'
				];
				$nombreMes = $nombresMeses[$mesNumero];

	            $pdf->Cell(0, 10, utf8_decode($nombreMes), 0, 1, "C");
        	}
        } elseif ($_POST['opcionFecha'] == "anio-especifico") {
        	if ($_POST['anio']=="") {
            	$pdf->Cell(0, 10, utf8_decode("Datos Generales"), 0, 1, "C");
        	}else {
        		$pdf->Cell(0, 10, utf8_decode($_POST['anio'] ), 0, 1, "C");
        	}
        } else {
        	if ($_POST['fechaInicio']=="" OR $_POST['fechaFin']=="") {
            	$pdf->Cell(0, 10, utf8_decode("Datos Generales"), 0, 1, "C");
        	}else {
        		$pdf->Cell(0, 10, utf8_decode($_POST['fechaInicio'] . " - " . $_POST['fechaFin']), 0, 1, "C");
        	}
        }
        $pdf->Ln(10);

	    // Crear encabezados de la tabla
	    $pdf->SetFont('FreeSerif', 'B', 12);
	    $pdf->SetFillColor(34, 40, 49);
	    $pdf->SetTextColor(256, 256, 256);
	    $pdf->Cell(35, 10, utf8_decode('Tipo de Vehículo'), 1, 0, 'C', 1);
	    $pdf->Cell(35, 10, utf8_decode('Marca'), 1, 0, 'C', 1);
	    $pdf->Cell(35, 10, utf8_decode('Modelo'), 1, 0, 'C', 1);
	    $pdf->Cell(35, 10, utf8_decode('Total Servicios'), 1, 0, 'C', 1);
	    $pdf->Cell(50, 10, utf8_decode('Servicio Más Solicitado'), 1, 1, 'C', 1); // Nueva columna

	    // Añadir los datos de la consulta
	    $pdf->SetFont('FreeSerif', '', 12);
	    $pdf->SetTextColor(1, 1, 1);
	    if ($result->num_rows == 0) {
	        $pdf->Cell(0, 10, utf8_decode("No se encontró ningún dato coincidente"), 1, 1, 'C');
	    } else {
	        while ($row = $result->fetch_assoc()) {
	            $pdf->Cell(35, 10, utf8_decode($row['tipo']), 1, 0, 'C');
	            $pdf->Cell(35, 10, utf8_decode($row['marca']), 1, 0, 'C');
	            $pdf->Cell(35, 10, utf8_decode($row['modelo']), 1, 0, 'C');
	            $pdf->Cell(35, 10, $row['total_servicios'], 1, 0, 'C');
	            $pdf->Cell(50, 10, utf8_decode($row['servicio_mas_solicitado']), 1, 1, 'C'); // Añadir servicio más solicitado
	        }
	    }
	    $pdf->Output('I', 'Reporte 4: DemandaVehiculos.pdf');
	}

	if ($_POST['tipo-reporte'] == "reporte5") {

	    $sql_empresa = "SELECT * FROM empresa LIMIT 1";
	    $result_empresa = conectarse()->query($sql_empresa);
	    $empresa = $result_empresa->fetch_assoc();

	    // Llamar a la consulta5 para obtener los resultados
	    $result = consulta5PDF(); // Asegúrate de que esta función retorne el resultado como en la consulta original

	    // PDF
	    $pdf = new PDF('P', 'mm', 'A4');
	    $pdf->nombre = $empresa['nombre'];
	    $pdf->slogan = ($empresa['slogan'] !== "Sin Especificar") ? ": " . $empresa['slogan'] : "";
	    $pdf->direccion_empresa = $empresa['direccion'];
	    $pdf->correo = ($empresa['correo'] !== "Sin Especificar") ? $empresa['correo'] : "- -";
	    $pdf->AddPage();

	    $pdf->SetMargins(10, 10, 10);
	    $pdf->SetDrawColor(180, 182, 186);
	    $pdf->SetFillColor(180, 182, 186);
	    $pdf->Rect(0, 4, 1000, 30, "F");

	    if (!empty($empresa['logo'])) {
	        $pdf->Image($empresa['logo'], 6, 6, 25);
	    }

	    $pdf->AddFont('FreeSerif', '', 'FreeSerif.php');
	    $pdf->AddFont('FreeSerif', 'B', 'FreeSerifBold.php');
	    $pdf->AddFont('FreeSerif', 'I', 'FreeSerifItalic.php');
	    $pdf->AddFont('FreeSerif', 'BI', 'FreeSerifBoldItalic.php');

	    // Información del taller
	    $pdf->SetY(7);
	    $pdf->Cell(30);
	    $pdf->SetFont('FreeSerif', 'B', 12);
	    $pdf->Cell(30, 5, utf8_decode($empresa['nombre'] . " " . $pdf->slogan), 0, 1, 'L');
	    $pdf->Cell(30);
	    $pdf->SetFont('FreeSerif', '', 10);
	    $pdf->Cell(30, 5, 'Telefono: ' . $empresa['fijo'], 0, 0, 'L');
	    $pdf->Cell(5);
	    $pdf->Cell(30, 5, utf8_decode('Celular: ' . $empresa['celular']), 0, 1, 'L');
	    $pdf->Cell(30);

	    if ($empresa['correo'] !== "Sin Información") {
	        $pdf->Cell(0, 5, utf8_decode('Correo: ' . $empresa['correo']), 0, 1, 'L');
	        $pdf->Cell(30);
	    }

	    $pdf->MultiCell(150, 5, utf8_decode('Dirección: ' . $empresa['direccion']), 0, 'L');
	    $pdf->Ln(20); // Espacio adicional

	    $pdf->SetFont('FreeSerif', 'B', 18);
	    $pdf->Cell(0, 10, utf8_decode('Reporte 5'), 0, 1, "C");
	    $pdf->SetFont('FreeSerif', 'I', 18);
	    $pdf->Cell(0, 10, utf8_decode('Reporte de Frecuencia de Clientes'), 0, 1, "C");

	     // Encabezado de fecha seleccionado
       	if ($_POST['opcionFecha'] == "general") {
            $pdf->Cell(0, 10, utf8_decode("Datos Generales"), 0, 1, "C");
        } elseif ($_POST['opcionFecha'] == "anio-actual") {
            $pdf->Cell(0, 10, date('Y'), 0, 1, "C");
        } elseif ($_POST['opcionFecha'] == "mes-actual") {
            $pdf->Cell(0, 10, date('M'), 0, 1, "C");
        } elseif ($_POST['opcionFecha'] == "mes-especifico") {
        	if ($_POST['mes']=="") {
            	$pdf->Cell(0, 10, utf8_decode("Datos Generales"), 0, 1, "C");
        	}else {
        		$mesNumero = $_POST['mes'];
				$nombresMeses = [
				    1 => 'Enero',
				    2 => 'Febrero',
				    3 => 'Marzo',
				    4 => 'Abril',
				    5 => 'Mayo',
				    6 => 'Junio',
				    7 => 'Julio',
				    8 => 'Agosto',
				    9 => 'Septiembre',
				    10 => 'Octubre',
				    11 => 'Noviembre',
				    12 => 'Diciembre'
				];
				$nombreMes = $nombresMeses[$mesNumero];

	            $pdf->Cell(0, 10, utf8_decode($nombreMes), 0, 1, "C");
        	}
        } elseif ($_POST['opcionFecha'] == "anio-especifico") {
        	if ($_POST['anio']=="") {
            	$pdf->Cell(0, 10, utf8_decode("Datos Generales"), 0, 1, "C");
        	}else {
        		$pdf->Cell(0, 10, utf8_decode($_POST['anio'] ), 0, 1, "C");
        	}
        } else {
        	if ($_POST['fechaInicio']=="" OR $_POST['fechaFin']=="") {
            	$pdf->Cell(0, 10, utf8_decode("Datos Generales"), 0, 1, "C");
        	}else {
        		$pdf->Cell(0, 10, utf8_decode($_POST['fechaInicio'] . " - " . $_POST['fechaFin']), 0, 1, "C");
        	}
        }
        $pdf->Ln(10);

	    // Crear encabezados de la tabla
	    $pdf->SetFont('FreeSerif', 'B', 12);
	    $pdf->SetFillColor(34, 40, 49);
	    $pdf->SetTextColor(256, 256, 256);
	    $pdf->Cell(85, 10, utf8_decode('Cliente'), 1, 0, 'C', 1);
	    $pdf->Cell(40, 10, utf8_decode('Total Cotizaciones'), 1, 0, 'C', 1);
	    $pdf->Cell(65, 10, utf8_decode('Vehículo más recurrente'), 1, 1, 'C', 1); // Nueva columna

	    // Añadir los datos de la consulta
	    $pdf->SetFont('FreeSerif', '', 12);
	    $pdf->SetTextColor(1, 1, 1);
	    if ($result->num_rows == 0) {
	        $pdf->Cell(0, 10, utf8_decode("No se encontró ningún dato coincidente"), 1, 1, 'C');
	    } else {
	        while ($row = $result->fetch_assoc()) {
	            $pdf->Cell(85, 10, utf8_decode($row['nombres'] . ' ' . $row['apellidos']), 1, 0, 'C');
	            $pdf->Cell(40, 10, $row['total_cotizaciones'], 1, 0, 'C');
	            $pdf->Cell(65, 10, utf8_decode($row['marca'] . " " . $row['modelo'] . " " . $row['año'] . " " . $row['color']), 1, 1, 'C'); // Vehículo más recurrente
	        }
	    }
	    $pdf->Output('I', 'Reporte 5: ClientesVehiculos.pdf');
	}



}
?>
