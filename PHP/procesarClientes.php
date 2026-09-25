<?php 
	if (isset($_POST['enviar'])) {

		insertarClientes();

	}elseif (isset($_GET['eliminar'])){

		eliminarClientes();

	}elseif (isset($_GET['actualizar'])) {

		$datos=buscarActualizar();

		if (isset($_GET['forminsert'])){

			include "../complementos/forminsertclientes.php";
		}

	}elseif (isset($_POST['update'])) {

	    $id = $_POST['id'];
	    actualizarClientes($id);
	    echo "<script>window.location.href='clientes.php';</script>";
	    
	}elseif (isset($_GET['forminsert'])){

		include "../complementos/forminsertclientes.php";

	}
 ?>