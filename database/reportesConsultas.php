<?php 
include_once "../database/conexion.php";
include_once "../PHP/procesarReportes.php";
function consulta1($clase) {

    $llave=conectarse();

    // Construir la consulta base
    $sql = "SELECT estado, YEAR(fecha_pedido) AS Año, SUM(total) AS Ingresos_Anuales, ROUND(SUM(total) / 12, 2) AS Promedio_Mensual, COUNT(*) AS Pedidos_Anuales FROM cotizaciones GROUP BY estado, YEAR(fecha_pedido) ORDER BY Año, estado;
";

    // Ejecutar la consulta
    $result = $llave->query($sql);

    // Crear la tabla HTML
    echo "<table border='1' class='styled-table'>
    <thead class='"; $class = (isset($clase)) ? $clase : 'premium' ; echo $class . "'>";
    echo "
        <tr>
            <th>Año</th>
            <th>Promedio Mensual</th>
            <th>Ingresos Anuales</th>
            <th>Cantidad</th>
            <th>Estado de Cotización</th>
        </tr>
    </thead>";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["Año"] . "</td>";
            echo "<td>" . $row["Promedio_Mensual"] . "</td>";
            echo "<td>" . $row["Ingresos_Anuales"] . "</td>";
            echo "<td>" . $row["Pedidos_Anuales"] . "</td>";
            echo "<td>" . $row["estado"] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No se encontraron resultados</td></tr>";
    }
    echo "</table>";

}

function consulta2($clase) {
    $llave = conectarse();

    // Consulta SQL mejorada
    $sql = "SELECT m.nombre, 
                   COUNT(CASE WHEN c.estado = 'completado' THEN 1 END) AS Cotizaciones_Completadas, 
                   COUNT(CASE WHEN c.estado = 'en proceso   ' THEN 1 END) AS Cotizaciones_Pendientes,
                   COUNT(DISTINCT c.id_cotizacion) AS Total_Cotizaciones,
                   SUM(CASE WHEN c.estado = 'completado' THEN c.total ELSE 0 END) AS Ingresos_Generados,
                   AVG(CASE WHEN c.estado = 'completado' THEN c.total END) AS Promedio_Ingreso 
            FROM mecánicos m 
            LEFT JOIN cotizaciones c ON m.id_mecánico = c.id_mecánico
            GROUP BY m.id_mecánico;";

    $result = $llave->query($sql);

    // Crear la tabla HTML
    echo "<table border='1' class='styled-table'>";
    echo " <thead class='"; $class = (isset($clase)) ? $clase : 'premium' ; echo $class . "'>";
    echo  "<tr>
            <th>Nombre del Mecánico</th>
            <th>Completadas</th>
            <th>En proceso</th>
            <th>Total Creadas </th>
            <th>Ingresos Generados</th>
            <th>Promedio de Ingreso</th>
          </tr>
        </thead>";
         echo " <tbody>";
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["nombre"] . "</td>";
            echo "<td>" . $valor = ($row["Cotizaciones_Completadas"]==0) ? 'Ninguna' : $row["Cotizaciones_Completadas"] . "</td>";
            echo "<td>" . $valor = ($row["Cotizaciones_Pendientes"]==0) ? 'Ninguna' : $row["Cotizaciones_Pendientes"] . "</td>";
            echo "<td>" . $valor = ($row["Total_Cotizaciones"]==0) ? 'Ninguna' : $row["Total_Cotizaciones"] . "</td>";
            echo "<td> $
            " . number_format($row["Ingresos_Generados"], 2) . "</td>"; // Formatear el ingreso
            echo "<td> $" . number_format($row["Promedio_Ingreso"], 2) . "</td>"; // Formatear el ingreso promedio
            echo "</tr>";
        }
    } else {
        echo "<tbody><tr><td colspan='6'>No se encontraron resultados</td></tr>";
    }
    echo "</table>";
}

