<?php 
	if (isset($_POST['enviar'])) {

		insertarMecanicos();

	}elseif (isset($_GET['eliminar'])){

		eliminarMecanicos();

	}elseif (isset($_GET['actualizar'])) {

		$datos=buscarActualizar();

		if (isset($_GET['forminsert'])){

			include "../complementos/forminsertmecanicos.php";
		}

	}elseif (isset($_GET['estado'])) {

		cambiarEstadoMecanicos();

	}elseif (isset($_POST['update'])) {

	    $id = $_POST['id'];
	    actualizarMecanicos($id);
	    echo "<script>window.location.href='mecanicos.php';</script>";

	}elseif (isset($_GET['forminsert'])){

			include "../complementos/forminsertmecanicos.php";
		}
 ?>