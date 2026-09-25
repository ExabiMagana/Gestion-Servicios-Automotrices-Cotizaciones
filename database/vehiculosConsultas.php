<?php 

function generarSelect(){
	$llave=conectarse();
	$consulta = "SELECT id_cliente, nombres, apellidos FROM clientes;";
	$ejecutarConsulta=$llave->query($consulta);

	echo "<select name='cliente'>";
	echo "<option value=''></option>";
	while ($datos=$ejecutarConsulta->fetch_assoc()) {
		echo "<option value='".$datos['id_cliente']."'>".$datos['nombres']."&nbsp;".$datos['apellidos']."</option> ";	
	}echo "</select>";

	?>
	<script>
	    $(document).ready(function() {
	        // Inicializar select2 en todos los select con el name 'cliente'
	        $('select[name="cliente"]').select2({
	            placeholder: "Selecciona un cliente",
	            allowClear: true,
		        language: {
		            noResults: function() {
		                return "No se encontraron clientes con ese nombre"; // Cambia este texto según lo que desees mostrar
	            }
	        }
	        });
	    });
	</script>
	<?php
}

function generarSelect_update($cliente_seleccionado) {
    $llave = conectarse();
    $consulta = "SELECT id_cliente, nombres, apellidos FROM clientes";
    $ejecutarConsulta = $llave->query($consulta);

    echo "<select name='cliente'>";
    while ($datos = $ejecutarConsulta->fetch_assoc()) {
        $selected = ($datos['id_cliente'] == $cliente_seleccionado) ? 'selected' : '';
        echo "<option value='" . $datos['id_cliente'] . "' $selected>" . $datos['nombres']."&nbsp;".$datos['apellidos'] . "</option>";
    }
    echo "</select>";

    ?>
	<script>
	    $(document).ready(function() {
	        // Inicializar select2 en todos los select con el name 'cliente'
	        $('select[name="cliente"]').select2({
	            placeholder: "Selecciona un cliente",
	            allowClear: true,
		        language: {
		            noResults: function() {
		                return "No se encontraron clientes con ese nombre"; // Cambia este texto según lo que desees mostrar
	            }
	        }
	        });
	    });
	</script>
	<?php
}

function obtenerValoresEnum($conexion, $tabla, $columna) {
    $consulta = "SHOW COLUMNS FROM $tabla LIKE '$columna'";
    $resultado = $conexion->query($consulta);
    $fila = $resultado->fetch_assoc();
    
    // Obtener la definición del ENUM
    $tipo_enum = $fila['Type'];
    
    // Extraer los valores
    preg_match("/^enum\('(.*)'\)$/", $tipo_enum, $matches);
    $valores = explode("','", $matches[1]);
    
    return $valores;
}

function genererSelect_medida() {
	echo "
        <div>
            <label>Unidad de medida:</label>";
			$valores_enum = obtenerValoresEnum(conectarse(), 'vehiculos', 'unidad_medida');

			echo "<select name='medida'>";
			foreach ($valores_enum as $valor) {
				echo "<option value='$valor'>$valor</option>";
			}
			echo "</select>
        </div>";
}

function generarSelect_enum($valor_actual_del_usuario) {
	echo "
    <div>
        <label>Unidad de medida:</label>";
		$valores_enum = obtenerValoresEnum(conectarse(), 'vehiculos', 'unidad_medida');

		echo "<select name='medida'>";
		foreach ($valores_enum as $valor) {
			$selected = ($valor == $valor_actual_del_usuario) ? 'selected' : '';
			echo "<option value='$valor' $selected>$valor</option>";
		}
		echo "</select>
    </div>";
	 
}

