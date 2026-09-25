<?php 

function generarTargetas($tabla, $icono, $clase, $redireccion, $tipoicon) {
	$llave=conectarse();
	$sql = "SELECT COUNT(*) AS total_registros FROM $tabla";
	$ejecutarConsulta = $llave->query($sql);

	while ($datos=$ejecutarConsulta->fetch_assoc()) {
		echo " <a href='$redireccion.php'>
		<div class='card $clase'>";
        	if ($tipoicon=="iconify-icon") {
        		echo "<iconify-icon icon='$icono'></iconify-icon>";
        	}else {
        		echo "<ion-icon name='$icono'></ion-icon>";
        	}
        	echo "
        	<h2>". ucfirst($tabla) ." <br>Actuales</h2>
        	<p><span class=numbers> " . $datos['total_registros'] . " </span></p>
    	</div></a>";
	}

}

function obtenerEmpresa() {
	$llave=conectarse();
	$sql = "SELECT * FROM empresa";
	$ejecutarConsulta = $llave->query($sql);
	$datos=$ejecutarConsulta->fetch_assoc();

	return $datos;
}

function buscadorUsuario() {
	$id=$_SESSION['id_user'];
	$llave=conectarse();
    $sql = "SELECT * FROM usuarios u, roles r WHERE u.id_rol=r.id_rol AND u.id_usuario = $id";
    $ejecutarConsulta = $llave->query($sql);
    $datos=$ejecutarConsulta->fetch_assoc();

    $_SESSION['nombre_rol']=$datos['nombre_rol'];
    $_SESSION['correo']=$datos['correo'];

	return $datos;
}

function actualizarPerfil() {
	$id = $_SESSION['id_user'];
	$nombreUs = $_POST['nombre_usuario'];
    $email = $_POST['correo'];
    $password = $_POST['password_actual'];
    $nuevapassword = $_POST['nueva_password'];

    $foto=$_FILES['nueva_foto']['tmp_name'];
	$ruta="../img/subidas/".$_FILES['nueva_foto']['name'];
	move_uploaded_file($foto, $ruta);

	$llave = conectarse();
	$sql = "SELECT contraseña FROM usuarios WHERE id_usuario=$id";
	$ejecutarConsulta = $llave->query($sql);
	$datos = $ejecutarConsulta->fetch_assoc();

	if ($datos['contraseña']==$password) {

	 	if ($nuevapassword !== "") {

			if ($_FILES['nueva_foto']['name']>1) {
				
				$consulta = "UPDATE usuarios SET nombre_usuario='$nombreUs', correo='$email', foto='$ruta', contraseña='$nuevapassword' WHERE id_usuario=$id";
				$_SESSION['foto'] = $ruta; // Actualizamos la ruta de la foto

			}else {
				$consulta = "UPDATE usuarios SET nombre_usuario='$nombreUs', correo='$email', contraseña='$nuevapassword' WHERE id_usuario=$id";
			}

		}else{

			if ($_FILES['nueva_foto']['name']>1) {
				
				$consulta = "UPDATE usuarios SET nombre_usuario='$nombreUs', correo='$email', foto='$ruta' WHERE id_usuario=$id";
				$_SESSION['foto'] = $ruta; // Actualizamos la ruta de la foto

			}else {
				$consulta = "UPDATE usuarios SET nombre_usuario='$nombreUs', correo='$email' WHERE id_usuario=$id";
			}

		}

		$ejecutarConsulta = $llave->query($consulta);

	   	// Ahora actualizamos las variables de sesión con la nueva información
	    $_SESSION['username'] = $nombreUs; // Actualizamos el nombre de usuario
	    $_SESSION['email'] = $email; // Añadir el correo si es necesario

		echo "<script>window.location.href='perfil.php?mensajeExitoActualizar=true';</script>";

	}else {
		echo "<script>window.location.href='perfil.php?mostrarMensajeError=true';</script>";
	}
		  
}

 ?>
