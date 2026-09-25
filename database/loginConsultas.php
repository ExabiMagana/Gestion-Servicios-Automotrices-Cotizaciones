<?php 
require '../database/conexion.php';

function verificarLogueo() {
	$llave=conectarse();
	$username=$_POST['username'];
	$pass=$_POST['password'];

	$consulta="SELECT id_usuario, nombre_usuario, u.estado AS estado, nombre_rol, foto, r.nivel AS nivel, u.id_rol FROM usuarios u, roles r WHERE nombre_usuario='$username' AND contraseña='$pass' AND u.id_rol=r.id_rol";
	$ejecutar=$llave->query($consulta);
	$datos=$ejecutar->fetch_assoc();

    if ($datos) {
        $rol_usuario = $datos['id_rol'];
        $consultaAccesos = "SELECT usuarios, mecanicos, servicios, cotizaciones, repuestos, reportes, clientes, vehiculos FROM accesos_roles WHERE id_rol = $rol_usuario";
        $ejecutarAccesos = $llave->query($consultaAccesos);
        $accesos = $ejecutarAccesos->fetch_assoc();
        
        $datos['accesos'] = $accesos;
    }

    return $datos;
}


function generarCodigoRecuperacion($longitud = 6) {
    $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $codigo = '';
    for ($i = 0; $i < $longitud; $i++) {
        $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
    }
    return $codigo;
}

function codigoRecuperar() {
	$correo = $_POST['email'];
	    
	$llave=conectarse();

	$consulta = "SELECT * FROM usuarios WHERE correo = '$correo'";
	$ejecutar=$llave->query($consulta);
	$datos=$ejecutar->fetch_assoc();
		
	if ($datos) {

		$codigo = generarCodigoRecuperacion();

		$sql="UPDATE usuarios SET generico = '$codigo' WHERE correo = '$correo'";
		$ejecutar=$llave->query($sql);
		        
		// Enviar el código al correo
		$to = $correo;
		$subject = "Código de Recuperación de Contraseña";
		$message = "Estimado usuario,\n\n";
		$message .= "Su código de recuperación es: **" . $codigo . "**\n\n";
		$message .= "Haga clic en el siguiente enlace para restablecer su contraseña:\n";
		$message .= "http://localhost/2024/ProyectoExab%C3%ADmaga%C3%B1a/complementos/formRecuperarContra.php\n\n";
		$message .= "Si no solicitó este cambio, ignore este correo.";

		$headers = "From: X"; // Cambia esto por tu dominio

		if (mail($to, $subject, $message, $headers)) {
		    echo "Se ha enviado un código de recuperación a tu correo.";
		}else {
		    echo "<script>window.location.href='../complementos/recuperarContraseña.php?mensajeErrorEnvio=true';</script>";
		}
	}else {
       echo "<script>window.location.href='../complementos/recuperarContraseña.php?mensajeError=true';</script>";
	}
}

function cambiarContra() {
	$password = $_POST['password'];
	$code = $_POST['code'];

	$llave=conectarse();
	$sql = "UPDATE usuarios SET contraseña='$password' WHERE generico='$code'";
	$ejecutar=$llave->query($sql);

	if ($ejecutar) {
		$sql = "UPDATE usuarios SET generico=NULL WHERE generico='$code'";
		$ejecutar=$llave->query($sql);
		header("location:../index.php");
	}

}

 ?>