function insertarVehiculos(){

	$cliente=$_POST['cliente'];
	$marca=$_POST['marca'];
	$modelo=$_POST['modelo'];
	$año=$_POST['año'];
	$color=$_POST['color'];
	$placas=$_POST['placas'];
	$chasis=$_POST['chasis'];
	$motor=$_POST['motor'];
	$tipo=$_POST['tipo'];
	$vin=$_POST['vin'];
	$odometro=$_POST['odometro'];
	$medida=$_POST['medida'];
			
	$consulta = "INSERT INTO vehiculos (id_vehiculo, id_cliente, marca, modelo, año, color, placas, chasis, motor, tipo, vin, odometro, unidad_medida) VALUES (NULL, $cliente, '$marca', '$modelo', '$año', '$color','$placas', '$chasis', '$motor', '$tipo', '$vin', '$odometro', '$medida')";
	
    $llave = conectarse();
   	$ejecutarConsulta = $llave->query($consulta);

	echo "<script>window.location.href='vehiculos.php?mensajeExitoInsertar=true';</script>";
	include "../complementos/forminsertvehiculos.php";
	mostrarVehiculos($clase);

}

function eliminarVehiculos(){

	$id=$_GET['id'];
	$llave = conectarse();
	$consulta = "DELETE FROM vehiculos WHERE id_vehiculo=$id";
	$ejecutarConsulta=$llave->query($consulta);

	/*if (isset($_GET['forminsert'])) {
		include "../complementos/forminsertusuarios.php";
	}*/
	?>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
		    // Verificar si hay parámetros en la URL
		    if (window.location.search.includes('eliminar=si')) {
		        // Cambiar la URL eliminando los parámetros
		        window.history.replaceState(null, null, window.location.pathname);
		    }
		});
	</script>
	<?php
}