function consulta3($clase) {
    $llave = conectarse();

    // Consulta SQL combinada para ingresos por tipo de servicio
    $sql = "SELECT s.nombre_servicio, 
                   COUNT(DISTINCT sc.id_cotizacion) AS total_cotizaciones, 
                   SUM(s.precio) AS ingresos_generados
            FROM servicios s
            INNER JOIN servicios_cotizaciones sc ON s.id_servicio = sc.id_servicio
            INNER JOIN cotizaciones c ON sc.id_cotizacion = c.id_cotizacion
            WHERE c.estado='completado' 
            GROUP BY s.nombre_servicio
            ORDER BY ingresos_generados DESC;"; // Agrupar por servicio

    $result = $llave->query($sql);

    // Crear la tabla HTML
    echo "<table border='1' class='styled-table'>";
    echo "<thead class='" . (isset($clase) ? $clase : 'premium') . "'>";
    echo "<tr>
            <th>Nombre del Servicio</th>
            <th>Cantidad de Apariciones</th>
            <th>Ingresos Generados</th>
          </tr>
        </thead>";
    echo "<tbody>";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["nombre_servicio"] . "</td>";
            echo "<td>" . $row["total_cotizaciones"] . "</td>"; // Mostrar la cantidad de cotizaciones
            echo "<td>$" . number_format($row["ingresos_generados"], 2) . "</td>"; // Ingresos generados
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No se encontraron resultados</td></tr>";
    }
    echo "</tbody></table>";
}

function consulta4($clase) {
    $llave = conectarse();

    // Consulta SQL combinada para la demanda de servicios por tipo de vehículo, marca y modelo
    $sql = "SELECT v.tipo, 
                   v.marca, 
                   v.modelo, 
                   COUNT(*) AS total_servicios,
                   (SELECT s.nombre_servicio
                    FROM servicios s
                    INNER JOIN servicios_cotizaciones sc ON s.id_servicio = sc.id_servicio
                    INNER JOIN cotizaciones c2 ON sc.id_cotizacion = c2.id_cotizacion
                    WHERE c2.id_vehiculo = v.id_vehiculo
                    GROUP BY s.nombre_servicio
                    ORDER BY COUNT(*) DESC
                    LIMIT 1) AS servicio_mas_solicitado
            FROM vehiculos v
            INNER JOIN cotizaciones c ON v.id_vehiculo = c.id_vehiculo
            GROUP BY v.tipo, v.marca, v.modelo
            ORDER BY total_servicios DESC;";

    $result = $llave->query($sql);

    // Crear la tabla HTML
    echo "<table border='1' class='styled-table'>";
    echo "<thead class='" . (isset($clase) ? $clase : 'premium') . "'>";
    echo "<tr>
            <th>Tipo de Vehículo</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Total de Servicios</th>
            <th>Servicio Más Solicitado</th>
          </tr>
        </thead>";
    echo "<tbody>";
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["tipo"] . "</td>";
            echo "<td>" . $row["marca"] . "</td>";
            echo "<td>" . $row["modelo"] . "</td>";
            echo "<td>" . $row["total_servicios"] . "</td>";
            echo "<td>" . ($row["servicio_mas_solicitado"] ? $row["servicio_mas_solicitado"] : 'N/A') . "</td>"; // Mostrar el servicio más solicitado o N/A si no hay
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No se encontraron resultados</td></tr>";
    }
    echo "</tbody></table>";
}

