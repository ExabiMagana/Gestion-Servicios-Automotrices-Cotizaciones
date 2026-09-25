<?php 

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

function generarSelect_enum($valor_actual_del_usuario) {
	 echo "
        <div>
            <label>Estado:</label>";
			$valores_enum = obtenerValoresEnum(conectarse(), 'mecánicos', 'estado');

			echo "<select name='estado'>";
			foreach ($valores_enum as $valor) {
				$selected = ($valor == $valor_actual_del_usuario) ? 'selected' : '';
				echo "<option value='$valor' $selected>$valor</option>";
			}
			echo "</select>
        </div>";
}

function obtenerEdad($f) {
    $date1 = new DateTime("$f");
    $date2 = new DateTime("now");
       
       
    $dif = $date1->diff($date2);
    $dif = $dif->format('%y');
    return $dif;
}

function insertarMecanicos(){

	$nombreMec=$_POST['txt-nombre'];
	$telefono=$_POST['telefono'];
	$especialidad = !empty($_POST['especialidad']) ? $_POST['especialidad'] : "Ninguna";
	$nacimiento=$_POST['nacimiento'];
	$contrato=$_POST['contrato'];

	$foto=$_FILES['foto']['tmp_name'];
	$ruta="../img/subidas/mecanicos/".$_FILES['foto']['name'];
	move_uploaded_file($foto, $ruta);

	if ($_FILES['foto']['name']>1) {
		
		$consulta = "INSERT INTO mecánicos (id_mecánico, nombre, telefono, especialidad, fecha_nacimiento, fecha_contrato, estado, foto) VALUES (NULL,'$nombreMec','$telefono','$especialidad','$nacimiento','$contrato','activo',$ruta)";

	}else {
		$consulta = "INSERT INTO mecánicos (id_mecánico, nombre, telefono, especialidad, fecha_nacimiento, fecha_contrato, estado) VALUES (NULL,'$nombreMec','$telefono','$especialidad','$nacimiento','$contrato','activo')";
	}
	
    $llave = conectarse();
   	$ejecutarConsulta = $llave->query($consulta);

	echo "<script>window.location.href='mecanicos.php?mensajeExitoInsertar=true';</script>";
	include "../complementos/forminsertmecanicos.php";

}

