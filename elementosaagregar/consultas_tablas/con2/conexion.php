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

// Consulta SQL
$sql = "SELECT m.nombre, COUNT(*) AS Cotizaciones_Completadas FROM mecánicos m JOIN cotizaciones c ON m.id_mecánico = c.id_mecánico WHERE c.estado = 'completado' GROUP BY m.id_mecánico;";
$result = $conexion->query($sql);

// Mostrar los datos en una tabla
echo "<table>";
echo "<tr><th>Nombre del Mecánico</th><th>Cotizaciones Completadas</th></tr>";
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["nombre"] . "</td>";
        echo "<td>" . $row["Cotizaciones_Completadas"] . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='3'>No se encontraron resultados</td></tr>";
}
echo "</table>";

// Cerrar la conexión
$conexion->close();

?>

