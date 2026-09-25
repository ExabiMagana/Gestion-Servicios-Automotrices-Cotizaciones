<?php 
include 'conexion.php';

function mostrarEmpresa($clase) {
    ?>
    <div class="table-container empresa">
    	<h3>Información de la Empresa</h3>
        <?php  
        $llave = conectarse();
        $consulta = "SELECT * FROM empresa";
        $ejecutarConsulta = $llave->query($consulta);

        while ($datos = $ejecutarConsulta->fetch_assoc()) {
            echo "
            <div class='empresa-card'>
                <div class='empresa-logo empresa'>
                    <h2>Logo</h2>
                    <img src='" . htmlspecialchars($datos["logo"]) . "' width='105px' alt='Logo de la empresa'>
                </div>
                <div class='empresa-info'>
                    <h2>Nombre:</h2>
                    <p>" . htmlspecialchars($datos["nombre"]) . "</p>
                </div>
                <div class='empresa-info'>
                    <h2>Teléfono Celular:</h2> 
                    <p>" . htmlspecialchars($datos["celular"]) . "</p>
                </div>
                <div class='empresa-info'>
                    <h2>Teléfono Fijo:</h2>
                    <p>" . htmlspecialchars($datos["fijo"]) . "</p>
                </div>
                <div class='empresa-info'>
                    <h2>Dirección:</h2>
                    <p>" . htmlspecialchars($datos["direccion"]) . "</p>
                </div>
                <div class='empresa-info'>
                    <h2>Correo:</h2>
                    <p>" . htmlspecialchars($datos["correo"]) . "</p>
                </div>
                <div class='empresa-info'>
                    <h2>Eslogan:</h2>
                    <p>" . htmlspecialchars($datos["slogan"]) . "</p>
                </div>
                <div class='empresa-btn-edit'>
                    <a class='btn-edit' href='empresa.php?forminsert=si&&actualizar=si'>Editar</a>
                </div>
            </div>";
        }
        ?>
        <div id="mensaje-exito-actualizar" class="mensaje-exito mensaje-exito-estado" style="display:none;">
            ¡Información de la Empresa modificada con éxito!
        </div>
    </div>
    <?php 
}


function buscarActualizar() {
	$llave = conectarse();
	$consulta = "SELECT * FROM empresa";
	$ejecutarConsulta=$llave->query($consulta);
	$datos=$ejecutarConsulta->fetch_assoc();
	return $datos;
}

function actualizarEmpresa() {

	$nombre=$_POST['nombre'];
	$celular=$_POST['celular'];
	$fijo = (isset($_POST['fijo']) AND $_POST['fijo']<>"") ? $_POST['fijo'] : "---------" ;
	$direccion=$_POST['direccion'];
	$correo=$_POST['correo'];
	$slogan = (isset($_POST['slogan']) AND $_POST['slogan']<>"") ? $_POST['slogan'] : "Sin Especificar" ;

	$foto=$_FILES['foto']['tmp_name'];
	$ruta="../img/logos/".$_FILES['foto']['name'];
	move_uploaded_file($foto, $ruta);

	if ($_FILES['foto']['name']>1) {
		
		$consulta = "UPDATE empresa SET nombre='$nombre', celular='$celular', fijo='$fijo', direccion='$direccion', correo='$correo', slogan='$slogan', logo='$ruta'";

	}else {
		$consulta = "UPDATE empresa SET nombre='$nombre', celular='$celular', fijo='$fijo', direccion='$direccion', correo='$correo', slogan='$slogan'";
	}
    $llave = conectarse();
   	$ejecutarConsulta = $llave->query($consulta);
   	echo "<script>window.location.href='empresa.php?mensajeExitoActualizar=true';</script>";
}

 ?>