<?php 
	if (isset($_POST['enviar'])) {

		insertarRol();

	}elseif (isset($_GET['eliminar'])){

		eliminarRol();

	}elseif (isset($_GET['actualizar'])) {

		$datos=buscarActualizar();

		if (isset($_GET['forminsert'])){

			include "../complementos/forminsertroles.php";

		}

	}elseif (isset($_GET['estado'])) {

		cambiarEstadoRol();

	}elseif (isset($_POST['update'])) {

	    $id = $_POST['id'];
	    actualizarRol($id);
	    echo "<script>window.location.href='roles.php';</script>";
	}elseif (isset($_GET['forminsert'])){

			include "../complementos/forminsertroles.php";

		}
 ?>