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
$sql = "SELECT v.tipo, COUNT(*) AS Cantidad_Servicios,
       (SELECT sc.id_servicio
            FROM servicios_cotizaciones sc
            WHERE sc.id_cotizacion IN (
                SELECT c.id_cotizacion
                FROM cotizaciones c
                WHERE c.id_vehiculo = v.id_vehiculo
            )
            GROUP BY sc.id_servicio
            ORDER BY COUNT(*) DESC
            LIMIT 1
        ) AS Servicio_Mas_Comun
        FROM vehiculos v, cotizaciones c
        WHERE v.id_vehiculo = c.id_vehiculo
        GROUP BY v.tipo;";

$result = $conexion->query($sql);

// Crear la tabla HTML
echo "<table>";
echo "<tr><th>Nombre del servicio</th><th>ID del servicio</th><th>Ingresos totales</th><th>Cantidad de Servicios</th><th>Servicio Más Común</th></tr>";

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . (isset($row['nombre_servicio']) ? $row['nombre_servicio'] : 'No disponible') . "</td>";
        echo "<td>" . (isset($row['id_servicio']) ? $row['id_servicio'] : 'No disponible') . "</td>";
        echo "<td>" . (isset($row['Ingresos_Generados']) ? $row['Ingresos_Generados'] : '0') . "</td>";
        echo "<td>" . (isset($row['Cantidad_Servicios']) ? $row['Cantidad_Servicios'] : '0') . "</td>";
        echo "<td>" . (isset($row['Servicio_Mas_Comun']) ? $row['Servicio_Mas_Comun'] : 'No disponible') . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No se encontraron resultados</td></tr>";
}

echo "</table>";


$conexion->close();
?>