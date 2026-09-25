<?php 
include_once '../database/rolesConsultas.php';
include_once '../sesiones/sesionstart.php';
include_once '../database/conexion.php';
$_SESSION['previo']="roles.php";

$llave = conectarse();

// Contar el total de registros
$consultaTotal = "SELECT COUNT(*) as total FROM roles";
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
        header("Location: roles.php?pagina=1");
        exit;
    }
    // Validar que no sea mayor al total de páginas
    if ($pagina > $totalPaginas) {
        header("Location: roles.php?pagina=$totalPaginas");
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
    <title>Roles</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="../estilos/style.css">
</head>
<body>
	<?php include '../complementos/sidebar.php'; ?>
	<div class="main">
		<?php include '../complementos/topbar.php'; ?>
		<div class="content">
			<?php include '../PHP/procesarRoles.php';?>
			<div id="lista-items">
                <?php 
                	// Si no se ha proporcionado un número de página, permanece en 1
					$inicio = ($pagina > 1) ? ($pagina * $limite) - $limite : 0;
					mostrarRol($clase,$limite,$inicio);

                ?>
            </div>
		</div>
	</div>

   <?php include '../complementos/implementos.php'; ?>

   <script type="text/javascript">
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
            window.location.href = 'roles.php?eliminar=si&id=' + idMecanicoAEliminar + '&mostrarMensaje=true';
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
        }else if (urlParams.has('mostrarMensaje2')) {
            const mensajeExito2 = document.getElementById("mensaje-exito2");
            mensajeExito2.style.display = "block"; // Mostrar el mensaje
            // Ocultar el mensaje después de 2 segundos
            setTimeout(() => {
                mensajeExito2.style.display = "none";
            }, 2000);
            // Permitir que el mensaje se cierre al hacer clic
            mensajeExito2.onclick = function() {
                mensajeExito2.style.display = "none";
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

        let idMecanicoAActivarDesactivar;
        let estadoMecanicoAActivarDesactivar;
        function confirmarActivarDesactivar(id, estado) {
            idMecanicoAActivarDesactivar = id;
            estadoMecanicoAActivarDesactivar = estado;

            let mensaje;
            if (estado === 'HABILITADO') {
                mensaje = '¿Quieres desactivar este rol? <strong>Todos los usuarios que lo posean serán desactivados.</strong>';
            } else {
                mensaje = '¿Quieres activar este rol?';
            }

            document.getElementById("modal-activar-desactivar").style.display = "flex";
            document.getElementById("modal-mensaje-estado").innerHTML = mensaje;
        }
        function cerrarModalActivarDesactivar() {
            document.getElementById("modal-activar-desactivar").style.display = "none";
        }
        document.querySelector(".btn-confirmar-activar-desactivar").onclick = function() {
            // Redirigir a la activación/desactivación
            const actualEstado = estadoMecanicoAActivarDesactivar === 'HABILITADO' ? 'HABILITADO' : 'DESHABILITADO';
            window.location.href = 'roles.php?estado=' + actualEstado + '&id=' + idMecanicoAActivarDesactivar + '&mostrarMensaje2=true'; 
        };
        document.querySelector(".btn-cancelar-activar-desactivar").onclick = cerrarModalActivarDesactivar; 
        window.onclick = function(event) {
            const modalActivarDesactivar = document.getElementById("modal-activar-desactivar");

            if (event.target === modalActivarDesactivar) {
                cerrarModalActivarDesactivar();
            }
        }
    </script>
</body>
</html>