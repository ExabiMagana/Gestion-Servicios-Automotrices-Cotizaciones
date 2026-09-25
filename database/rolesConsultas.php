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

function generarSelect_enum($valor_actual_del_usuario,$valores_enum) {
	 echo "
        <div>
            <label for='estado'>Estado:</label>";
			echo "<select id='estado' name='estado'>";
			foreach ($valores_enum as $valor) {
				$selected = ($valor == $valor_actual_del_usuario) ? 'selected' : '';
				echo "<option value='$valor' $selected>$valor</option>";
			}
			echo "</select>
        </div>";
}

function generarSelect_nivelInsert() {
	 echo "
        <div>
            <label for='nivel'>Nivel:</label>";
            $valores_enum = obtenerValoresEnum(conectarse(), 'roles', 'nivel');
			echo "<select id='nivel' name='nivel'>";
			foreach ($valores_enum as $valor) {
				if ($valor<>5) {
					echo "<option value='$valor'>$valor</option>";
				}
			}
			echo "</select>
        </div>";
}

function generarSelect_nivel($valor_actual_del_usuario,$valores_enum) {
	 echo "
        <div>
            <label for='nivel'>Nivel:</label>";
			echo "<select id='nivel' name='nivel'>";
			foreach ($valores_enum as $valor) {
				if ($valor<>5) {
					$selected = ($valor == $valor_actual_del_usuario) ? 'selected' : '';
					echo "<option value='$valor' $selected>$valor</option>";
				}
			}
			echo "</select>
        </div>";
}


function insertarRol(){
    $nombrerol = $_POST['txt-nombre'];
    $nivel = $_POST['nivel'];
    
    $llave = conectarse();
    $consulta = "INSERT INTO roles (id_rol, nombre_rol, estado, nivel) VALUES (NULL, '$nombrerol', 'HABILITADO', '$nivel')";
    $ejecutarConsulta = $llave->query($consulta);

    if ($ejecutarConsulta) {
       	
       	$estadousuario = (isset($_POST['estadousuario'])) ? $_POST['estadousuario'] : 0 ;
       	$estadomecanic = (isset($_POST['estadomecanic'])) ? $_POST['estadomecanic'] : 0 ;
       	$estadoservice = (isset($_POST['estadoservice'])) ? $_POST['estadoservice'] : 0 ;
       	$estadocotices = (isset($_POST['estadocotices'])) ? $_POST['estadocotices'] : 0 ;
       	$estadorepuest = (isset($_POST['estadorepuest'])) ? $_POST['estadorepuest'] : 0 ;
       	$estadoreporte = (isset($_POST['estadoreporte'])) ? $_POST['estadoreporte'] : 0 ;
       	$estadocliente = (isset($_POST['estadocliente'])) ? $_POST['estadocliente'] : 0 ;
       	$estadovehicle = (isset($_POST['estadovehicle'])) ? $_POST['estadovehicle'] : 0 ;

        $id_rol = $llave->insert_id;
        $consultaAccesos = "INSERT INTO accesos_roles (id_rol, usuarios, mecanicos, servicios, cotizaciones, repuestos, reportes, clientes, vehiculos) 
                            VALUES ('$id_rol', $estadousuario, $estadomecanic, $estadoservice, $estadocotices, $estadorepuest, $estadoreporte, $estadocliente, $estadovehicle)";
        $llave->query($consultaAccesos);
    }

    echo "<script>window.location.href='roles.php?mensajeExitoInsertar=true';</script>";
    include "../complementos/forminsertroles.php";
}


