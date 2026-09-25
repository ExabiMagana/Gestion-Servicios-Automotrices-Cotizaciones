<?php 
function conectarse(){
	$host="localhost";
	$user="root";
	$pass="";
	$db="cotizacionesm9";

	$conexion = new mysqli($host, $user, $pass, $db) or die("error en la conexion"); 

	return $conexion;
}
$conexion = conectarse();

// Ejecutar la consulta
$sql = "SELECT YEAR(fecha_pedido) AS Año, MONTH(fecha_pedido) AS Mes, SUM(total) AS Ingresos_Totales
        FROM cotizaciones
        WHERE estado = 'parcialmente'
        GROUP BY YEAR(fecha_pedido), MONTH(fecha_pedido)";
$result = $conexion->query($sql);

// Crear la tabla HTML
echo "<table>";
echo "<tr><th>Año</th><th>Mes</th><th>Ingresos Totales</th></tr>";
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["Año"] . "</td>";
        echo "<td>" . $row["Mes"] . "</td>";
        echo "<td>" . $row["Ingresos_Totales"] . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='3'>No se encontraron resultados</td></tr>";
}
echo "</table>";

$conexion->close();

?>