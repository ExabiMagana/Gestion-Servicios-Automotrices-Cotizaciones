<?php 
	if (isset($_POST['enviar'])) {

		insertarVehiculos();

	}elseif (isset($_GET['eliminar'])){

		eliminarVehiculos();

	}elseif (isset($_GET['actualizar'])) {

		$datos=buscarActualizar();

		if (isset($_GET['forminsert'])){

			include "../complementos/forminsertvehiculos.php";
		}

	}elseif (isset($_POST['update'])) {

	    $id = $_POST['id'];
	    actualizarVehiculos($id);
	    echo "<script>window.location.href='vehiculos.php';</script>";
	    
	}elseif (isset($_GET['forminsert'])){
			
		include "../complementos/forminsertvehiculos.php";
				
	}
 ?>