function eliminarRol(){

	$id=$_GET['id'];
	$llave = conectarse();
	$consulta = "DELETE FROM roles WHERE id_rol=$id";
	$ejecutarConsulta=$llave->query($consulta);

	/*if (isset($_GET['forminsert'])) {
		include "../complementos/forminsertroles.php";
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

function mostrarRol($clase, $limite, $inicio) {
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
    $consultaTotal = "SELECT COUNT(*) as total FROM roles WHERE nombre_rol LIKE '%$buscar%'";
    $resultadoTotal = $llave->query($consultaTotal);
    $totalRegistros = $resultadoTotal->fetch_assoc()['total'];

    // Contar el total de registros
    $consultaTotal2 = "SELECT COUNT(*) as total FROM roles";
    $resultadoTotal2 = $llave->query($consultaTotal2);
    $totalRegistros2 = $resultadoTotal2->fetch_assoc()['total'];

    // Obtener los registros de la página actual con filtro de búsqueda
    $consulta = "SELECT * FROM roles r, accesos_roles ar WHERE r.id_rol=ar.id_rol AND nombre_rol LIKE '%$buscar%' LIMIT $inicio, $limite";
    $ejecutarConsulta = $llave->query($consulta);
    ?>
<div class="table-container">
	<a href='roles.php?forminsert=si'><button class='btn-add'>Agregar Registro</button></a>
	    <table border="1" class="styled-table active">
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
						        		echo "<a href='roles.php'><iconify-icon icon='ion:reload-circle-sharp'></iconify-icon></a>";
						        
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
	    	<thead>
	            <tr>
	            	<th>N° Rol</th>  
	                <th>Nombre Rol</th>    
	                <th>Estado</th>
	                <th>Nivel</th>
	                <th colspan="4">Herramientas</th>
	            </tr>
	        </thead>
	        <tbody>
	        <?php  
	        
	        $correlativo = 1;

	       if (isset($_GET['forminsert'])) {
			    $forminsert = "forminsert=si";
			}

			while ($datos = $ejecutarConsulta->fetch_assoc()) {
				$estado = $datos['estado'];
				if ($datos['nivel'] == 5) {
					$claseEliminar = "disabled";
					$claseEstado = "disabled";
				} else {
					$claseEliminar = "";
					$claseEstado = "";
				}
				$idRol = $datos["id_rol"]; // Guarda el ID del rol para usarlo en los atributos

				echo "
				    <tr>
				        <td>$correlativo</td>
				        <td>{$datos['nombre_rol']}</td>
				        <td>{$datos['estado']}</td>
				        <td>{$datos['nivel']}</td>
				        <td><a class='btn-delete $claseEliminar' href='#' onclick='confirmarEliminacion($idRol)'>Eliminar</a></td>
				        <td><a class='btn-edit' href='roles.php?forminsert=si&&actualizar=si&&id=$idRol'>Editar</a></td>
				        <td>
				            <input type='checkbox' id='abrir-opciones-$idRol' class='abrir-opciones' style='display: none;'>
				            <a class='btn-print'><label for='abrir-opciones-$idRol'>Acceso</label></a>
				        </td>
				        <td>
				            <a class='btn-details $claseEstado' href='#' onclick='confirmarActivarDesactivar($idRol, \"$estado\")'>" . ($estado == 'HABILITADO' ? 'DESACTIVAR' : 'ACTIVAR') . "</a>
				        </td>
				    </tr>
				    <tr id='opciones-avanzadas-$idRol' style='display: none; text-align: center;'>
				        <td colspan='8'>
				        <strong>Este rol cuenta con acceso a las siguientes páginas:</strong> ". $acces = ($datos['usuarios']==0) ? '&emsp;Usuarios' : '';
				        echo $acces = ($datos['mecanicos']==0) ? '&emsp;Mecánicos' : '';  "</td>";
				        echo $acces = ($datos['servicios']==0) ? '&emsp;Servicios' : '';  "</td>";
				        echo $acces = ($datos['cotizaciones']==0) ? '&emsp;Cotizaciones' : '';  "</td>";
				        echo $acces = ($datos['repuestos']==0) ? '&emsp;Repuestos' : '';  "</td>";
				        echo $acces = ($datos['reportes']==0) ? '&emsp;Reportes' : '';  "</td>";
				        echo $acces = ($datos['clientes']==0) ? '&emsp;Clientes' : '';  "</td>";
				        echo $acces = ($datos['vehiculos']==0) ? '&emsp;Vehículos' : '';  "</td>";
				echo "</tr>
				";

				$correlativo++;
			}

	        ?>
	        </tbody>
	      <script type="text/javascript">
	      	document.querySelectorAll('.abrir-opciones').forEach(function(checkbox) {
			    checkbox.addEventListener('change', function() {
			        const idRol = this.id.split('-')[2]; // Obtener el ID del rol desde el ID del checkbox
			        const opcionesAvanzadas = document.getElementById('opciones-avanzadas-' + idRol);
			        opcionesAvanzadas.style.display = this.checked ? 'table-row' : 'none';
			    });
			});
	      </script>  
	    </table>
	 <!-- Paginador -->
        <div id="paginador">
            <?php
            $totalPaginas = ceil($totalRegistros / $limite);
            $pagActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

            if ($pagActual > 1) {
			    echo "<a href='roles.php?pagina=1' class='btn-pagina $clase'><iconify-icon icon='octicon:move-to-start-16'></iconify-icon></a>";
			}

            $rango = 4; 
            $inicio = max(1, $pagActual - $rango); 
            $fin = min($totalPaginas, $pagActual + $rango);

            if ($inicio > 1) {
                echo "<a href='roles.php?pagina=1' class='btn-pagina $clase'>1</a>";
                if ($inicio > 2) {
                    echo "...";
                }
            }

            for ($i = $inicio; $i <= $fin; $i++) {
			    if ($i == $pagActual) {
			        echo "<a href='roles.php?pagina=$i' class='btn-pagina $clase active'>$i</a>";
			    } else {
			        echo "<a href='roles.php?pagina=$i' class='btn-pagina $clase'>$i</a>";
			    }
			}

            if ($fin < $totalPaginas) {
                if ($fin < $totalPaginas - 1) {
                    echo "...";
                }
                echo "<a href='roles.php?pagina=$totalPaginas' class='btn-pagina $clase'>$totalPaginas</a>";
            }

            if ($pagActual < $totalPaginas) {
			    echo "<a href='roles.php?pagina=$totalPaginas' class='btn-pagina $clase'><iconify-icon icon='octicon:move-to-end-16'></iconify-icon></a>";
			}
            ?>
        </div>
        <div class="ventanasmodales">
			<!-- Modal de confirmación -->
			<div id="modal-confirmacion" class="modal-confirmacion" style="display:none;">
			    <div class="modal-content">
			        <span class="close">&times;</span>
			        <h2>¿Estás seguro?</h2>
			        <p>¿Quieres eliminar este rol?<strong> Si lo eliminas todos los usuarios con este rol también se eliminaran, esta acción es Irreversible</strong></p>
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
			        <p id="modal-mensaje-estado"></p>
			        <div class="button-container">
			            <button class="btn-confirmar-activar-desactivar btn-confirmar">Confirmar</button>
			            <button class="btn-cancelar-activar-desactivar btn-cancelar">Cancelar</button>
			        </div>
			    </div>
			</div>
			<!-- Mensajes de Éxito -->
			<div id="mensaje-exito" class="mensaje-exito" style="display:none;">¡Registro eliminado con éxito!</div>
			<div id="mensaje-exito-insertar" class="mensaje-exito mensaje-insertar" style="display:none;">¡Registro Insertado con éxito!</div>
			<div id="mensaje-exito2" class="mensaje-exito mensaje-exito-estado" style="display:none;">¡Estado del rol modificado con éxito!</div>
			<div id="mensaje-exito-actualizar" class="mensaje-exito mensaje-exito-estado" style="display:none;">¡Registro Actualizado con éxito!</div>
		</div>
</div>

<?php 
}
function buscarActualizar() {
    $id = $_GET['id'];
    $llave = conectarse();
    
    $consultaRol = "SELECT id_rol, nombre_rol, estado, nivel FROM roles WHERE id_rol = $id";
    $ejecutarConsultaRol = $llave->query($consultaRol);
    $datosRol = $ejecutarConsultaRol->fetch_assoc();

    $consultaAccesos = "SELECT usuarios, mecanicos, servicios, cotizaciones, repuestos, reportes, clientes, vehiculos 
                        FROM accesos_roles WHERE id_rol = $id";
    $ejecutarConsultaAccesos = $llave->query($consultaAccesos);
    $datosAccesos = $ejecutarConsultaAccesos->fetch_assoc();

    $datos = array_merge($datosRol, $datosAccesos);

    return $datos;
}

function actualizarRol() {

	$id = $_POST['id'];
	$nombrerol = $_POST['txt-nombre'];
    $estado = (isset($_POST['estado'])) ? $_POST['estado'] : "";
    $nivel= (isset($_POST['nivel'])) ? $_POST['nivel'] : "";

    if ($estado=="" AND $nivel=="") {
    	$estado="HABILITADO";
    	$nivel=5;
    }
		
	$consulta = "UPDATE roles SET nombre_rol='$nombrerol', estado='$estado', nivel='$nivel' WHERE id_rol=$id";
    $llave = conectarse();
   	$ejecutarConsulta = $llave->query($consulta);

   	 if ($ejecutarConsulta) {
        // Segunda consulta para actualizar los accesos en accesos_roles
        $estadousuario = (isset($_POST['estadousuario'])) ? $_POST['estadousuario'] : 0 ;
       	$estadomecanic = (isset($_POST['estadomecanic'])) ? $_POST['estadomecanic'] : 0 ;
       	$estadoservice = (isset($_POST['estadoservice'])) ? $_POST['estadoservice'] : 0 ;
       	$estadocotices = (isset($_POST['estadocotices'])) ? $_POST['estadocotices'] : 0 ;
       	$estadorepuest = (isset($_POST['estadorepuest'])) ? $_POST['estadorepuest'] : 0 ;
       	$estadoreporte = (isset($_POST['estadoreporte'])) ? $_POST['estadoreporte'] : 0 ;
       	$estadocliente = (isset($_POST['estadocliente'])) ? $_POST['estadocliente'] : 0 ;
       	$estadovehicle = (isset($_POST['estadovehicle'])) ? $_POST['estadovehicle'] : 0 ;

        $consultaAccesos = "UPDATE accesos_roles SET usuarios='$estadousuario', mecanicos='$estadomecanic', servicios='$estadoservice', cotizaciones='$estadocotices', repuestos='$estadorepuest', reportes='$estadoreporte', clientes='$estadocliente', vehiculos='$estadovehicle' WHERE id_rol=$id";
        $llave->query($consultaAccesos);
    }

   	echo "<script>window.location.href='roles.php?mensajeExitoActualizar=true';</script>";
}

function cambiarEstadoRol() {
	$id = $_GET['id'];
	$llave = conectarse();
	
	if (isset($_GET["estado"])) {
		
		if ($_GET['estado']=="DESHABILITADO") {

			$consulta = "UPDATE roles SET estado='HABILITADO' WHERE id_rol=$id";

		}elseif ($_GET['estado']=="HABILITADO") {

			$consulta = "UPDATE roles SET estado='DESHABILITADO' WHERE id_rol=$id";

		}

	}

	$ejecutarConsulta=$llave->query($consulta);

	$consulta = "SELECT id_rol,estado FROM roles";
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
	$consulta = "SELECT * FROM roles ORDER BY id_usuario ASC";
	//$consulta = "SELECT * FROM roles ORDER BY id_usuario DESC";
	$ejecutarConsulta=$llave->query($consulta);
}

 ?>