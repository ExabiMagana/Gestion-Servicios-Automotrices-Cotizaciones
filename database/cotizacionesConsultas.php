<?php 
	include_once '../database/conexion.php';

function generarSelectMecanico() {
	$llave=conectarse();
	$consulta="SELECT * FROM mecánicos WHERE estado='ACTIVO'";
	$ejecutar=$llave->query($consulta);

	while ($datos=$ejecutar->fetch_assoc()) {
		echo "<option value='".$datos['id_mecánico']."' id='".$datos['nombre']."'>".$datos["nombre"]."</option>";
	}
}

function generarSelectVehiculo() {
	$llave=conectarse();
	$consulta="SELECT * FROM vehiculos";
	$ejecutar=$llave->query($consulta);

	while ($datos=$ejecutar->fetch_assoc()) {
		echo "<option value='".$datos['id_vehiculo']."' id='".$datos['placas']."'>".$datos["placas"]."</option>";
	}
}

function generarSelect() {
	$llave=conectarse();
	$consulta="SELECT * FROM servicios";
	$ejecutar=$llave->query($consulta);

	while ($datos=$ejecutar->fetch_assoc()) {
		echo "<option value='".$datos['id_servicio']."' id='".$datos['nombre_servicio']."'>".$datos["nombre_servicio"]."</option>";
	}
}

function generarSelectRepuestos() {
	$llave=conectarse();
	$consulta="SELECT * FROM repuestos";
	$ejecutar=$llave->query($consulta);

	while ($datos=$ejecutar->fetch_assoc()) {
		echo "<option value='".$datos['id_repuesto']."' id='".$datos['nombre_repuesto']."'>".$datos["nombre_repuesto"]."</option>";
	}
}

function insertarCotizacion(){

	$vehiculo = $_POST['vehiculo'];
    $mecanico = $_POST['mecanico'];
    
    // Lógica para insertar en la base de datos
    $llave = conectarse(); // Asegúrate de tener tu conexión a la base de datos

    // Inserta la cotización principal (ejemplo)
    $consultaCotizacion = "INSERT INTO cotizaciones (id_cotizacion, id_vehiculo, id_mecánico, fecha_pedido, fecha_expiración, estado, total) VALUES (NULL, $vehiculo, $mecanico, NOW(), DATE_ADD(NOW(), INTERVAL 10 DAY), 'pendiente', 0)";
    $ejecutarConsultaCotizacion = $llave->query($consultaCotizacion);

    // Obtener el ID de la cotización recién insertada
    $idCotizacion = $llave->insert_id;

    // Inserta los servicios seleccionados
    if (isset($_SESSION['arreglo']) && !empty($_SESSION['arreglo'])) {
            foreach ($_SESSION['arreglo'] as $key => $servicio) {
                $queryServicio = "INSERT INTO servicios_cotizaciones (id_cotizacion, id_servicio) VALUES ('$idCotizacion', '$key')";
                $llave->query($queryServicio);
            }
        }

    // Inserta los repuestos seleccionados
   if (isset($_SESSION['arregloRepuestos']) && !empty($_SESSION['arregloRepuestos'])) {
        foreach ($_SESSION['arregloRepuestos'] as $key => $repuesto) {
            foreach ($repuesto as $nombre => $cantidad) {
                $queryRepuesto = "INSERT INTO repuestos_cotizaciones (id_cotizacion, id_repuesto, cantidad) VALUES ('$idCotizacion', '$key', '$cantidad')";
                $llave->query($queryRepuesto);
            }
        }
    }

    unset($_SESSION['arreglo']);
    unset($_SESSION['arregloRepuestos']);
    $_SESSION['arreglo'] = array();
    $_SESSION['arregloRepuestos'] = array();
    // Redireccionar o mostrar un mensaje
   	echo "<script>window.location.href='cotizaciones.php?opcion=mostrar&mensajeExitoInsertar=true';</script>";
   

}

