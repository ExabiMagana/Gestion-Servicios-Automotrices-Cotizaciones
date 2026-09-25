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

$sql = "SELECT r.nombre_repuesto, r.proveedor, COUNT(*) AS Cantidad_Usada
        FROM repuestos_cotizaciones rc
        JOIN repuestos r ON rc.id_repuesto = r.id_repuesto
        GROUP BY r.id_repuesto
        ORDER BY Cantidad_Usada DESC";

$result = mysqli_query($conexion, $sql);

echo "<table>";
echo "<tr><th>Nombre del Repuesto</th><th>Proveedor</th><th>Cantidad Usada</th></tr>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $row['nombre_repuesto'] . "</td>";
    echo "<td>" . $row['proveedor'] . "</td>";
    echo "<td>" . $row['Cantidad_Usada'] . "</td>";

    echo "<td><a href='editar_repuesto.php?id=" . $row['nombre_repuesto'] . "'>Editar</a></td>";
    echo "</tr>";
}
echo "</table>";

// Cerrar la conexión
$conexion->close();
?>
