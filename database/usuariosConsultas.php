<?php 

function generarSelect(){
	$llave=conectarse();
	$consulta = "SELECT id_rol, nombre_rol, nivel FROM roles;";
	$ejecutarConsulta=$llave->query($consulta);

	echo "<select id='rol-sel' name='rol'>";
	echo "<option value=''>Selecciona un rol</option>";
	while ($datos=$ejecutarConsulta->fetch_assoc()) {
		if ($datos['nivel']<=$_SESSION['nivel']) {
			echo "<option value='".$datos['id_rol']."'>".$datos['nombre_rol']."</option> ";	
		}
	}echo "</select>";
	?>
	<script>
	    $(document).ready(function() {
	        // Inicializar select2 en todos los select con el name 'rol'
	        $('select[name="rol"]').select2({
	            placeholder: "Selecciona un rol",
	            allowClear: true,
		        language: {
		            noResults: function() {
		                return "No se encontraron roles con ese nombre"; // Cambia este texto según lo que desees mostrar
	            }
	        }
	        });
	    });
	</script>
	<?php
}

function generarSelect_update($rol_seleccionado) {
    $llave = conectarse();
    $consulta = "SELECT id_rol, nombre_rol, nivel FROM roles;";
    $ejecutarConsulta = $llave->query($consulta);

    echo "<select name='rol'>";
    while ($datos = $ejecutarConsulta->fetch_assoc()) {
    	if ($datos['nivel']<=$_SESSION['nivel']) {
	        $selected = ($datos['id_rol'] == $rol_seleccionado) ? 'selected' : '';
	        echo "<option value='" . $datos['id_rol'] . "' $selected>" . $datos['nombre_rol'] . "</option>";
        }
    }
    echo "</select>";

    ?>
	<script>
	    $(document).ready(function() {
	        // Inicializar select2 en todos los select con el name 'rol'
	        $('select[name="rol"]').select2({
	            placeholder: "Selecciona un rol",
	            allowClear: true,
		        language: {
		            noResults: function() {
		                return "No se encontraron roles con ese nombre"; // Cambia este texto según lo que desees mostrar
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

function generarSelect_enum($valor_actual_del_usuario) {
	 echo "
        <div>
            <label>Estado:</label>";
			$valores_enum = obtenerValoresEnum(conectarse(), 'usuarios', 'estado');

			echo "<select name='estado'>";
			foreach ($valores_enum as $valor) {
				$selected = ($valor == $valor_actual_del_usuario) ? 'selected' : '';
				echo "<option value='$valor' $selected>$valor</option>";
			}
			echo "</select>
        </div>";
}

function insertarUsuario(){

	$nombreUs=$_POST['txt-nombre'];
	$email=$_POST['correo'];
	$contra=$_POST['contra'];
	$rol=$_POST['rol'];

	$foto=$_FILES['foto']['tmp_name'];
	$ruta="../img/subidas/".$_FILES['foto']['name'];
	move_uploaded_file($foto, $ruta);

	if ($_FILES['foto']['name']>1) {
		
		$consulta = "INSERT INTO usuarios (id_usuario, nombre_usuario, correo, contraseña, foto, id_rol, estado) VALUES (NULL,'$nombreUs','$email','$contra','$ruta','$rol','activo')";

	}else {
		$consulta = "INSERT INTO usuarios (id_usuario, nombre_usuario, correo, contraseña, id_rol, estado) VALUES (NULL,'$nombreUs','$email','$contra','$rol','activo')";
	}
	
    $llave = conectarse();
   	$ejecutarConsulta = $llave->query($consulta);

	echo "<script>window.location.href='usuarios.php?mensajeExitoInsertar=true';</script>";
	include "../complementos/forminsertusuarios.php";
	mostrarUsuario($clase);

}

function eliminarUsuario(){

	$id=$_GET['id'];
	$llave = conectarse();
	$consulta = "DELETE FROM usuarios WHERE id_usuario=$id";
	$ejecutarConsulta=$llave->query($consulta);

	/*if (isset($_GET['forminsert'])) {
		include "../complementos/forminsertusuarios.php";
	}*/
	if ($id==$_SESSION['id_user']) {
		echo "<script>window.location.href='../sesiones/sesionclose.php?eliminado=true';</script>";
	}
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

function mostrarUsuario($clase, $limite, $inicio){
	$llave = conectarse();

	 if (!isset($_POST['buscar']) || trim($_POST['buscar']) === '') {
        unset($_SESSION['buscar']);
    }

    if (isset($_POST['buscar'])) {
        $_SESSION['buscar'] = $_POST['buscar'];
    }

    $buscar = isset($_SESSION['buscar']) ? $_SESSION['buscar'] : '';

    // Contar el total de registros coincidentes
    $consultaTotal = "SELECT COUNT(*) as total FROM usuarios WHERE nombre_usuario LIKE '%$buscar%'";
    $resultadoTotal = $llave->query($consultaTotal);
    $totalRegistros = $resultadoTotal->fetch_assoc()['total'];

    // Contar el total de registros
    $consultaTotal2 = "SELECT COUNT(*) as total FROM usuarios";
    $resultadoTotal2 = $llave->query($consultaTotal2);
    $totalRegistros2 = $resultadoTotal2->fetch_assoc()['total'];

    // Obtener los registros de la página actual con filtro de búsqueda
    $consulta = "SELECT u.id_usuario AS id_usuario, u.nombre_usuario, u.correo, u.foto, u.estado AS user_estado, r.id_rol, r.nombre_rol, r.nivel, r.estado AS rol_estado FROM usuarios u, roles r WHERE r.id_rol=u.id_rol AND nombre_usuario LIKE '%$buscar%' LIMIT $inicio, $limite";
    $ejecutarConsulta = $llave->query($consulta);
	?>
<div class="table-container">
	<?php
	if (isset($_SESSION['error_message'])) {
	    echo "<div id='errormensaje'>" . $_SESSION['error_message'] . "</div>";
	    unset($_SESSION['error_message']);
	}?>
	<a href='usuarios.php?forminsert=si'><button class='btn-add <?php echo $clase ?>'>Agregar Registro</button></a>
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
					        		echo "<a href='usuarios.php'><iconify-icon icon='ion:reload-circle-sharp'></iconify-icon></a>";
					        
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
					    <select name="limite" id="limite" onchange="this.form.submit()" class="limite">
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
                <th>Nombre Usuario</th>    
                <th>Correo</th>
                <th>Rol</th>
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
			$estado = $datos['user_estado'];
			$id_user = $datos['id_usuario'];
			 $msg = ($_SESSION['id_user'] == $id_user) ? ' Si te eliminas a ti mismo se cerrará sesión automáticamente.' : ''; 

		    if ($datos['nivel'] > $_SESSION['nivel']) {
		    	$claseEliminar = 'disabled';
		        $claseEstado = 'disabled'; 
		        $claseEditar = 'disabled';  
		    } 
		    if ($datos['id_usuario'] == $_SESSION['id_user']) {
		        $claseEliminar = '';
		        $claseEstado = 'disabled'; 
		        $claseEditar = '';           
		    }else {
		        $claseEliminar = '';
		        $claseEditar = '';
		        $claseEstado = '';
		    }

		    echo "
		        <tr>
		            <td><img src='" . $datos["foto"] . "' width='65px'></td>
		            <td>" . $datos["nombre_usuario"] . "</td>    
		            <td>" . $datos["correo"] . "</td>
		            <td>" . $datos["nombre_rol"] . "</td>
		            <td>" . $estado . "</td>";
		            if ($_SESSION['nivel'] == 5) {
		      			echo  "<td><a class='btn-delete $claseEliminar' href='#' onclick='confirmarEliminacion(" . $id_user . ", `" . $msg . "`)'>Eliminar</a></td>";
		    		} 
		           	echo "
		            <td><a class='btn-edit $claseEditar' href='usuarios.php?forminsert=si&&actualizar=si&&id=" . $datos["id_usuario"] . "'>Editar</a></td>";
		             echo '
					    <td>
					        <a class="btn-details ' . $claseEstado . '" href="#" onclick="confirmarActivarDesactivar(' . $datos['id_usuario'] . ', \'' . $estado . '\')">' . ($estado == 'INACTIVO' ? 'ACTIVAR' : 'DESACTIVAR') . '</a>
					    </td>';
		        echo "</tr>";
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
			    echo "<a href='usuarios.php?pagina=1' class='btn-pagina $clase'><iconify-icon icon='octicon:move-to-start-16'></iconify-icon></a>";
			}

            $rango = 4; 
            $inicio = max(1, $pagActual - $rango); 
            $fin = min($totalPaginas, $pagActual + $rango);

            if ($inicio > 1) {
                echo "<a href='usuarios.php?pagina=1' class='btn-pagina $clase'>1</a>";
                if ($inicio > 2) {
                    echo "...";
                }
            }

            for ($i = $inicio; $i <= $fin; $i++) {
			    if ($i == $pagActual) {
			        echo "<a href='usuarios.php?pagina=$i' class='btn-pagina $clase active'>$i</a>";
			    } else {
			        echo "<a href='usuarios.php?pagina=$i' class='btn-pagina $clase'>$i</a>";
			    }
			}

            if ($fin < $totalPaginas) {
                if ($fin < $totalPaginas - 1) {
                    echo "...";
                }
                echo "<a href='usuarios.php?pagina=$totalPaginas' class='btn-pagina $clase'>$totalPaginas</a>";
            }

            if ($pagActual < $totalPaginas) {
			    echo "<a href='usuarios.php?pagina=$totalPaginas' class='btn-pagina $clase'><iconify-icon icon='octicon:move-to-end-16'></iconify-icon></a>";
			}
            ?>
        </div>
        <div class="ventanasmodales">
			<!-- Modal de confirmación -->
			<div id="modal-confirmacion" class="modal-confirmacion" style="display:none;"> <!-- Cambiado a ID -->
			    <div class="modal-content">
			        <span class="close">&times;</span>
			        <h2>¿Estás seguro?</h2>
			        <p id="modal-mensaje"></p> <!-- Aquí se mostrará el mensaje -->
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

			<!-- Mensajes de Éxito / Mensajes de Error -->
			<div id="mensaje-error" class="mensaje-exito" style="display:none; background-color: red;">No se puede activar el usuario porque su rol está deshabilitado.</div>
			<div id="mensaje-exito-insertar" class="mensaje-exito mensaje-insertar" style="display:none;">¡Registro Insertado con éxito!</div>
			<div id="mensaje-exito" class="mensaje-exito" style="display:none;">¡Registro eliminado con éxito!</div>
			<div id="mensaje-exito2" class="mensaje-exito mensaje-exito-estado" style="display:none;">¡Estado del usuario modificado con éxito!</div>
			<div id="mensaje-exito-actualizar" class="mensaje-exito mensaje-exito-estado" style="display:none;">¡Registro Actualizado con éxito!</div>
		</div>

</div>

<?php 
/*<td><a class='btn-details' href='principal.php?id=" . $datos["id_usuario"] . "'>Detalles</a></td>
		            <td><a class='btn-print' href='principal.php?id=" . $datos["id_usuario"] . "'>Imprimir</a></td>*/
}

function buscarActualizar() {
	$id=$_GET['id'];
	$llave = conectarse();
	$consulta = "SELECT id_usuario, nombre_usuario, correo, r.id_rol, u.estado FROM usuarios AS u, roles AS r WHERE u.id_rol=r.id_rol AND id_usuario=$id;";
	$ejecutarConsulta=$llave->query($consulta);
	$datos=$ejecutarConsulta->fetch_assoc();
	return $datos;
}

function actualizarUsuario() {

	$id = $_POST['id'];
	$nombreUs = $_POST['txt-nombre'];
    $email = $_POST['correo'];
    $rol = (isset($_POST['rol'])) ? $_POST['rol'] : '' ;
    $estado = (isset($_POST['estado'])) ? $_POST['estado'] : '' ;

    $foto=$_FILES['foto']['tmp_name'];
	$ruta="../img/subidas/".$_FILES['foto']['name'];
	move_uploaded_file($foto, $ruta);

	if ($_FILES['foto']['name']>1) {
		
		if ($_SESSION['id_user']==$id) {
	   	    $consulta = "UPDATE usuarios SET nombre_usuario='$nombreUs', correo='$email', foto='$ruta' WHERE id_usuario=$id";
			if ($_SESSION['id_user']==$id) {
				$_SESSION['foto'] = $ruta;
			}else{
				$consulta = "UPDATE usuarios SET nombre_usuario='$nombreUs', correo='$email', foto='$ruta', id_rol=$rol, estado='$estado' WHERE id_usuario=$id";
				if ($_SESSION['id_user']==$id) {
					$_SESSION['foto'] = $ruta;
				}
			}
	   	}
		
	}else {
		if ($_SESSION['id_user']==$id) {
			$consulta = "UPDATE usuarios SET nombre_usuario='$nombreUs', correo='$email' WHERE id_usuario=$id";
		}else{
			$consulta = "UPDATE usuarios SET nombre_usuario='$nombreUs', correo='$email', id_rol=$rol, estado='$estado' WHERE id_usuario=$id";
		}
	}
    $llave = conectarse();
   	$ejecutarConsulta = $llave->query($consulta);

   	if ($_SESSION['id_user']==$id) {
   	    // Ahora actualizamos las variables de sesión con la nueva información
	    $_SESSION['username'] = $nombreUs;
	    $_SESSION['rol'] = $rol;
	    $_SESSION['email'] = $email; 
	    $_SESSION['estado'] = $estado;
   	}
    echo "<script>window.location.href='usuarios.php?mensajeExitoActualizar=true';</script>";

}

function cambiarEstadoUsuario() {
    $id = $_GET['id'];
    $llave = conectarse(); 
    
    if (isset($_GET["estado"])) {
        $consultaRol = "SELECT r.estado AS rol_estado FROM usuarios u, roles r WHERE u.id_rol = r.id_rol AND u.id_usuario = $id";
        $ejecutarConsultaRol = $llave->query($consultaRol);
        $datosRol = $ejecutarConsultaRol->fetch_assoc();
        
           if ($datosRol['rol_estado'] == 'DESHABILITADO' && $_GET['estado'] == 'INACTIVO') {
	            // Si el rol está deshabilitado, evitar activar el usuario
	           	echo "<script>window.location.href = 'usuarios.php?mostrarMensajeError=true';</script>";
           		exit; 
	        } else {
	            // Proceder con el cambio de estado del usuario
	            if ($_GET['estado'] == "ACTIVO") {
	                // Actualizar a INACTIVO
	                $consulta = "UPDATE usuarios SET estado='INACTIVO' WHERE id_usuario=$id";
	            } elseif ($_GET['estado'] == "INACTIVO") {
	                // Actualizar a ACTIVO
	                $consulta = "UPDATE usuarios SET estado='ACTIVO' WHERE id_usuario=$id";
	            }

	            $llave->query($consulta);
	        }
	       
    }

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
	$consulta = "SELECT * FROM usuarios ORDER BY id_usuario ASC";
	//$consulta = "SELECT * FROM usuarios ORDER BY id_usuario DESC";
	$ejecutarConsulta=$llave->query($consulta);
}

 ?>