<?php 
//este docuento es nuevo, yo lo agregue en la carpeta paginas, usted lo puede agregar en la carpeta que considere conveniente siempre y cuando tome en consideracion la Ruta
$id=$_POST['id'];	

if (isset($_POST['eliminar'])) {
	session_start();
	unset($_SESSION['arregloRepuestos'][$id]);
	foreach ($_SESSION['arregloRepuestos'] as $key => $value) {
		foreach ($value as $key2 => $value2) {
			echo "<li><span>".$key . " - " . $key2 . " -- ". $value2."</span>";
			?>
			<input class="remove-btn" type="button" name="eliminar" value="Eliminar" onclick="eliminarRepuesto(<?php echo $key; ?>);"><br>
            <input class="decrease-btn" type="button" name="restar" value="Restar 1" onclick="restarCantidad(<?php echo $key; ?>);"><br>
			<?php
			echo "</li>";
		}	
	}
}else if (isset($_POST['mostrar'])) {
	session_start();
		foreach ($_SESSION['arregloRepuestos'] as $key => $value) {
		foreach ($value as $key2 => $value2) {
			echo "<li><span>".$key . " - " . $key2 . " -- ". $value2."</span>";
			?>
			<input class="remove-btn" type="button" name="eliminar" value="Eliminar" onclick="eliminarRepuesto(<?php echo $key; ?>);"><br>
            <input class="decrease-btn" type="button" name="restar" value="Restar 1" onclick="restarCantidad(<?php echo $key; ?>);"><br>
			<?php
			echo "</li>";
		}	
	}
}// Verifica si el usuario presionó el botón de restar
else if (isset($_POST['restar'])) {
    session_start();
    // Restar 1 a la cantidad del repuesto
    if (isset($_SESSION['arregloRepuestos'][$id])) {
        $nombre_repuesto = key($_SESSION['arregloRepuestos'][$id]); // Obtener el nombre del repuesto
        $cantidad_actual = $_SESSION['arregloRepuestos'][$id][$nombre_repuesto];

        if ($cantidad_actual > 1) {
            // Si la cantidad es mayor a 1, se resta 1
            $_SESSION['arregloRepuestos'][$id][$nombre_repuesto] = $cantidad_actual - 1;
        } else {
            // Si la cantidad es 1, se elimina el repuesto
            unset($_SESSION['arregloRepuestos'][$id]);
        }
    }

    // Muestra la lista actualizada de repuestos
    foreach ($_SESSION['arregloRepuestos'] as $key => $value) {
        foreach ($value as $key2 => $value2) {
            echo "<li><span>" . $key . " - " . $key2 . " -- " . $value2 . "</span>";
            ?>
            <input class="remove-btn" type="button" name="eliminar" value="Eliminar" onclick="eliminarRepuesto(<?php echo $key; ?>);">
            <input class="decrease-btn" type="button" name="restar" value="Restar 1" onclick="restarCantidad(<?php echo $key; ?>);"><br>
            <?php
            echo "</li>";
        }   
    }

} else{
	session_start();
	$nombre_repuesto=$_POST['repuesto'];

	if (isset($_SESSION['arregloRepuestos'][$id])) {
		if (in_array($_SESSION['arregloRepuestos'][$id], $_SESSION['arregloRepuestos'])) {
		$anterior=$_SESSION['arregloRepuestos'][$id][$nombre_repuesto];
		$_SESSION['arregloRepuestos'][$id][$nombre_repuesto]=$anterior+1;
		foreach ($_SESSION['arregloRepuestos'] as $key => $value) {
			foreach ($value as $key2 => $value2) {
				echo "<li><span>".$key . " - " . $key2 . " -- ". $value2."</span>";
				?>
				<input class="remove-btn" type="button" name="eliminar" value="Eliminar" onclick="eliminarRepuesto(<?php echo $key; ?>);"><br>
                <input class="decrease-btn" type="button" name="restar" value="Restar 1" onclick="restarCantidad(<?php echo $key; ?>);"><br>
				<?php
				echo "</li>";
			}	
		}
	}
	
	}else{
		$nombre_repuesto=$_POST['repuesto'];
		$_SESSION['arregloRepuestos'][$id]=array($nombre_repuesto => 1 );

		foreach ($_SESSION['arregloRepuestos'] as $key => $value) {
			foreach ($value as $key2 => $value2) {
				echo "<li><span>".$key . " - " . $key2 . " -- ". $value2."</span>";
				?>
				<input class="remove-btn" type="button" name="eliminar" value="Eliminar" onclick="eliminarRepuesto(<?php echo $key; ?>);"><br>
                <input class="decrease-btn" type="button" name="restar" value="Restar 1" onclick="restarCantidad(<?php echo $key; ?>);"><br>
				<?php
				echo "</li>";
			}	
		}

	}
}
?>