<?php 

function insertarClientes(){

	$nombres=$_POST['nombres'];
	$apellidos=$_POST['apellidos'];
	$telefono=$_POST['telefono'];
	$correo =$_POST['correo'];
	$direccion = !empty($_POST['direccion']) ? $_POST['direccion'] : "Sin Información";
			
	$consulta = "INSERT INTO clientes (id_cliente, nombres, apellidos, telefono, correo, direccion) VALUES (NULL,'$nombres','$apellidos','$telefono','$correo','$direccion')";
	
    $llave = conectarse();
   	$ejecutarConsulta = $llave->query($consulta);

	echo "<script>window.location.href='clientes.php?mensajeExitoInsertar=true';</script>";
	include "../complementos/forminsertclientes.php";
	mostrarClientes($clase);

}

function eliminarClientes(){

	$id=$_GET['id'];
	$llave = conectarse();
	$consulta = "DELETE FROM clientes WHERE id_cliente=$id";
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

function mostrarClientes($clase, $limite, $inicio) {
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
    $consultaTotal = "SELECT COUNT(*) as total FROM clientes WHERE nombres LIKE '%$buscar%' OR apellidos LIKE '%$buscar%'";
    $resultadoTotal = $llave->query($consultaTotal);
    $totalRegistros = $resultadoTotal->fetch_assoc()['total'];

    // Contar el total de registros
    $consultaTotal2 = "SELECT COUNT(*) as total FROM clientes";
    $resultadoTotal2 = $llave->query($consultaTotal2);
    $totalRegistros2 = $resultadoTotal2->fetch_assoc()['total'];

    // Obtener los registros de la página actual con filtro de búsqueda
    $consulta = "SELECT * FROM clientes WHERE nombres LIKE '%$buscar%' OR apellidos LIKE '%$buscar%' LIMIT $inicio, $limite";
    $ejecutarConsulta = $llave->query($consulta);
	?>
<div class="table-container">
	<a href='clientes.php?forminsert=si'><button class='btn-add <?php echo $clase ?>'>Agregar Registro</button></a>
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
						        		echo "<a href='clientes.php'><iconify-icon icon='ion:reload-circle-sharp'></iconify-icon></a>";
						        
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
                <th>Nombres <input type="checkbox" name="order"></th>
                <th>Apellidos</th>    
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Dirección</th>
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
		            <td>" . $datos["nombres"] . "</td>
		            <td>" . $datos["apellidos"] . "</td>    
		            <td>" . $datos["telefono"] . "</td>
		            <td>" . $datos["correo"] . "</td>
		            <td>" . $datos["direccion"] . "</td>";
		            if ($_SESSION['nivel'] == 5) {
			      			echo  "<td><a class='btn-delete' href='#' onclick='confirmarEliminacion(" . $datos["id_cliente"] . ")'>Eliminar</a></td>";
	                    } 
		           	echo "
		            <td><a class='btn-edit' href='clientes.php?forminsert=si&&actualizar=si&&id=" . $datos["id_cliente"] . "'>Editar</a></td>
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
			    echo "<a href='clientes.php?pagina=1' class='btn-pagina $clase'><iconify-icon icon='octicon:move-to-start-16'></iconify-icon></a>";
			}

            $rango = 4; 
            $inicio = max(1, $pagActual - $rango); 
            $fin = min($totalPaginas, $pagActual + $rango);

            if ($inicio > 1) {
                echo "<a href='clientes.php?pagina=1' class='btn-pagina $clase'>1</a>";
                if ($inicio > 2) {
                    echo "...";
                }
            }

            for ($i = $inicio; $i <= $fin; $i++) {
			    if ($i == $pagActual) {
			        echo "<a href='clientes.php?pagina=$i' class='btn-pagina $clase active'>$i</a>";
			    } else {
			        echo "<a href='clientes.php?pagina=$i' class='btn-pagina $clase'>$i</a>";
			    }
			}

            if ($fin < $totalPaginas) {
                if ($fin < $totalPaginas - 1) {
                    echo "...";
                }
                echo "<a href='clientes.php?pagina=$totalPaginas' class='btn-pagina $clase'>$totalPaginas</a>";
            }

            if ($pagActual < $totalPaginas) {
			    echo "<a href='clientes.php?pagina=$totalPaginas' class='btn-pagina $clase'><iconify-icon icon='octicon:move-to-end-16'></iconify-icon></a>";
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
		            <td><a class='btn-print' href='principal.php?id=" . $datos["id_cliente"] . "'>Imprimir</a></td>*/
}
function buscarActualizar() {
	$id=$_GET['id'];
	$llave = conectarse();
	$consulta = "SELECT * FROM clientes WHERE id_cliente=$id;";
	$ejecutarConsulta=$llave->query($consulta);
	$datos=$ejecutarConsulta->fetch_assoc();
	return $datos;
}

function actualizarClientes() {
	$id=$_POST['id'];
	$nombres=$_POST['nombres'];
	$apellidos=$_POST['apellidos'];
	$telefono=$_POST['telefono'];
	$correo =$_POST['correo'];
	$direccion = !empty($_POST['direccion']) ? $_POST['direccion'] : "Sin Información";

	$consulta = "UPDATE clientes SET nombres='$nombres', apellidos='$apellidos', telefono='$telefono', correo='$correo', direccion='$direccion' WHERE id_cliente=$id";
    $llave = conectarse();
   	$ejecutarConsulta = $llave->query($consulta);
   	echo "<script>window.location.href='clientes.php?mensajeExitoActualizar=true';</script>";
}

function orderById() {
	$llave=conectarse();
	$consulta = "SELECT * FROM usuarios ORDER BY id_cliente ASC";
	//$consulta = "SELECT * FROM usuarios ORDER BY id_cliente DESC";
	$ejecutarConsulta=$llave->query($consulta);
}

 ?>