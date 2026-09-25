<?php 

session_start();
session_destroy();

if (isset($_GET['salir'])) {
	header("location:../paginas/login.php");
}elseif (isset($_GET['error2'])) {
	header("location:../paginas/login.php?error2=1");
}elseif (isset($_GET['eliminado'])) {
	echo "<script>window.location.href='../paginas/login.php?mensajeExitoEliminar=true';</script>";
}else {
	header("location:../paginas/login.php?error=1");
}


 ?>