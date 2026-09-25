<?php 
function conectarse(){
	$host="localhost";
	$user="root";
	$pass="";
	$db="cotizacionesm9";

	$conexion = new mysqli($host, $user, $pass, $db) or die("error en la conexion"); 

	return $conexion;
}

 ?>