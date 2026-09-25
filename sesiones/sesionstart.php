<?php 
session_start();
if (!$_SESSION['verificacion']) {
	header("location:../sesiones/sesionclose.php");
}
if ($_SESSION['nivel']<=2) {
    $clase="basic";
}elseif ($_SESSION['nivel']>=3 AND $_SESSION['nivel']<5) {
    $clase="medium";
}elseif ($_SESSION['nivel']==5) {
     $clase="premium";
}

/*if (!isset($cotizacion)) {
    unset($_SESSION['arreglo']);
    unset($_SESSION['arregloRepuestos']);
    $_SESSION['arreglo'] = array();
    $_SESSION['arregloRepuestos'] = array();
}*/



 ?>