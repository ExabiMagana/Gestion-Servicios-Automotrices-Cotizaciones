<?php 
include '../database/empresaConsultas.php';
include '../sesiones/sesionstart.php';
$_SESSION['previo']="empresa.php";
?>
<!DOCTYPE html>
<html>
<head>
	 <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empresa</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="../estilos/style.css">
</head>
<body>
	<?php include '../complementos/sidebar.php'; ?>
	<div class="main">
		<?php include '../complementos/topbar.php'; ?>
		<div class="content">
			<?php include '../PHP/procesarempresa.php' ?>
		</div>
	</div>

   <?php include '../complementos/implementos.php'; ?>
   <script type="text/javascript" defer>
   		const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('mensajeExitoActualizar')) {
            const mensajeExito = document.getElementById("mensaje-exito-actualizar");
            mensajeExito.style.display = "block"; // Mostrar el mensaje
            // Ocultar el mensaje después de 2 segundos
            urlParams.delete('mensajeExitoActualizar'); // Eliminar el parámetro
            window.history.replaceState({}, document.title, window.location.pathname + '?' + urlParams.toString());
            setTimeout(() => {
                mensajeExito.style.display = "none";
            }, 2000);
            // Permitir que el mensaje se cierre al hacer clic
            mensajeExito.onclick = function() {
                mensajeExito.style.display = "none";
            };
        }
   </script>
</body>
</html>