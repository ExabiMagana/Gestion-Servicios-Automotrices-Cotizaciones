<?php
include "../database/conexion.php";
$conn = conectarse();

date_default_timezone_set('America/El_Salvador');

$year = isset($_POST['year']) && $_SERVER["REQUEST_METHOD"] == "POST" ? $_POST['year'] : date('Y');

// Inicializamos los arrays con 0 para los 12 meses
$totals = [
    'en proceso' => array_fill(0, 12, 0),
    'pendiente' => array_fill(0, 12, 0),
    'completado' => array_fill(0, 12, 0),
    'cancelado' => array_fill(0, 12, 0),
    'parcialmente' => array_fill(0, 12, 0)
];

// Consulta para obtener las cotizaciones por mes y estado
$sql = "SELECT MONTH(fecha_pedido) AS mes, estado, SUM(total) AS total 
        FROM cotizaciones 
        WHERE YEAR(fecha_pedido) = '$year' AND estado IN ('en proceso', 'pendiente', 'cancelado', 'completado', 'parcialmente') 
        GROUP BY mes, estado";

$query = $conn->query($sql);

while ($row = $query->fetch_assoc()) {
    $month = $row['mes'] - 1; // Convertimos el mes a índice del array
    $estado = $row['estado'];
    $totals[$estado][$month] = $row['total'] ?: 0; // Actualizamos el valor correspondiente
}

// Ahora usamos los arrays para pasar los valores a JavaScript
$totalesEnProceso = implode(',', $totals['en proceso']);
$totalesPendientes = implode(',', $totals['pendiente']);
$totalesCompletados = implode(',', $totals['completado']);
$totalesCancelados = implode(',', $totals['cancelado']);
$totalesParciales = implode(',', $totals['parcialmente']);
?>
