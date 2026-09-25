<?php 
//este docuento es nuevo, yo lo agregue en la carpeta paginas, usted lo puede agregar en la carpeta que considere conveniente siempre y cuando tome en consideracion la Ruta
$id=$_POST['id'];	

if (isset($_POST['eliminar'])) {
	session_start();
	unset($_SESSION['arreglo'][$id]);
	foreach ($_SESSION['arreglo'] as $key => $value) {
		echo "<li><span>".$key . " - " . $value."</span>";
		?>
		<input class="remove-btn" type="button" name="eliminar" value="Eliminar" onclick="eliminarServicio(<?php echo $key; ?>);"><br>
		<?php
		echo "</li>";
	}
}else if (isset($_POST['mostrar'])) {
	session_start();
		foreach ($_SESSION['arreglo'] as $key => $value) {
		echo "<li><span>".$key . " - " . $value."</span>";
		?>
		<input class="remove-btn" type="button" name="eliminar" value="Eliminar" onclick="eliminarServicio(<?php echo $key; ?>);"><br>
		<?php
		echo "</li>";
	}
}
else{
	session_start();
	$nombre_servicio=$_POST['servicio'];
	$_SESSION['arreglo'][$id]=$nombre_servicio;
	foreach ($_SESSION['arreglo'] as $key => $value) {
		echo "<li><span>".$key . " - " . $value."</span>";
		?>
		<button class="remove-btn" onclick="eliminarServicio(<?php echo $key; ?>);">Eliminar</button><br>
		<?php
		echo "</li>";
	}
}
?>