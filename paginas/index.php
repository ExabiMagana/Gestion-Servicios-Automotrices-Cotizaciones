<?php 
	include '../sesiones/sesionstart.php';
	include '../database/conexion.php';
	$_SESSION['previo']="index.php";
 ?>
<!DOCTYPE html>
<html>
<head>
	 <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="../estilos/style.css">
</head>
<body>
	<?php include '../complementos/sidebar.php'; ?>
	<div class="main">
		<?php 
			$inicio="si";
			include '../complementos/topbar.php'; 
		?>
		<div class="content">
			<div class="cardBox" style=" width : 98%; margin: 0 auto; background: var(--black2); margin-top: 5px; color: white;"><strong>Tarjetas de Información</strong></div>
			<div class="cardBox">
				<?php 
					if ($_SESSION['nivel']<=2) {
				        $clase="basic";
				    }elseif ($_SESSION['nivel']>=3 AND $_SESSION['nivel']<5) {
				        $clase="medium";
				    }elseif ($_SESSION['nivel']==5) {
				         $clase="premium";
				    }

				    if ($_SESSION['nivel']>=3) {
						generarTargetas("usuarios","person-circle-outline",$clase,"usuarios","ion-icon");
					}
					generarTargetas("clientes","man-outline",$clase,"clientes","ion-icon");
					generarTargetas("cotizaciones","journal-outline",$clase,"cotizaciones","ion-icon");
					generarTargetas("mecánicos","build-outline",$clase,"mecanicos","ion-icon"); 
					generarTargetas("repuestos","construct-outline",$clase,"repuestos","ion-icon");
					if ($_SESSION['nivel']==5) {
				       generarTargetas("roles","carbon:user-role",$clase,"roles","iconify-icon");
				    }
					generarTargetas("servicios","mdi:mechanic",$clase,"servicios","iconify-icon");
					generarTargetas("vehiculos","car-sport-outline",$clase,"vehiculos","ion-icon");
				?>
			</div>
			<div class="cardBox" style=" width : 98%; margin: 0 auto; background: var(--black2); margin-top: 35px; color: white;"><strong>Graficas</strong></div>
			<div style="display: flex;">
				<iframe src="../Graficaschartjs/index.php"  style="width: 68%; margin: 1%; height: 470px; border: 0px; border-radius: 5px; box-shadow: 0 7px 25px rgba(0, 0, 0, 0.08); margin-top: 10px;"></iframe>
			 	<iframe src="../Graficaschartjs/pastel.php"  style="width: 28%; margin: 1%; height: 470px; border: 0px; border-radius: 5px; box-shadow: 0 7px 25px rgba(0, 0, 0, 0.08); margin-top: 10px;"></iframe>
			</div>
		</div>
	</div>

   <?php include '../complementos/implementos.php'; ?>
</body>
</html>