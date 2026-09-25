<?php 
	if (isset($_POST['enviar'])) {

		insertarRepuestos();

	}elseif (isset($_GET['eliminar'])){

		eliminarRepuestos();

	}elseif (isset($_GET['actualizar'])) {

		$datos=buscarActualizar();

		if (isset($_GET['forminsert'])){

			include "../complementos/forminsertrepuestos.php";
		}

	}elseif (isset($_POST['update'])) {

	    $id = $_POST['id'];
	    actualizarRepuestos($id);	
	    echo "<script>window.location.href='repuestos.php';</script>";
	    
	}elseif (isset($_GET['forminsert'])){
			
		include "../complementos/forminsertrepuestos.php";
				
	}
 ?>