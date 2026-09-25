<?php 
	if (isset($_POST['enviar'])) {

		insertarServicios();

	}elseif (isset($_GET['eliminar'])){

		eliminarServicios();

	}elseif (isset($_GET['actualizar'])) {

		$datos=buscarActualizar();

		if (isset($_GET['forminsert'])){

			include "../complementos/forminsertservicios.php";
		}

	}elseif (isset($_POST['update'])) {

	    $id = $_POST['id'];
	    actualizarServicios($id);
	    echo "<script>window.location.href='servicios.php';</script>";
	    
	}elseif (isset($_GET['forminsert'])){

		include "../complementos/forminsertservicios.php";
	}
 ?>