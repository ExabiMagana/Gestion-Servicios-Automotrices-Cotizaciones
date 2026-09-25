<?php 
include_once '../database/serviciosConsultas.php';
include_once '../sesiones/sesionstart.php';
include_once '../database/conexion.php';

$pagina='servicios';

if (isset($_SESSION['accesos'][$pagina]) && $_SESSION['accesos'][$pagina] == 1) {
    if ($clase=='premium') {
        $colorbody='#222831';
    }elseif ($clase=='medium') {
        $colorbody='#39494C';
    }elseif ($clase=='basic') {
        $colorbody='#738988';
    }else{
        $colorbody='#284860';
    }
    include_once '../complementos/implementos.php';
    echo "
        <style type='text/css'>
            body {
                background-color: $colorbody;
                margin: 0;
                font-family: Arial, sans-serif;
            }

            #modal-acceso {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background-color: #fff;
                padding: 20px;
                width: 40%;
                max-width: 500px;
                height: 300px;
                border-radius: 8px;
                border: 1px solid #f00;
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
                z-index: 1000;
                text-align: center;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 15px;
                color: #333;
            }

            #modal-acceso p {
                font-size: 25px;
                margin: 0;
            }

            #modal-acceso iconify-icon {
                color: #17d0ed;
                font-size: 4em;
            }
        </style>
        <div id='modal-acceso'>
            <iconify-icon icon='fluent:person-warning-32-regular'></iconify-icon>
            <p><strong>No tienes acceso a esta página</strong></p>
            <p>Redirigiendo al inicio...</p>
            <iconify-icon icon='eos-icons:loading'></iconify-icon>
        </div>";
    echo "<script>
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 2000);
          </script>";
    exit();
}

$_SESSION['previo']="servicios.php";

$llave = conectarse();

// Contar el total de registros
$consultaTotal = "SELECT COUNT(*) as total FROM servicios";
$resultadoTotal = $llave->query($consultaTotal);
$totalRegistros = $resultadoTotal->fetch_assoc()['total'];

// Configurar el límite y la página
if (isset($_POST['limite'])) {
    $_SESSION['limite'] = (int)$_POST['limite']; // Almacena el límite en la sesión
}

// Valor por defecto
$limite = isset($_SESSION['limite']) ? $_SESSION['limite'] : 10; // Valor por defecto es 10

// Calcular el total de páginas
$totalPaginas = ceil($totalRegistros / $limite);

// Verificar si se ha proporcionado un número de página válido
if (isset($_GET['pagina'])) {
    $pagina = (int)$_GET['pagina'];
    // Validar que no sea menor a 1
    if ($pagina < 1) {
        header("Location: servicios.php?pagina=1");
        exit;
    }
    // Validar que no sea mayor al total de páginas
    if ($pagina > $totalPaginas) {
        header("Location: servicios.php?pagina=$totalPaginas");
        exit;
    }
} else {
    $pagina = 1;  // Página por defecto si no se pasa por la URL
}

?>

<!DOCTYPE html>
<html>
<head>
	 <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="../estilos/style.css">
</head>
<body>
	<?php include '../complementos/sidebar.php'; ?>
	<div class="main">
		<?php include '../complementos/topbar.php'; ?>
		<div class="content">
			<?php include '../PHP/procesarServicios.php';?>
			<div id="lista-items">
                <?php 
                	// Si no se ha proporcionado un número de página, permanece en 1
					$inicio = ($pagina > 1) ? ($pagina * $limite) - $limite : 0;
					mostrarServicios($clase,$limite,$inicio);

                ?>
            </div>
		</div>
	</div>

   <?php include '../complementos/implementos.php'; ?>

   <script defer>
        let idMecanicoAEliminar;
        function confirmarEliminacion(id) {
            idMecanicoAEliminar = id; 
            document.getElementById("modal-confirmacion").style.display = "flex"; 
        }
        function cerrarModal() {
            document.getElementById("modal-confirmacion").style.display = "none";
        }
        document.querySelector(".btn-confirmar").onclick = function() {
            // Redirigir a la eliminación y mostrar el mensaje de éxito
            window.location.href = 'servicios.php?eliminar=si&id=' + idMecanicoAEliminar + '&mostrarMensaje=true';
        };
        document.querySelector(".btn-cancelar").onclick = cerrarModal;
        document.querySelector(".close").onclick = cerrarModal;
        // Cerrar el modal si se hace clic fuera de él
        window.onclick = function(event) {
            const modal = document.getElementById("modal-confirmacion");
            if (event.target === modal) {
                cerrarModal();
            }
        }

        // Mostrar mensaje de éxito si el parámetro está presente
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('mostrarMensaje')) {
            const mensajeExito = document.getElementById("mensaje-exito");
            mensajeExito.style.display = "block"; // Mostrar el mensaje
            // Ocultar el mensaje después de 2 segundos
            setTimeout(() => {
                mensajeExito.style.display = "none";
            }, 2000);
            // Permitir que el mensaje se cierre al hacer clic
            mensajeExito.onclick = function() {
                mensajeExito.style.display = "none";
            };
        }else if (urlParams.has('mensajeExitoInsertar')) {
            const mensajeExitoInsert = document.getElementById("mensaje-exito-insertar");
            mensajeExitoInsert.style.display = "block"; // Mostrar el mensaje
            urlParams.delete('mensajeExitoInsertar'); // Eliminar el parámetro
            window.history.replaceState({}, document.title, window.location.pathname + '?' + urlParams.toString());
            // Ocultar el mensaje después de 2 segundos
            setTimeout(() => {
                mensajeExitoInsert.style.display = "none";
            }, 2000);
            // Permitir que el mensaje se cierre al hacer clic
            mensajeExitoInsert.onclick = function() {
                mensajeExitoInsert.style.display = "none";
            };
        }else if (urlParams.has('mensajeExitoActualizar')) {
            const mensajeExitoInsert = document.getElementById("mensaje-exito-actualizar");
            mensajeExitoInsert.style.display = "block"; // Mostrar el mensaje
            urlParams.delete('mensajeExitoActualizar'); // Eliminar el parámetro
            window.history.replaceState({}, document.title, window.location.pathname + '?' + urlParams.toString());
            // Ocultar el mensaje después de 2 segundos
            setTimeout(() => {
                mensajeExitoInsert.style.display = "none";
            }, 2000);
            // Permitir que el mensaje se cierre al hacer clic
            mensajeExitoInsert.onclick = function() {
                mensajeExitoInsert.style.display = "none";
            };
        }
    </script>
</body>
</html>