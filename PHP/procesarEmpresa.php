<?php 
	if (isset($_GET['actualizar'])) {

		$datos=buscarActualizar();

		if (isset($_GET['forminsert'])){
			include "../complementos/forminsertempresa.php";
			
		}

	}elseif (isset($_POST['update'])) {

	    actualizarEmpresa();
	    echo "<script>window.location.href='empresa.php?mostrar=si';</script>";
	    
	}
	mostrarEmpresa($clase);
 ?>