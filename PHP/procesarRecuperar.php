<?php 
include "../database/loginConsultas.php";
if (isset($_POST['recuperar'])) {
	codigoRecuperar();
}
if(isset($_POST['cambiar'])) {
	cambiarContra();
}

 ?>