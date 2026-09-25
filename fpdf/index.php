<?php
require('fpdf.php');

// Conexión a la base de datos
$servername = "localhost"; // Cambia esto si es necesario
$username = "root"; // Cambia esto si es necesario
$password = ""; // Cambia esto si es necesario
$dbname = "cotizacionesm9"; // Cambia esto por tu base de datos

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

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

if (isset($_GET['generar'])) {
    $id_cotizacion = $_GET['id'];

    // Consulta los datos de la cotización
    $sql = "SELECT * FROM cotizaciones ct, mecánicos mc, vehiculos vh, clientes cl WHERE ct.id_mecánico=mc.id_mecánico AND vh.id_vehiculo=ct.id_vehiculo AND vh.id_cliente=cl.id_cliente AND ct.id_cotizacion=$id_cotizacion";

    $result = $conn->query($sql);
    $datos = $result->fetch_assoc();

    $sql_empresa = "SELECT * FROM empresa LIMIT 1"; // 
    $result_empresa = $conn->query($sql_empresa);
    $empresa = $result_empresa->fetch_assoc();

    $sql_ctv ="SELECT * FROM cotizacionesvista WHERE id_cotizacion=$id_cotizacion";
    $result_ctv = $conn->query($sql_ctv);

    $sql_csv ="SELECT * FROM cotizacionesserviciosvista WHERE id_cotizacion=$id_cotizacion";
    $result_csv = $conn->query($sql_csv);


    //PDF
    $pdf = new PDF();
    $pdf->nombre = $empresa['nombre'];
    $pdf->slogan = $slogan = ($empresa['slogan']!=="Sin Especificar") ? ": ". $empresa['slogan'] : "" ;
    $pdf->direccion_empresa = $empresa['direccion'];
    $pdf->correo = $correo = ($empresa['correo']!=="Sin Especificar") ? $empresa['correo'] : "- -" ;


    $pdf->SetTitle(utf8_decode('Cotización ' . $id_cotizacion));
    $pdf->AddPage();

    $pdf->SetMargins(10, 10, 10); 

    $pdf->SetDrawColor(180,182,186);
    $pdf->SetFillColor(180,182,186);
    $pdf->Rect(0,4,1000,30,"F");

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
    $pdf->Cell(30, 5, utf8_decode($empresa['nombre'] . " " . $slogan), 0, 1, 'L');
    $pdf->Cell(30);
    $pdf->SetFont('FreeSerif', '', 10);
    $pdf->Cell(30, 5, 'Telefono: ' . $empresa['fijo'], 0, 0, 'L');
    $pdf->Cell(5);
    $pdf->Cell(30, 5, utf8_decode('Celular: ' . $empresa['celular']), 0, 1, 'L');
    $pdf->Cell(30);

    if ($empresa['correo']!=="Sin Información") {
        $pdf->Cell(0, 5, utf8_decode('Correo: ' . $empresa['correo']), 0, 1, 'L');
        $pdf->Cell(30);
    }

    $pdf->MultiCell(150, 5, utf8_decode('Dirección: ' . $empresa['direccion']), 0, 'L');
    $pdf->Ln(20); // Espacio adicional

    // Información de la cotización
    $pdf->SetFont('FreeSerif', 'B', 14);
    $pdf->Cell(0, 10, utf8_decode('Cotización #' . $datos['id_cotizacion']), 0, 1,'L');
    
    $pdf->SetFont('FreeSerif', 'B', 10);
    $pdf->Cell(70, 5, utf8_decode('Fecha de Emisión: '), 0, 0,'L');
    $pdf->Cell(70, 5, utf8_decode('Fecha de Expiración: '), 0, 0,'L');
    $pdf->Cell(70, 5, utf8_decode('Mécanico Encargado: '), 0, 1,'L');
    
    $pdf->SetFont('FreeSerif', '', 10);
    $pdf->Cell(70, 5, $datos['fecha_pedido'], 0, 0,'L');
    $pdf->Cell(70, 5, $datos['fecha_expiración'], 0, 0,'L');
    $pdf->Cell(70, 5, $datos['nombre'], 0, 1,'L');
    $pdf->Ln(5); 

    // Información del Vehiculo
    $pdf->SetFont('FreeSerif', 'B', 12);
    $pdf->Cell(50, 10, utf8_decode('Datos del Vehiculo '), 0, 0,'L');

    if ($datos['unidad_medida']=='KM') {

        $pdf->Cell(10, 10, utf8_decode('KM:'), 0, 0,'C');
        $pdf->SetFont('FreeSerif', 'B', 18);
        $pdf->Cell(8, 8, utf8_decode('×'), 1, 0,'C');
        $pdf->Cell(10);
        $pdf->SetFont('FreeSerif', 'B', 12);
        $pdf->Cell(10, 10, utf8_decode('MI:'), 0, 0,'C');
        $pdf->Cell(8, 8, '' , 1, 0,'C');
        $pdf->Cell(20);

    } elseif ($datos['unidad_medida']=='MI') {

        $pdf->Cell(10, 10, utf8_decode('KM:'), 0, 0,'C');
        $pdf->Cell(8, 8, '', 1, 0,'C');
        $pdf->Cell(10);
        $pdf->Cell(10, 10, utf8_decode('MI:'), 0, 0,'C');
        $pdf->SetFont('FreeSerif', 'B', 18);
        $pdf->Cell(8, 8, utf8_decode('×'), 1, 0,'C');
        $pdf->SetFont('FreeSerif', 'B', 12);
        $pdf->Cell(20);

    }

    $pdf->Cell(10, 10, utf8_decode('Odómetro: '), 0, 0,'C');
    $pdf->Cell(5);
    $pdf->SetFont('FreeSerif', '', 11);
    $pdf->Cell(10, 10, $datos['odometro'], 0, 1,'C');
    $pdf->Ln(2);

    $pdf->SetFillColor(242,242,242);
    $pdf->SetFont('FreeSerif', 'B', 10);
    $pdf->Cell(16, 10, utf8_decode('MARCA: '), 'T', 0, 'L', true);
    $pdf->SetFont('FreeSerif', '', 10);
    $pdf->Cell(31, 10, utf8_decode($datos['marca']), 'TR', 0, 'L', true);

    $pdf->SetFont('FreeSerif', 'B', 10);
    $pdf->Cell(18, 10, utf8_decode('MODELO: '), 'T', 0, 'L', true);
    $pdf->SetFont('FreeSerif', '', 10);
    $pdf->Cell(29, 10, utf8_decode($datos['modelo']), 'TR', 0, 'L', true);

    $pdf->SetFont('FreeSerif', 'B', 10);
    $pdf->Cell(10, 10, utf8_decode('AÑO: '), 'TL', 0, 'L', true);
    $pdf->SetFont('FreeSerif', '', 10);
    $pdf->Cell(37, 10, utf8_decode($datos['año']), 'T', 0, 'L', true);

    $pdf->SetFont('FreeSerif', 'B', 10);
    $pdf->Cell(12, 10, utf8_decode('TIPO: '), 'TL', 0, 'L', true);
    $pdf->SetFont('FreeSerif', '', 10);
    $pdf->Cell(35, 10, utf8_decode($datos['tipo']), 'T', 1, 'L', true);

    //
    $pdf->SetFont('FreeSerif', 'B', 10);
    $pdf->Cell(16, 10, utf8_decode('PLACAS: '), 'B', 0, 'L', true);
    $pdf->SetFont('FreeSerif', '', 10);
    $pdf->Cell(31, 10, utf8_decode($datos['placas']), 'BR', 0, 'L', true);

    $pdf->SetFont('FreeSerif', 'B', 10);
    $pdf->Cell(16, 10, utf8_decode('CHASIS: '), 'B', 0, 'L', true);
    $pdf->SetFont('FreeSerif', '', 10);
    $pdf->Cell(31, 10, utf8_decode($datos['chasis']), 'BR', 0, 'L', true);

    $pdf->SetFont('FreeSerif', 'B', 10);
    $pdf->Cell(16, 10, utf8_decode('MOTOR: '), 'BL', 0, 'L', true);
    $pdf->SetFont('FreeSerif', '', 10);
    $pdf->Cell(31, 10, utf8_decode($datos['motor']), 'B', 0, 'L', true);

    $pdf->SetFont('FreeSerif', 'B', 10);
    $pdf->Cell(9, 10, utf8_decode('VIN: '), 'LB', 0, 'L', true);
    $pdf->SetFont('FreeSerif', '', 10);
    $pdf->Cell(38, 10, utf8_decode($datos['vin']), 'B', 0, 'L', true);
    $pdf->Ln(22);

    /*$texto=$pdf->GetPageWidth();
    $pdf->Cell(0,10,$texto);*/


    // Detalles de la Cotización
    $pdf->SetFont('FreeSerif', 'B', 12);
    $pdf->Cell(0, 10, utf8_decode('Cotización #' . $datos['id_cotizacion']), 0, 1, 'L');
    $pdf->SetFont('FreeSerif', 'B', 12);
    $pdf->Cell(90, 10, utf8_decode('Descripción'), 'T');
    $pdf->Cell(38, 10, 'Cantidad', 'T');
    $pdf->Cell(40, 10, 'Precio Unitario', 'T');
    $pdf->Cell(20, 10, 'Importe', 'T', 1);

    if (mysqli_num_rows($result_csv) > 0) {  
        // Servicios
        $pdf->SetFillColor(220,220,221);
        $pdf->SetTextColor(121,86,112);
        $pdf->SetFont('FreeSerif', 'B', 12);
        $pdf->Cell(188, 10, 'Mano de Obra', 0, 1, 'L', true);
        $pdf->SetTextColor(0,0,0);

        // Datos Dinámicos Servicios
        $subtotal=0;
        while ($csv = $result_csv->fetch_assoc()) {
            // Inicializar la altura máxima de la celda
            $pdf->SetFillColor(252,252,252);
            $maxCellHeight = 10;

            // Mide el ancho de la descripción
            $descripcion = utf8_decode($csv['nombre_servicio']);
            $pdf->SetFont('FreeSerif', '', 11);
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(90, 10, $descripcion, 0, 'L',true);
            
            // Ajusta la posición X e Y para los siguientes elementos en la misma fila
            $pdf->SetXY($x + 90, $y);
            
            // Cantidad (puede ser dinámica)
            $cantidad = '1 Unidad';
            $pdf->Cell(38, 10, utf8_decode($cantidad), 0, 0, 'L',true);

            // Precio Unitario (formateado a 2 decimales)
            $precio_formateado = number_format($csv['precio_servicio'], 2);
            $pdf->Cell(40, 10, '$' . $precio_formateado, 0, 0, 'L',true);

            $total_formateado = number_format($csv['precio_servicio'], 2);
            $pdf->Cell(20, 10, '$' . $total_formateado, 0, 1, 'L',true);

            $subtotal=$subtotal+$total_formateado;
            $cliente = $csv['nombres'] . " " . $csv['apellidos'];
            $fecha = $csv['fecha_pedido'];
            $vehiculo = $csv['marca'] . "-" . $csv['modelo'] . "-" . $csv['año'];

        }

        $pdf->SetFillColor(240,240,241);
        $subtotal_formateado = number_format($subtotal, 2);
        $pdf->SetFont('FreeSerif', 'B', 12);
        $pdf->Cell(128,10,'',0,0,'L',true);
        $pdf->Cell(40, 10, 'Subtotal', 0, 0, 'R',true);
        $pdf->Cell(20, 10, '$' . $subtotal_formateado, 0, 1, 'L',true);
    }else{
        $subtotal_formateado = 0;
    }

    if (mysqli_num_rows($result_ctv) > 0) {  
        // Repuestos
        $pdf->SetFillColor(220,220,221);
        $pdf->SetTextColor(121,86,112);
        $pdf->SetFont('FreeSerif', 'B', 12);
        $pdf->Cell(188, 10, 'Repuestos Automotrices', 0, 1, 'L', true);
        $pdf->SetTextColor(0,0,0);

        // Datos Dinámicos Repuestos
        $subtotal=0;
        while ($ctv = $result_ctv->fetch_assoc()) {
            // Inicializar la altura máxima de la celda
            $pdf->SetFillColor(252,252,252);
            $maxCellHeight = 10;

            // Mide el ancho de la descripción
            $descripcion = utf8_decode($ctv['nombre_repuesto'] . ' - ' . $ctv['marca_repuesto']);
            $pdf->SetFont('FreeSerif', '', 11);
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(90, 10, $descripcion, 0, 'L',true);
            
            // Ajusta la posición X e Y para los siguientes elementos en la misma fila
            $pdf->SetXY($x + 90, $y);
            
            // Cantidad
            $unidad = ($ctv['cantidad_repuesto']>1) ? "Unidades" : "Unidad" ;
            $pdf->Cell(38, 10, utf8_decode($ctv['cantidad_repuesto'] . ' ' . $unidad), 0, 0, 'L',true);

            // Precio Unitario (formateado a 2 decimales)
            $precio_formateado = number_format($ctv['precio_unitario'], 2);
            $pdf->Cell(40, 10, '$' . $precio_formateado, 0, 0, 'L',true);

            $total_formateado = number_format($ctv['precio_unitario']*$ctv['cantidad_repuesto'], 2);
            $pdf->Cell(20, 10, '$' . $total_formateado, 0, 1, 'L',true);

            $subtotal=$subtotal+$total_formateado;
            $cliente = $ctv['nombres'] . " " . $ctv['apellidos'];
            $fecha = $ctv['fecha_pedido'];
            $vehiculo = $ctv['marca'] . "-" . $ctv['modelo'] . "-" . $ctv['año'];
        }   


        $pdf->SetFillColor(240,240,241);
        $subtotal_formateado2 = number_format($subtotal, 2);
        $pdf->SetFont('FreeSerif', 'B', 12);
        $pdf->Cell(128,10,'',0,0,'L',true);
        $pdf->Cell(40, 10, 'Subtotal', 0, 0, 'R',true);
        $pdf->Cell(20, 10, '$' . $subtotal_formateado2, 0, 1, 'L',true); 
    }else{
        $subtotal_formateado2 = 0;
    }


    $total=$subtotal_formateado+$subtotal_formateado2;
    $total_formateado = number_format($total, 2);
    $pdf->SetFont('FreeSerif', 'B', 12);
    $pdf->Cell(128,10,'',0,1,'L');
    $pdf->Cell(128,10,'',0,0,'L');
    $pdf->Cell(40, 10, 'Total', 'TB', 0, 'L');
    $pdf->Cell(20, 10, '$' . $total_formateado,'TB', 1, 'L');

    // Cerrar conexión
    $conn->close();

    // Generar el PDF
    if ($_GET['generar']=="imprimir") {


        $pdf->Output('I', str_replace(" ","",'Cotizacion-' . $fecha . '-' . $cliente . '-' . $vehiculo .'.pdf'));
    }elseif ($_GET['generar']=="enviar") {
        $ruta=str_replace(" ", "", 'PDF/cotizaciones/Cotizacion-' . $fecha . '-' . $cliente . '-' . $vehiculo .'.pdf');
        $pdf->Output('F', $ruta);
        $nombre = $empresa['nombre'];
        $correo = $datos['correo'];
        $vehiculo = str_replace("-"," ",$vehiculo);
        header("location:../complementos/sendmail.php?ruta=$ruta&&empresa=$nombre&&vehiculo='$vehiculo'&&cliente='$cliente'&&correo='$correo'");
    }

    exit;
}
?>