function mostrarVehiculos($clase, $limite, $inicio) {
	$llave = conectarse();

    if (!isset($_POST['buscar']) || trim($_POST['buscar']) === '') {
        unset($_SESSION['buscar']);
    }

    if (isset($_POST['buscar'])) {
        $_SESSION['buscar'] = $_POST['buscar'];
    }

    $buscar = isset($_SESSION['buscar']) ? $_SESSION['buscar'] : '';

    $pagina = (isset($_GET['pagina'])) ? $_GET['pagina'] : 1 ;

    // Contar el total de registros coincidentes
    $consultaTotal = "SELECT COUNT(*) as total FROM vehiculos v, clientes c WHERE v.id_cliente=c.id_cliente AND (nombres LIKE '%$buscar%' OR apellidos LIKE '%$buscar%' OR marca LIKE '%$buscar%' OR modelo LIKE '%$buscar%' OR año LIKE '%$buscar%' OR color LIKE '%$buscar%' OR placas LIKE '%$buscar%' OR chasis LIKE '%$buscar%' OR motor LIKE '%$buscar%' OR tipo LIKE '%$buscar%' OR vin LIKE '%$buscar%')";
    $resultadoTotal = $llave->query($consultaTotal);
    $totalRegistros = $resultadoTotal->fetch_assoc()['total'];

    // Contar el total de registros
    $consultaTotal2 = "SELECT COUNT(*) as total FROM vehiculos v, clientes c WHERE v.id_cliente=c.id_cliente";
    $resultadoTotal2 = $llave->query($consultaTotal2);
    $totalRegistros2 = $resultadoTotal2->fetch_assoc()['total'];

    // Obtener los registros de la página actual con filtro de búsqueda
    $consulta = "SELECT id_vehiculo, c.nombres AS nombres, c.apellidos AS apellidos, marca, modelo, año, color, placas, chasis, motor, tipo, vin, odometro, unidad_medida FROM vehiculos v, clientes c WHERE v.id_cliente=c.id_cliente AND (nombres LIKE '%$buscar%' OR apellidos LIKE '%$buscar%' OR marca LIKE '%$buscar%' OR modelo LIKE '%$buscar%' OR año LIKE '%$buscar%' OR color LIKE '%$buscar%' OR placas LIKE '%$buscar%' OR chasis LIKE '%$buscar%' OR motor LIKE '%$buscar%' OR tipo LIKE '%$buscar%' OR vin LIKE '%$buscar%')";
    $ejecutarConsulta = $llave->query($consulta);
	?>
<div class="table-container">
	<a href='vehiculos.php?forminsert=si'><button class='btn-add <?php echo $clase ?>'>Agregar Registro</button></a>
    <table border="1" class="styled-table">
    	<thead class="<?php echo $clase ?>">
        		<tr>
        			<th colspan="2">
        				<div class="search">
        					<form method="POST">
						        <label>
						            <input type="text" id="buscar" name="buscar" placeholder="Buscar aquí" onkeyup="buscarDinamico(this.value)" on>
						            <ion-icon name="search-outline"></ion-icon>
						        </label>
						        <?php 
						        	if (isset($_SESSION['buscar'])) {
						        		echo "<a href='vehiculos.php'><iconify-icon icon='ion:reload-circle-sharp'></iconify-icon></a>";
						        
						        	}
						        ?>
        					</form>
					    </div>
					</th>
					<th colspan="1">
						<span>Coincidencias Totales: <?php echo $totalRegistros; ?></span>
        			</th>
        			<th colspan="1">
        				<span>Registros Totales: <?php echo $totalRegistros2; ?></span>
        			</th>
        			<th colspan="8">
        				<form method="POST" action="#">
						    <label for="limite">Registros por página:</label>
						    <select name="limite" onchange="this.form.submit()" class="limite">
						    	<option value="5" <?php echo ($limite == 5) ? 'selected' : ''; ?>>5</option>
						        <option value="10" <?php echo ($limite == 10) ? 'selected' : ''; ?>>10</option>
						        <option value="15" <?php echo ($limite == 15) ? 'selected' : ''; ?>>15</option>
						        <option value="20" <?php echo ($limite == 20) ? 'selected' : ''; ?>>20</option>
						    </select>
						</form>
        			</th>
        		</tr>
        	</thead>
        <thead class="<?php echo $clase ?>">
            <tr>
                <th>Cliente <input type="checkbox" name="order"></th>
                <th>Vehículo</th>    
                <th>Tipo</th>
                <th>Placas</th>
                <th>Códigos</th>
                <th>VIN</th>
                <th>Odómetro</th>
                <th colspan="4">Herramientas</th>
            </tr>
        </thead>
        <tbody>
        <?php  

       if (isset($_GET['forminsert'])) {
		    $forminsert = "forminsert=si";
		}

		while ($datos = $ejecutarConsulta->fetch_assoc()) {
		    echo "
		        <tr>
		            <td>" . $datos["nombres"] . "<br>" . $datos['apellidos'] . "</td>
		            <td>" . $datos["marca"] . "&nbsp;" . $datos["modelo"] . "&nbsp;" .  $datos["color"] . "&nbsp;" . $datos["año"] . "</td>
		            <td>" . str_replace(" ", "", strtoupper($datos['tipo'])). "</td>
		            <td>" . $datos["placas"] . "</td>
		            <td>" . "<strong>Chasis: </strong>" . $datos["chasis"] . "<br>" . "<strong>Motor: </strong>". $datos["motor"] ."</td>
		            <td>" . $datos["vin"] . "</td>
		            <td>" . $datos["odometro"] . "&nbsp;" . $datos["unidad_medida"] . "</td>";
		            if ($_SESSION['nivel'] == 5) {
		      			echo  "<td><a class='btn-delete' href='#' onclick='confirmarEliminacion(" . $datos["id_vehiculo"] . ")'>Eliminar</a></td>";
		    		}
		           	echo "
		            <td><a class='btn-edit' href='vehiculos.php?forminsert=si&&actualizar=si&&id=" . $datos["id_vehiculo"] . "'>Editar</a></td>
		        </tr>";
		}

        ?>
        </tbody>
    </table>
    <!-- Paginador -->
        <div id="paginador">
            <?php
            $totalPaginas = ceil($totalRegistros / $limite);
            $pagActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

            if ($pagActual > 1) {
			    echo "<a href='vehiculos.php?pagina=1' class='btn-pagina $clase'><iconify-icon icon='octicon:move-to-start-16'></iconify-icon></a>";
			}

            $rango = 4; 
            $inicio = max(1, $pagActual - $rango); 
            $fin = min($totalPaginas, $pagActual + $rango);

            if ($inicio > 1) {
                echo "<a href='vehiculos.php?pagina=1' class='btn-pagina $clase'>1</a>";
                if ($inicio > 2) {
                    echo "...";
                }
            }

            for ($i = $inicio; $i <= $fin; $i++) {
			    if ($i == $pagActual) {
			        echo "<a href='vehiculos.php?pagina=$i' class='btn-pagina $clase active'>$i</a>";
			    } else {
			        echo "<a href='vehiculos.php?pagina=$i' class='btn-pagina $clase'>$i</a>";
			    }
			}

            if ($fin < $totalPaginas) {
                if ($fin < $totalPaginas - 1) {
                    echo "...";
                }
                echo "<a href='vehiculos.php?pagina=$totalPaginas' class='btn-pagina $clase'>$totalPaginas</a>";
            }

            if ($pagActual < $totalPaginas) {
			    echo "<a href='vehiculos.php?pagina=$totalPaginas' class='btn-pagina $clase'><iconify-icon icon='octicon:move-to-end-16'></iconify-icon></a>";
			}
            ?>
        </div>
        <div class="ventanasmodales">
			<!-- Modal de confirmación -->
			<div id="modal-confirmacion" class="modal-confirmacion" style="display:none;"> <!-- Cambiado a ID -->
			    <div class="modal-content">
			        <span class="close">&times;</span>
			        <h2>¿Estás seguro?</h2>
			        <p>¿Quieres eliminar este registro? <strong>Esta acción es Irreversible</strong></p>
			        <div class="button-container"> <!-- Contenedor para los botones -->
			            <button class="btn-confirmar">Confirmar</button>
			            <button class="btn-cancelar">Cancelar</button>
			        </div>
			    </div>
			</div>

			<!-- Mensajes de Éxito / Mensajes de Error -->
			<div id="mensaje-exito-insertar" class="mensaje-exito mensaje-insertar" style="display:none;">¡Registro Insertado con éxito!</div>
			<div id="mensaje-exito" class="mensaje-exito" style="display:none;">¡Registro eliminado con éxito!</div>
			<div id="mensaje-exito-actualizar" class="mensaje-exito mensaje-exito-estado" style="display:none;">¡Registro Actualizado con éxito!</div>
		</div>
</div>

<?php 
/*<td><a class='btn-details' href='principal.php?id=" . $datos["id_mecánico"] . "'>Detalles</a></td>
		            <td><a class='btn-print' href='principal.php?id=" . $datos["id_vehiculo"] . "'>Imprimir</a></td>*/
}
function buscarActualizar() {
	$id=$_GET['id'];
	$llave = conectarse();
	$consulta = "SELECT id_vehiculo, c.nombres AS nombres, c.apellidos AS apellidos, marca, modelo, año, color, placas, chasis, motor, tipo, vin, odometro, unidad_medida, v.id_cliente as cliente FROM vehiculos v, clientes c WHERE c.id_cliente=v.id_cliente AND id_vehiculo=$id";
	$ejecutarConsulta=$llave->query($consulta);
	$datos=$ejecutarConsulta->fetch_assoc();
	return $datos;
}

function actualizarVehiculos() {

    $id = $_POST['id'];
    $cliente = $_POST['cliente'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $año = $_POST['año'];
    $color = $_POST['color'];
    $placas = $_POST['placas'];
    $chasis = $_POST['chasis'];
    $motor = $_POST['motor'];
    $tipo = $_POST['tipo'];
    $vin = $_POST['vin'];
    $odometro = $_POST['odometro'];
    $medida = $_POST['medida'];

    $consulta = "UPDATE vehiculos SET id_cliente=$cliente, marca='$marca', modelo='$modelo', año=$año, color='$color', placas='$placas', chasis='$chasis', motor='$motor', tipo='$tipo', vin='$vin', odometro=$odometro, unidad_medida='$medida' WHERE id_vehiculo=$id";
    $llave = conectarse();
    $ejecutarConsulta = $llave->query($consulta);
    echo "<script>window.location.href='vehiculos.php?mensajeExitoActualizar=true';</script>";
}

function orderById() {
	$llave=conectarse();
	$consulta = "SELECT * FROM usuarios ORDER BY id_vehiculo ASC";
	//$consulta = "SELECT * FROM usuarios ORDER BY id_vehiculo DESC";
	$ejecutarConsulta=$llave->query($consulta);
}

 ?>