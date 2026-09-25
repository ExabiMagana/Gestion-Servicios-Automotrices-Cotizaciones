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
$sql = "SELECT s.nombre_servicio, sp.id_servicio, SUM(s.precio) AS Ingresos_Generados
FROM servicios s
JOIN servicios_cotizaciones sp ON s.id_servicio = sp.id_servicio
JOIN cotizaciones c ON sp.id_cotizacion = c.id_cotizacion
GROUP BY s.id_servicio, s.nombre_servicio
ORDER BY Ingresos_Generados DESC;";

$result = $conexion->query($sql);

// Crear la tabla HTML
echo "<table>";
echo "<tr><th>Nombre del servicio</th><th>ID del servicio</th><th>Ingresos totales</th></tr>";

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . (isset($row['nombre_servicio']) ? $row['nombre_servicio'] : 'No disponible') . "</td>";
        echo "<td>" . (isset($row['id_servicio']) ? $row['id_servicio'] : 'No disponible') . "</td>";
        echo "<td>" . (isset($row['Ingresos_Generados']) ? $row['Ingresos_Generados'] : '0') . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='3'>No se encontraron resultados</td></tr>";
}

echo "</table>";

$conexion->close();
?>