function consulta5($clase) {
    $llave = conectarse();

    // Consulta SQL combinada para obtener clientes y su vehículo con más cotizaciones
    $sql = "
        WITH vehiculo_cotizaciones AS (
            SELECT 
                v.id_vehiculo,
                v.id_cliente,
                COUNT(co.id_cotizacion) AS total_cotizaciones,
                v.marca,
                v.modelo,
                v.año,
                v.color,
                ROW_NUMBER() OVER (PARTITION BY v.id_cliente ORDER BY COUNT(co.id_cotizacion) DESC) AS rn
            FROM 
                vehiculos v
            INNER JOIN 
                cotizaciones co ON v.id_vehiculo = co.id_vehiculo
            GROUP BY 
                v.id_vehiculo, v.id_cliente, v.marca, v.modelo
        )
        SELECT 
            c.id_cliente, 
            c.nombres, 
            c.apellidos,
            vc.marca, 
            vc.modelo,
            vc.año,
            vc.color,
            vc.total_cotizaciones
        FROM 
            clientes c
        INNER JOIN 
            vehiculo_cotizaciones vc ON c.id_cliente = vc.id_cliente
        WHERE 
            vc.rn = 1 -- Solo el vehículo con más cotizaciones por cliente
        ORDER BY 
            vc.total_cotizaciones DESC;
    ";

    $result = $llave->query($sql);

    // Crear la tabla HTML
    echo "<table border='1' class='styled-table'>";
    echo "<thead class='" . (isset($clase) ? $clase : 'premium') . "'>";
    echo "<tr>
            <th>Cliente</th>
            <th>Total Cotizaciones</th>
            <th>Vehículo más recurrente</th>
          </tr>
        </thead>";
    echo "<tbody>";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["nombres"] . " " . $row['apellidos'] . "</td>";
            echo "<td>" . $row["total_cotizaciones"] . "</td>";
            echo "<td>" . $row["marca"] . " " .$row["modelo"] . " " . $row['año'] . " " . $row['color'] ."</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No se encontraron resultados</td></tr>";
    }
    echo "</tbody></table>";
}


function consulta1PDF() {

    $llave = conectarse();

    // Recuperar los parámetros de la URL
    $anio = isset($_POST['anio']) ? $_POST['anio'] : null;
    $fechaInicio = isset($_POST['fechaInicio']) ? $_POST['fechaInicio'] : null;
    $fechaFin = isset($_POST['fechaFin']) ? $_POST['fechaFin'] : null;
    $opcionFecha = isset($_POST['opcionFecha']) ? $_POST['opcionFecha'] : null;

    // Construir la consulta base para PDF
    $sql = "SELECT YEAR(fecha_pedido) AS Año,
                   estado,
                   SUM(total) AS Ingresos_Anuales,
                   ROUND(SUM(total) / 12, 2) AS Promedio_Mensual,
                   COUNT(*) AS Pedidos_Anuales
            FROM cotizaciones WHERE 1=1";

    // Agregar condiciones basadas en la opción de fecha
    if ($opcionFecha == 'anio-actual') {
        $sql .= " AND YEAR(fecha_pedido) = YEAR(CURRENT_DATE())";
    } elseif ($opcionFecha == 'anio-especifico' && !empty($anio)) {
        $sql .= " AND YEAR(fecha_pedido) = '$anio'";
    } elseif ($opcionFecha == 'rango-fechas' && !empty($fechaInicio) && !empty($fechaFin)) {
        $sql .= " AND fecha_pedido BETWEEN '$fechaInicio' AND '$fechaFin'";
    }

    // Agrupar los resultados por estado y año
    $sql .= " GROUP BY estado, YEAR(fecha_pedido)";
    $sql .= " ORDER BY Año, estado";

    // Ejecutar la consulta
    $result = $llave->query($sql);
    return $result;
}


