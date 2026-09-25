<?php
include "../database/conexion.php";
$conn = conectarse();

date_default_timezone_set('America/El_Salvador');

$year = isset($_POST['year']) && $_SERVER["REQUEST_METHOD"] == "POST" ? $_POST['year'] : date('Y');

// Inicializamos el array de totales por estado
$totals = [
    'en proceso' => 0,
    'pendiente' => 0,
    'completado' => 0,
    'cancelado' => 0,
    'parcialmente' => 0
];

// Consulta para contar las cotizaciones por estado para el año especificado
$sql = "SELECT estado, COUNT(*) AS cantidad 
        FROM cotizaciones 
        WHERE YEAR(fecha_pedido) = '$year' 
        AND estado IN ('en proceso', 'pendiente', 'cancelado', 'completado', 'parcialmente') 
        GROUP BY estado";

$query = $conn->query($sql);

while ($row = $query->fetch_assoc()) {
    $estado = $row['estado'];
    $totals[$estado] = $row['cantidad']; // Almacenamos la cantidad en el array
}

// Convertimos los totales a una lista para pasarlos a JavaScript
$totalesEnProceso = $totals['en proceso'];
$totalesPendientes = $totals['pendiente'];
$totalesCompletados = $totals['completado'];
$totalesCancelados = $totals['cancelado'];
$totalesParciales = $totals['parcialmente'];
?>