function eliminarMecanicos(){

	$id=$_GET['id'];
	$llave = conectarse();
	$consulta = "DELETE FROM mecánicos WHERE id_mecánico=$id";
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

function mostrarMecanicos($clase,$limite,$inicio) {
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
    $consultaTotal = "SELECT COUNT(*) as total FROM mecánicos WHERE nombre LIKE '%$buscar%'";
    $resultadoTotal = $llave->query($consultaTotal);
    $totalRegistros = $resultadoTotal->fetch_assoc()['total'];

    // Contar el total de registros
    $consultaTotal2 = "SELECT COUNT(*) as total FROM mecánicos	";
    $resultadoTotal2 = $llave->query($consultaTotal2);
    $totalRegistros2 = $resultadoTotal2->fetch_assoc()['total'];

    // Obtener los registros de la página actual con filtro de búsqueda
    $consulta = "SELECT * FROM mecánicos WHERE nombre LIKE '%$buscar%' LIMIT $inicio, $limite";
    $ejecutarConsulta = $llave->query($consulta);
	?>
<div class="table-container">
	<a href='mecanicos.php?forminsert=si'><button class='btn-add <?php echo $clase ?>'>Agregar Registro</button></a>
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
						        		echo "<a href='mecanicos.php'><iconify-icon icon='ion:reload-circle-sharp'></iconify-icon></a>";
						        
						        	}
						        ?>
        					</form>
					    </div>
					</th>
					<th>
						<span>Coincidencias: <?php echo $totalRegistros; ?></span>
        			</th>
        			<th>
        				<span>Registros Totales: <?php echo $totalRegistros2; ?></span>
        			</th>
        			<th colspan="7">
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
                <th>Foto <input type="checkbox" name="order"></th>
                <th>Nombre Mecánico</th>    
                <th>Teléfono</th>
                <th>Especialidad</th>
                <th>Edad</th>
                <th>Estado</th>
                <th colspan="4">Herramientas</th>
            </tr>
        </thead>
        <tbody>
        <?php  

       if (isset($_GET['forminsert'])) {
		    $forminsert = "forminsert=si";
		}

		while ($datos = $ejecutarConsulta->fetch_assoc()) {
			$estado = $datos['estado'];
		    echo "
		        <tr>
		            <td><img src='" . $datos["foto"] . "' width='65px'></td>
		            <td>" . $datos["nombre"] . "</td>    
		            <td>" . $datos["telefono"] . "</td>
		            <td>" . $datos["especialidad"] . "</td>
		            <td>" . obtenerEdad($datos['fecha_nacimiento']) . " Años</td>
		            <td>" . $datos["estado"] . "</td>";
		            if ($_SESSION['nivel'] == 5) {
		      			echo "<td><a class='btn-delete' href='#' onclick='confirmarEliminacion(" . $datos["id_mecánico"] . ")'>Eliminar</a></td>";
		    		} 
		           	echo "
		            <td><a class='btn-edit' href='mecanicos.php?forminsert=si&&actualizar=si&&id=" . $datos["id_mecánico"] . "'>Editar</a></td>";
		            if ($_SESSION['nivel'] >= 3 ) {
		      			echo '
						   <td>
				                <a class="btn-details" href="#" onclick="confirmarActivarDesactivar(' . $datos['id_mecánico'] . ', \'' . $estado . '\')">' . ($estado == 'ACTIVO' ? 'DESACTIVAR' : 'ACTIVAR') . '</a>
				            </td>';
		    		} 
		        echo "
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
			    echo "<a href='mecanicos.php?pagina=1' class='btn-pagina $clase'><iconify-icon icon='octicon:move-to-start-16'></iconify-icon></a>";
			}

            $rango = 4; 
            $inicio = max(1, $pagActual - $rango); 
            $fin = min($totalPaginas, $pagActual + $rango);

            if ($inicio > 1) {
                echo "<a href='mecanicos.php?pagina=1' class='btn-pagina $clase'>1</a>";
                if ($inicio > 2) {
                    echo "...";
                }
            }

            for ($i = $inicio; $i <= $fin; $i++) {
			    if ($i == $pagActual) {
			        echo "<a href='mecanicos.php?pagina=$i' class='btn-pagina $clase active'>$i</a>";
			    } else {
			        echo "<a href='mecanicos.php?pagina=$i' class='btn-pagina $clase'>$i</a>";
			    }
			}

            if ($fin < $totalPaginas) {
                if ($fin < $totalPaginas - 1) {
                    echo "...";
                }
                echo "<a href='mecanicos.php?pagina=$totalPaginas' class='btn-pagina $clase'>$totalPaginas</a>";
            }

            if ($pagActual < $totalPaginas) {
			    echo "<a href='mecanicos.php?pagina=$totalPaginas' class='btn-pagina $clase'><iconify-icon icon='octicon:move-to-end-16'></iconify-icon></a>";
			}
            ?>
        </div>
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

	<!-- Modal de confirmación para activar/desactivar -->
	<div id="modal-activar-desactivar" class="modal-confirmacion" style="display:none;">
	    <div class="modal-content">
	        <span class="close" onclick="cerrarModalActivarDesactivar()">&times;</span>
	        <h2>¿Estás seguro?</h2>
	        <p>¿Quieres <?php echo ($estado == 'ACTIVO') ? 'desactivar' : 'activar'; ?> este mecánico?</p>
	        <div class="button-container">
	            <button class="btn-confirmar-activar-desactivar btn-confirmar">Confirmar</button>
	            <button class="btn-cancelar-activar-desactivar btn-cancelar">Cancelar</button>
	        </div>
	    </div>
	</div>

	<!-- Mensajes de Éxito -->
	<div id="mensaje-exito" class="mensaje-exito" style="display:none;">¡Registro eliminado con éxito!</div>
	<div id="mensaje-exito-insertar" class="mensaje-exito mensaje-insertar" style="display:none;">¡Registro Insertado con éxito!</div>
	<div id="mensaje-exito-actualizar" class="mensaje-exito mensaje-exito-estado" style="display:none;">¡Registro Actualizado con éxito!</div>
	<div id="mensaje-exito2" class="mensaje-exito mensaje-exito-estado" style="display:none;">¡Registro <?php echo ($estado == 'ACTIVO') ? 'activado' : 'desactivado'; ?>  con éxito!</div>
</div>

<?php 
/*<td><a class='btn-details' href='principal.php?id=" . $datos["id_mecánico"] . "'>Detalles</a></td>
		            <td><a class='btn-print' href='principal.php?id=" . $datos["id_mecánico"] . "'>Imprimir</a></td>*/
}
function buscarActualizar() {
	$id=$_GET['id'];
	$llave = conectarse();
	$consulta = "SELECT * FROM mecánicos WHERE id_mecánico=$id;";
	$ejecutarConsulta=$llave->query($consulta);
	$datos=$ejecutarConsulta->fetch_assoc();
	return $datos;
}