function consulta2PDF() {
    $llave = conectarse();

    // Recuperar los parámetros de la solicitud POST
    $anio = isset($_POST['anio']) ? $_POST['anio'] : null;
    $fechaInicio = isset($_POST['fechaInicio']) ? $_POST['fechaInicio'] : null;
    $fechaFin = isset($_POST['fechaFin']) ? $_POST['fechaFin'] : null;
    $opcionFecha = isset($_POST['opcionFecha']) ? $_POST['opcionFecha'] : null;
    $mes = isset($_POST['mes']) ? $_POST['mes'] : null; // Mes específico

    // Consulta SQL mejorada
    $sql = "SELECT m.nombre, 
                   COUNT(CASE WHEN c.estado = 'completado' THEN 1 END) AS Cotizaciones_Completadas, 
                   COUNT(CASE WHEN c.estado = 'en proceso' THEN 1 END) AS Cotizaciones_Pendientes,
                   COUNT(DISTINCT c.id_cotizacion) AS Total_Cotizaciones,
                   SUM(CASE WHEN c.estado = 'completado' THEN c.total ELSE 0 END) AS Ingresos_Generados,
                   AVG(CASE WHEN c.estado = 'completado' THEN c.total END) AS Promedio_Ingreso 
            FROM mecánicos m 
            LEFT JOIN cotizaciones c ON m.id_mecánico = c.id_mecánico
            WHERE 1=1"; // Para facilitar la adición de condiciones

    // Agregar condiciones basadas en la opción de fecha
    if ($opcionFecha == 'anio-actual') {
        $sql .= " AND YEAR(c.fecha_pedido) = YEAR(CURRENT_DATE())"; // Suponiendo que hay una columna fecha_pedido en cotizaciones
    } elseif ($opcionFecha == 'anio-especifico' && !empty($anio)) {
        $sql .= " AND YEAR(c.fecha_pedido) = '$anio'";
    } elseif ($opcionFecha == 'rango-fechas' && !empty($fechaInicio) && !empty($fechaFin)) {
        $sql .= " AND c.fecha_pedido BETWEEN '$fechaInicio' AND '$fechaFin'";
    } elseif ($opcionFecha == 'mes-actual') {
        $sql .= " AND MONTH(c.fecha_pedido) = MONTH(CURRENT_DATE()) AND YEAR(c.fecha_pedido) = YEAR(CURRENT_DATE())";
    } elseif ($opcionFecha == 'mes-especifico' && !empty($mes) && !empty($anio)) {
        $sql .= " AND MONTH(c.fecha_pedido) = '$mes' AND YEAR(c.fecha_pedido) = '$anio'";
    }

    // Agrupar los resultados por mecánico
    $sql .= " GROUP BY m.id_mecánico;";

    // Ejecutar la consulta
    $result = $llave->query($sql);
    return $result;
}

function consulta3PDF() {
    $llave = conectarse();

    // Recuperar los parámetros de la solicitud POST
    $anio = isset($_POST['anio']) ? $_POST['anio'] : null;
    $fechaInicio = isset($_POST['fechaInicio']) ? $_POST['fechaInicio'] : null;
    $fechaFin = isset($_POST['fechaFin']) ? $_POST['fechaFin'] : null;
    $opcionFecha = isset($_POST['opcionFecha']) ? $_POST['opcionFecha'] : null;
    $mes = isset($_POST['mes']) ? $_POST['mes'] : null;

    // Consulta SQL ajustada para contar la cantidad de cotizaciones únicas
    $sql = "SELECT s.nombre_servicio, 
                   COUNT(DISTINCT sc.id_cotizacion) AS total_cotizaciones, 
                   SUM(s.precio) AS ingresos_generados
            FROM servicios s
            INNER JOIN servicios_cotizaciones sc ON s.id_servicio = sc.id_servicio
            INNER JOIN cotizaciones c ON sc.id_cotizacion = c.id_cotizacion
            WHERE c.estado='completado'"; // Solo contar cotizaciones completadas

    // Agregar condiciones basadas en la opción de fecha
    if ($opcionFecha == 'anio-actual') {
        $sql .= " AND YEAR(c.fecha_pedido) = YEAR(CURRENT_DATE())";
    } elseif ($opcionFecha == 'anio-especifico' && !empty($anio)) {
        $sql .= " AND YEAR(c.fecha_pedido) = '$anio'";
    } elseif ($opcionFecha == 'rango-fechas' && !empty($fechaInicio) && !empty($fechaFin)) {
        $sql .= " AND c.fecha_pedido BETWEEN '$fechaInicio' AND '$fechaFin'";
    } elseif ($opcionFecha == 'mes-actual') {
        $sql .= " AND MONTH(c.fecha_pedido) = MONTH(CURRENT_DATE()) AND YEAR(c.fecha_pedido) = YEAR(CURRENT_DATE())";
    } elseif ($opcionFecha == 'mes-especifico' && !empty($mes) && !empty($anio)) {
        $sql .= " AND MONTH(c.fecha_pedido) = '$mes' AND YEAR(c.fecha_pedido) = '$anio'";
    }

    // Agrupar los resultados por servicio
    $sql .= " GROUP BY s.nombre_servicio ORDER BY ingresos_generados DESC;";

    // Ejecutar la consulta
    $result = $llave->query($sql);
    return $result;
}