function mostrarCotizacion($clase) {
	?>
		<div class="table-container">
		    <table border="1" class="styled-table">
		        <thead class="<?php echo $clase ?>">
		            <tr>
		                <th>N° <input type="checkbox" name="order"></th>
		                <th>Cliente</th>
		                <th>Vehiculo</th>
		                <th>Fecha de emisión</th>    
		                <th>Fecha de expiración</th>
		                <th>Total</th>
		                <th>Estado</th>
		                <th>Mecánico</th>
		                <th colspan="4">Herramientas</th>
		            </tr>
		        </thead>
		        <tbody>
		        <?php  
			    $llave=conectarse();
				$consulta="SELECT 
						    ct.id_cotizacion AS id_cotizacion,
						    ct.fecha_pedido AS fecha_pedido,
						    ct.fecha_expiración AS fecha_expiración,
						    ct.total AS total,
						    ct.estado AS estado,
						    CONCAT(cl.nombres, ' ', cl.apellidos) AS cliente,
						    cl.correo AS correo_cliente,
						    CONCAT(vh.marca, ' ', vh.modelo, ' ', vh.año, ' ', vh.color, ' ', vh.placas) AS vehiculo,
						    mc.nombre AS mecánico
						FROM
						    cotizacionesm9.cotizaciones ct
						    LEFT JOIN cotizacionesm9.mecánicos mc ON mc.id_mecánico = ct.id_mecánico
						    LEFT JOIN cotizacionesm9.vehiculos vh ON ct.id_vehiculo = vh.id_vehiculo
						    LEFT JOIN cotizacionesm9.clientes cl ON vh.id_cliente = cl.id_cliente
						    LEFT JOIN cotizacionesm9.repuestos_cotizaciones rc ON rc.id_cotizacion = ct.id_cotizacion
						    LEFT JOIN cotizacionesm9.servicios_cotizaciones sc ON sc.id_cotizacion = ct.id_cotizacion
						GROUP BY
						    ct.id_cotizacion
						ORDER BY
						    ct.id_cotizacion, ct.fecha_pedido;
						";
				$ejecutar=$llave->query($consulta);

		       if (isset($_GET['forminsert'])) {
				    $forminsert = "forminsert=si";
				}

				while ($datos = $ejecutar->fetch_assoc()) {
					$id_Cotiza = $datos['id_cotizacion'];
				    echo "
				        <tr>
				            <td>" . $datos["id_cotizacion"] . "</td>
				            <td>" . $datos["cliente"] . "</td>
				            <td>" . $datos["vehiculo"] . "</td>
				            <td>" . $datos["fecha_pedido"] . "</td>
				            <td>" . $datos["fecha_expiración"] . "</td>
				            <td>" . $datos["total"] . "</td>
				            <td>" . $datos["estado"] . "</td>
				            <td>" . $datos["mecánico"] . "</td>";
				            if ($_SESSION['nivel'] == 5) {
				      			echo  "<td><a class='btn-delete' href='#' onclick='confirmarEliminacion(" . $id_Cotiza . ")'>Eliminar</a></td>";
				    		} 
				            //<td><a class='btn-edit' href='cotizaciones.php?forminsert=si&&actualizar=si&&id=" . $datos["id_cotizacion"] . "'>Editar</a></td>
				            echo "       
		            		<td><a class='btn-print' href='cotizaciones.php?detalles=si&id=" . $datos["id_cotizacion"] . "'>Detalles</a></td>
		            		<td><a class='btn-details' target='_blank' href='../fpdf/index.php?generar=imprimir&id=" . $datos["id_cotizacion"] . "'>Imprimir</a></td>
		            		<td><a class='btn-edit' href='#' onclick='confirmarEnvio(" . $datos["id_cotizacion"] . ", \"" . $datos["cliente"] . "\", \"" . $datos["correo_cliente"] . "\")'>Enviar</a></td>
				        </tr>";
				}
		        ?>
		        </tbody>
		    </table>
		    <div class="ventanasmodales">
				<!-- Ventana modal de confirmación -->
				<div id="modal-confirmacion" class="modal-confirmacion" style="display:none;">
				    <div class="modal-content">
				        <span class="close">&times;</span>
				        <h2 id="modal-titulo">¿Estás seguro?</h2>
				        <p id="modal-mensaje"></p>
				        <div class="button-container">
				            <button class="btn-confirmar">Confirmar</button>
				            <button class="btn-cancelar">Cancelar</button>
				        </div>
				    </div>
				</div>
				<div id="modal-confirmacion-envio" class="modal-confirmacion" style="display:none;">
				    <div class="modal-content">
				        <span class="close envio" id="close-envio">&times;</span>
				        <h2 id="modal-titulo-envio">¿Estás seguro?</h2>
				        <p id="modal-mensaje-envio"></p>
				        <div class="button-container" id="button-container">
				            <button class="btn-confirmar envio">Confirmar</button>
				            <button class="btn-cancelar envio">Cancelar</button>
				        </div>
				    </div>
				</div>
			</div>
		</div>
	<?php 
}

