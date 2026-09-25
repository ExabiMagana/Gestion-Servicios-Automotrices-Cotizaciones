<?php 
	if (isset($_POST['enviar'])) {

		insertarUsuario();

	}elseif (isset($_GET['eliminar'])){

		eliminarUsuario();

	}elseif (isset($_GET['actualizar'])) {

		$datos=buscarActualizar();

		if (isset($_GET['forminsert'])){

			include "../complementos/forminsertusuarios.php";
		}

	}elseif (isset($_GET['estado'])) {

		cambiarEstadoUsuario();

	}elseif (isset($_POST['update'])) {

	    $id = $_POST['id'];
	    actualizarUsuario($id);
	    echo "<script>window.location.href='usuarios.php';</script>";

	}elseif (isset($_GET['forminsert'])){

			include "../complementos/forminsertusuarios.php";
		}
 ?>