function consulta4PDF() {
    $llave = conectarse();

    // Recuperar los parámetros de la solicitud POST
    $anio = isset($_POST['anio']) ? $_POST['anio'] : null;
    $fechaInicio = isset($_POST['fechaInicio']) ? $_POST['fechaInicio'] : null;
    $fechaFin = isset($_POST['fechaFin']) ? $_POST['fechaFin'] : null;
    $opcionFecha = isset($_POST['opcionFecha']) ? $_POST['opcionFecha'] : null;
    $mes = isset($_POST['mes']) ? $_POST['mes'] : null;

    // Consulta SQL con filtros de fecha
    $sql = "SELECT v.tipo, 
                   v.marca, 
                   v.modelo, 
                   COUNT(*) AS total_servicios,
                   (SELECT s.nombre_servicio
                    FROM servicios s
                    INNER JOIN servicios_cotizaciones sc ON s.id_servicio = sc.id_servicio
                    INNER JOIN cotizaciones c2 ON sc.id_cotizacion = c2.id_cotizacion
                    WHERE c2.id_vehiculo = v.id_vehiculo";

    // Agregar condiciones basadas en la opción de fecha
    if ($opcionFecha == 'anio-actual') {
        $sql .= " AND YEAR(c2.fecha_pedido) = YEAR(CURRENT_DATE())";
    } elseif ($opcionFecha == 'anio-especifico' && !empty($anio)) {
        $sql .= " AND YEAR(c2.fecha_pedido) = '$anio'";
    } elseif ($opcionFecha == 'rango-fechas' && !empty($fechaInicio) && !empty($fechaFin)) {
        $sql .= " AND c2.fecha_pedido BETWEEN '$fechaInicio' AND '$fechaFin'";
    } elseif ($opcionFecha == 'mes-actual') {
        $sql .= " AND MONTH(c2.fecha_pedido) = MONTH(CURRENT_DATE()) AND YEAR(c2.fecha_pedido) = YEAR(CURRENT_DATE())";
    } elseif ($opcionFecha == 'mes-especifico' && !empty($mes) && !empty($anio)) {
        $sql .= " AND MONTH(c2.fecha_pedido) = '$mes' AND YEAR(c2.fecha_pedido) = '$anio'";
    }

    // Completar la subconsulta para el servicio más solicitado
    $sql .= " GROUP BY s.nombre_servicio
              ORDER BY COUNT(*) DESC
              LIMIT 1) AS servicio_mas_solicitado
              FROM vehiculos v
              INNER JOIN cotizaciones c ON v.id_vehiculo = c.id_vehiculo
              WHERE 1=1"; // Para facilitar la adición de condiciones

    // Agregar condiciones a la consulta principal
    if ($opcionFecha == 'anio-actual') {
        $sql .= " AND YEAR(c.fecha_pedido) = YEAR(CURRENT_DATE())";
    } elseif ($opcionFecha == 'anio-especifico' && !empty($anio)) {
        $sql .= " AND YEAR(c.fecha_pedido) = '$anio'";
    } elseif ($opcionFecha == 'rango-fechas' && !empty($fechaInicio) && !empty($fechaFin)) {
        $sql .= " AND c.fecha_pedido BETWEEN '$fechaInicio' AND '$fechaFin'";
    } elseif ($opcionFecha == 'mes-actual') {
        $sql .= " AND MONTH(c.fecha_pedido) = MONTH(CURRENT_DATE()) AND YEAR(c.fecha_pedido) = YEAR(CURRENT_DATE())";
    } elseif ($opcionFecha == 'mes-especifico' && !empty($mes) && !empty($anio)) {
        $sql .= " AND MONTH(c.fecha_pedido) = '$mes' AND YEAR(c.fecha_pedido) = '$anio'";
    }

    // Agrupar los resultados por tipo, marca y modelo
    $sql .= " GROUP BY v.tipo, v.marca, v.modelo ORDER BY total_servicios DESC;";

    // Ejecutar la consulta
    $result = $llave->query($sql);
    return $result;
}