function actualizarMecanicos() {

	$id=$_POST['id'];
	$nombreMec=$_POST['txt-nombre'];
	$telefono=$_POST['telefono'];
	$especialidad = !empty($_POST['especialidad']) ? $_POST['especialidad'] : "Ninguna";
	$nacimiento=$_POST['nacimiento'];
	$contrato=$_POST['contrato'];
	$estado=$_POST['estado'];

	$foto=$_FILES['foto']['tmp_name'];
	$ruta="../img/subidas/mecanicos/".$_FILES['foto']['name'];
	move_uploaded_file($foto, $ruta);

	if ($_FILES['foto']['name']>1) {
		
		$consulta = "UPDATE mecánicos SET nombre='$nombreMec', telefono='$telefono', especialidad='$especialidad', fecha_nacimiento='$nacimiento', fecha_contrato='$contrato', estado='$estado', foto='$ruta' WHERE id_mecánico=$id";

	}else {
		$consulta = "UPDATE mecánicos SET nombre='$nombreMec', telefono='$telefono', especialidad='$especialidad', fecha_nacimiento='$nacimiento', fecha_contrato='$contrato', estado='$estado' WHERE id_mecánico=$id";
	}
    $llave = conectarse();
   	$ejecutarConsulta = $llave->query($consulta);
   	echo "<script>window.location.href='mecanicos.php?mensajeExitoActualizar=true';</script>";
}

function cambiarEstadoMecanicos() {
	$id = $_GET['id'];
	$llave = conectarse();
	
	if (isset($_GET["estado"])) {
		
		if ($_GET['estado']=="ACTIVO") {

			$consulta = "UPDATE mecánicos SET estado='INACTIVO' WHERE id_mecánico=$id";

		}elseif ($_GET['estado']=="INACTIVO") {

			$consulta = "UPDATE mecánicos SET estado='ACTIVO' WHERE id_mecánico=$id";

		}

	}

	$ejecutarConsulta=$llave->query($consulta);

	$consulta = "SELECT id_mecánico,estado FROM mecánicos";
	$ejecutarConsulta=$llave->query($consulta);
	$datos=$ejecutarConsulta->fetch_assoc();

	?>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
		    // Verificar si hay parámetros en la URL
		    if (window.location.search.includes('estado')) {
		        // Cambiar la URL eliminando los parámetros
		        window.history.replaceState(null, null, window.location.pathname);
		    }
		});
	</script>
	<?php
}

function orderById() {
	$llave=conectarse();
	$consulta = "SELECT * FROM usuarios ORDER BY id_mecánico ASC";
	//$consulta = "SELECT * FROM usuarios ORDER BY id_mecánico DESC";
	$ejecutarConsulta=$llave->query($consulta);
}

 ?>