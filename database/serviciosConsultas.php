<?php 

function insertarServicios(){

	$nombre=$_POST['servicio'];
	$descripcion=$_POST['descripcion'];
	$precio=$_POST['precio'];
			
	$consulta = "INSERT INTO servicios (id_servicio, nombre_servicio, descripcion, precio) VALUES (NULL,'$nombre','$descripcion','$precio')";
	
    $llave = conectarse();
   	$ejecutarConsulta = $llave->query($consulta);

	echo "<script>window.location.href='servicios.php?mensajeExitoInsertar=true';</script>";
	include "../complementos/forminsertservicios.php";
}

function eliminarServicios(){

	$id=$_GET['id'];
	$llave = conectarse();
	$consulta = "DELETE FROM servicios WHERE id_servicio=$id";
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

function mostrarServicios($clase, $limite, $inicio) {
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
    $consultaTotal = "SELECT COUNT(*) as total FROM servicios WHERE nombre_servicio LIKE '%$buscar%'";
    $resultadoTotal = $llave->query($consultaTotal);
    $totalRegistros = $resultadoTotal->fetch_assoc()['total'];

    // Contar el total de registros
    $consultaTotal2 = "SELECT COUNT(*) as total FROM servicios";
    $resultadoTotal2 = $llave->query($consultaTotal2);
    $totalRegistros2 = $resultadoTotal2->fetch_assoc()['total'];

    // Obtener los registros de la página actual con filtro de búsqueda
    $consulta = "SELECT * FROM servicios WHERE nombre_servicio LIKE '%$buscar%' LIMIT $inicio, $limite";
    $ejecutarConsulta = $llave->query($consulta);
	?>
<div class="table-container">
	<a href='servicios.php?forminsert=si'><button class='btn-add <?php echo $clase ?>'>Agregar Registro</button></a>
    <table border="1" class="styled-table">
    	<thead class="<?php echo $clase ?>">
        		<tr>
        			<th colspan="1">
        				<div class="search">
        					<form method="POST">
						        <label>
						            <input type="text" id="buscar" name="buscar" placeholder="Buscar aquí" onkeyup="buscarDinamico(this.value)" on>
						            <ion-icon name="search-outline"></ion-icon>
						        </label>
						        <?php 
						        	if (isset($_SESSION['buscar'])) {
						        		echo "<a href='servicios.php'><iconify-icon icon='ion:reload-circle-sharp'></iconify-icon></a>";
						        
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
                <th>Servicio <input type="checkbox" name="order"></th>
                <th>Descripción</th>    
                <th>Precio</th>
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
		            <td>" . $datos["nombre_servicio"] . "</td>
		            <td>" . $datos["descripcion"] . "</td>    
		            <td>" . $datos["precio"] . "</td>";
		            if ($_SESSION['nivel'] == 5) {
		      			echo  "<td><a class='btn-delete' href='#' onclick='confirmarEliminacion(" . $datos["id_servicio"] . ")'>Eliminar</a></td>";
		      			//<td><a class='btn-delete' href='servicios.php?eliminar=si&id=" . $datos["id_servicio"] . (isset($forminsert) ? "&" . $forminsert : "") . "'>Eliminar</a></td>";
		    		} 
		           	echo "
		            <td><a class='btn-edit' href='servicios.php?forminsert=si&&actualizar=si&&id=" . $datos["id_servicio"] . "'>Editar</a></td>
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
			    echo "<a href='servicios.php?pagina=1' class='btn-pagina $clase'><iconify-icon icon='octicon:move-to-start-16'></iconify-icon></a>";
			}

            $rango = 4; 
            $inicio = max(1, $pagActual - $rango); 
            $fin = min($totalPaginas, $pagActual + $rango);

            if ($inicio > 1) {
                echo "<a href='servicios.php?pagina=1' class='btn-pagina $clase'>1</a>";
                if ($inicio > 2) {
                    echo "...";
                }
            }

            for ($i = $inicio; $i <= $fin; $i++) {
			    if ($i == $pagActual) {
			        echo "<a href='servicios.php?pagina=$i' class='btn-pagina $clase active'>$i</a>";
			    } else {
			        echo "<a href='servicios.php?pagina=$i' class='btn-pagina $clase'>$i</a>";
			    }
			}

            if ($fin < $totalPaginas) {
                if ($fin < $totalPaginas - 1) {
                    echo "...";
                }
                echo "<a href='servicios.php?pagina=$totalPaginas' class='btn-pagina $clase'>$totalPaginas</a>";
            }

            if ($pagActual < $totalPaginas) {
			    echo "<a href='servicios.php?pagina=$totalPaginas' class='btn-pagina $clase'><iconify-icon icon='octicon:move-to-end-16'></iconify-icon></a>";
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
			<!-- Mensajes de Éxito -->
			<div id="mensaje-exito" class="mensaje-exito" style="display:none;">¡Registro eliminado con éxito!</div>
			<div id="mensaje-exito-insertar" class="mensaje-exito mensaje-insertar" style="display:none;">¡Registro Insertado con éxito!</div>
			<div id="mensaje-exito-actualizar" class="mensaje-exito mensaje-exito-estado" style="display:none;">¡Registro Actualizado con éxito!</div>
		</div>
</div>

<?php 
/*<td><a class='btn-details' href='principal.php?id=" . $datos["id_mecánico"] . "'>Detalles</a></td>
		            <td><a class='btn-print' href='principal.php?id=" . $datos["id_servicio"] . "'>Imprimir</a></td>*/
}
function buscarActualizar() {
	$id=$_GET['id'];
	$llave = conectarse();
	$consulta = "SELECT * FROM servicios WHERE id_servicio=$id;";
	$ejecutarConsulta=$llave->query($consulta);
	$datos=$ejecutarConsulta->fetch_assoc();
	return $datos;
}

function actualizarServicios() {
	$id=$_POST['id'];
	$nombre=$_POST['servicio'];
	$descripcion=$_POST['descripcion'];
	$precio=$_POST['precio'];

	$consulta = "UPDATE servicios SET nombre_servicio='$nombre', descripcion='$descripcion', precio=$precio WHERE id_servicio=$id";
    $llave = conectarse();
   	$ejecutarConsulta = $llave->query($consulta);
   	echo "<script>window.location.href='servicios.php?mensajeExitoActualizar=true';</script>";
}

function orderById() {
	$llave=conectarse();
	$consulta = "SELECT * FROM usuarios ORDER BY id_servicio ASC";
	//$consulta = "SELECT * FROM usuarios ORDER BY id_servicio DESC";
	$ejecutarConsulta=$llave->query($consulta);
}

 ?>