function consulta5PDF() {
    $llave = conectarse();

    // Recuperar los parámetros de la solicitud POST
    $anio = isset($_POST['anio']) ? $_POST['anio'] : null;
    $fechaInicio = isset($_POST['fechaInicio']) ? $_POST['fechaInicio'] : null;
    $fechaFin = isset($_POST['fechaFin']) ? $_POST['fechaFin'] : null;
    $opcionFecha = isset($_POST['opcionFecha']) ? $_POST['opcionFecha'] : null;
    $mes = isset($_POST['mes']) ? $_POST['mes'] : null;

    // Consulta SQL combinada para obtener clientes y su vehículo con más cotizaciones
    $sql = "
        WITH vehiculo_cotizaciones AS (
            SELECT 
                v.id_vehiculo,
                v.id_cliente,
                COUNT(co.id_cotizacion) AS total_cotizaciones,
                v.marca,
                v.modelo,
                v.año,
                v.color,
                ROW_NUMBER() OVER (PARTITION BY v.id_cliente ORDER BY COUNT(co.id_cotizacion) DESC) AS rn
            FROM 
                vehiculos v
            INNER JOIN 
                cotizaciones co ON v.id_vehiculo = co.id_vehiculo
            WHERE 1=1"; // Para facilitar la adición de condiciones

    // Agregar condiciones basadas en la opción de fecha
    if ($opcionFecha == 'anio-actual') {
        $sql .= " AND YEAR(co.fecha_pedido) = YEAR(CURRENT_DATE())"; // Suponiendo que hay una columna fecha_pedido en cotizaciones
    } elseif ($opcionFecha == 'anio-especifico' && !empty($anio)) {
        $sql .= " AND YEAR(co.fecha_pedido) = '$anio'";
    } elseif ($opcionFecha == 'rango-fechas' && !empty($fechaInicio) && !empty($fechaFin)) {
        $sql .= " AND co.fecha_pedido BETWEEN '$fechaInicio' AND '$fechaFin'";
    } elseif ($opcionFecha == 'mes-actual') {
        $sql .= " AND MONTH(co.fecha_pedido) = MONTH(CURRENT_DATE()) AND YEAR(co.fecha_pedido) = YEAR(CURRENT_DATE())";
    } elseif ($opcionFecha == 'mes-especifico' && !empty($mes) && !empty($anio)) {
        $sql .= " AND MONTH(co.fecha_pedido) = '$mes' AND YEAR(co.fecha_pedido) = '$anio'";
    }

    // Cierre de la consulta
    $sql .= "
            GROUP BY 
                v.id_vehiculo, v.id_cliente, v.marca, v.modelo, v.año, v.color
        )
        SELECT 
            c.id_cliente, 
            c.nombres, 
            c.apellidos,
            vc.marca, 
            vc.modelo,
            vc.año,
            vc.color,
            vc.total_cotizaciones
        FROM 
            clientes c
        INNER JOIN 
            vehiculo_cotizaciones vc ON c.id_cliente = vc.id_cliente
        WHERE 
            vc.rn = 1 -- Solo el vehículo con más cotizaciones por cliente
        ORDER BY 
            vc.total_cotizaciones DESC;";

    // Ejecutar la consulta
    $result = $llave->query($sql);
    return $result;
}


 ?>