function eliminarCotizacion() {
	$id=$_GET['id'];
	$llave = conectarse();
	$consulta = "DELETE FROM cotizaciones WHERE id_cotizacion=$id";
	$ejecutarConsulta=$llave->query($consulta);

	/*if (isset($_GET['forminsert'])) {
		include "../complementos/forminsertusuarios.php";
	}*/
	?>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
		    // Verificar si hay parámetros en la URL
		    const urlParams = new URLSearchParams(window.location.search);

		    // Eliminar los parámetros 'eliminar' y 'id' si están presentes
		    urlParams.delete('eliminar');
		    urlParams.delete('id');
		    urlParams.delete('mostrarMensaje');

		    // Construir la nueva URL con los parámetros restantes
		    const newUrl = window.location.pathname + '?' + urlParams.toString();

		    // Cambiar la URL sin recargar la página
		    window.history.replaceState(null, null, newUrl);
		});
	</script>

	<?php

}


function buscarActualizarCotizacion() {
    $id = $_GET['id'];
    $llave = conectarse();

    // Consulta principal para los datos generales de la cotización
    $queryCotizacion = "
        SELECT 
            ct.id_cotizacion AS id_cotizacion,
            ct.fecha_pedido AS fecha_pedido,
            ct.fecha_expiración AS fecha_expiracion,
            ct.total AS total,
            ct.estado AS estado,
            CONCAT(cl.nombres, ' ', cl.apellidos) AS cliente,
            cl.correo AS correo_cliente,
            CONCAT(vh.marca, ' ', vh.modelo, ' ', vh.año, ' ', vh.color, ' ', vh.placas) AS vehiculo,
            mc.nombre AS mecanico
        FROM
            cotizacionesm9.cotizaciones ct
            LEFT JOIN cotizacionesm9.mecánicos mc ON mc.id_mecánico = ct.id_mecánico
            LEFT JOIN cotizacionesm9.vehiculos vh ON ct.id_vehiculo = vh.id_vehiculo
            LEFT JOIN cotizacionesm9.clientes cl ON vh.id_cliente = cl.id_cliente
        WHERE
            ct.id_cotizacion = $id
    ";

    $resultadoCotizacion = $llave->query($queryCotizacion);
    $cotizacion = $resultadoCotizacion->fetch_assoc();

    // Consulta para obtener los repuestos relacionados con la cotización
    $queryRepuestos = "
        SELECT 
            r.id_repuesto AS id_repuesto,
            r.nombre_repuesto AS nombre_repuesto,
            rc.estado_item AS estado_repuesto
        FROM
            cotizacionesm9.repuestos_cotizaciones rc
        INNER JOIN
            cotizacionesm9.repuestos r ON r.id_repuesto = rc.id_repuesto
        WHERE
            rc.id_cotizacion = $id
    ";

    $resultadoRepuestos = $llave->query($queryRepuestos);
    $repuestos = [];
    while ($repuesto = $resultadoRepuestos->fetch_assoc()) {
        $repuestos[] = $repuesto;
    }

    // Consulta para obtener los servicios relacionados con la cotización
    $queryServicios = "
        SELECT 
            s.id_servicio AS id_servicio,
            s.nombre_servicio AS nombre_servicio,
            sc.estado AS estado_servicio
        FROM
            cotizacionesm9.servicios_cotizaciones sc
        INNER JOIN
            cotizacionesm9.servicios s ON s.id_servicio = sc.id_servicio
        WHERE
            sc.id_cotizacion = $id
    ";

    $resultadoServicios = $llave->query($queryServicios);
    $servicios = [];
    while ($servicio = $resultadoServicios->fetch_assoc()) {
        $servicios[] = $servicio;
    }

    return [
        'cotizacion' => $cotizacion,
        'repuestos' => $repuestos,
        'servicios' => $servicios
    ];
}

function actualizarCotizacion($id_cotizacion) {
    $llave = conectarse();

    // Actualizar el estado de cada repuesto
    foreach ($_POST['estado_repuesto'] as $idRepuesto => $estadoRepuesto) {
        $query = "UPDATE cotizacionesm9.repuestos_cotizaciones 
                  SET estado_item = '$estadoRepuesto' 
                  WHERE id_repuesto = $idRepuesto AND id_cotizacion = $id_cotizacion";
        $llave->query($query);
    }

    // Actualizar el estado de cada servicio
    foreach ($_POST['estado_servicio'] as $idServicio => $estadoServicio) {
        $query = "UPDATE cotizacionesm9.servicios_cotizaciones 
                  SET estado = '$estadoServicio' 
                  WHERE id_servicio = $idServicio AND id_cotizacion = $id_cotizacion";
        $llave->query($query);
    }
}

 ?>