<?php 
include '../sesiones/sesionstart.php'; 
include_once '../database/conexion.php';
include_once '../database/reportesConsultas.php'; 
include '../PHP/procesarReportes.php';

$pagina='reportes';

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

$_SESSION['previo']="estadisticas.php";

$llave=conectarse();

// Obtener el total de registros para la paginación
$consultaTotal = "SELECT COUNT(*) AS total FROM (
                    SELECT YEAR(fecha_pedido) AS Año, 
                           MONTH(fecha_pedido) AS Mes, 
                           SUM(total) AS Ingresos_Totales, 
                           COUNT(*) AS Cantidad, 
                           estado
                    FROM cotizaciones
                    WHERE 1=1 
                    GROUP BY estado, YEAR(fecha_pedido), MONTH(fecha_pedido)
                  ) AS subconsulta";

$resultTotal = $llave->query($consultaTotal);
$totalRegistros = $resultTotal->fetch_assoc()['total'];

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
        header("Location: reportes.php?pagina=1");
        exit;
    }
    // Validar que no sea mayor al total de páginas
    if ($pagina > $totalPaginas) {
        header("Location: reportes.php?pagina=$totalPaginas");
        exit;
    }
} else {
    $pagina = 1;  // Página por defecto si no se pasa por la URL
}

$limite = isset($_POST['limite']) ? (int)$_POST['limite'] : 5; // Valor predeterminado
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1; // Página actual
$inicio = ($pagina - 1) * $limite;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes</title>
    <link rel="stylesheet" href="../estilos/style.css">
    <style>
    /* Estilos para la ventana modal */
    .modal {
        display: none; /* Oculto por defecto */
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: #EEEEEE;
        padding: 0px;
        border-radius: 8px;
        width: 40%;
        max-width: 700px;
        position: relative;
    }

    .modal-content iframe {
        margin-top: 20px;
        width: 100%;
        height: 470px;
        border: none; /* Elimina el borde gris */
    }

    .close-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 20px;
        cursor: pointer;
        color: #333;
    }
</style>
</head>
<body>
    <?php include '../complementos/sidebar.php'; ?>
    <div class="main">
        <?php include '../complementos/topbar.php'; ?>
        <div class="content">
            <form class="cardBox" style="background: var(--black2); font-size: 1em; color: white; text-align: center; display: flex;">
                <label for="opcion" style="width :45%; padding: 5px"><b>Selecciona una opción de Reporte:</b></label>
                <select id="opcion" name="opcion" style="width :50%; background-color: ghostwhite; font-size: 1em; border-radius:5px; border: 0; padding: 5px;" onchange="cambiarContenido()">
                    <option value="opcion1">Reporte 1: Reporte de Ingresos Anuales y Promedio Mensual por Estado de Cotizaciones</option>
                    <option value="opcion2">Reporte 2: Reporte de Efectividad de los Mecánicos</option>
                    <option value="opcion3">Reporte 3: Reporte Rentabilidad de Servicios</option>
                    <option value="opcion4">Reporte 4: Reporte de Demanda de Servicios por Vehículo</option>
                    <option value="opcion5">Reporte 5: Reporte de Frecuencia de Clientes</option>
                </select>
            </form>
            <div class="table-container">
                <form action="#" method="POST">
                    <a href="javascript:void(0);" onclick="abrirModal()">
                        <input class='btn-add' type="button" value="Generar PDF">
                    </a>
                </form>
                <div id="contenido-dinamico"><!-- Aquí cambia el contenido dinámico --></div>
            </div>
        </div>
    </div>
    
    <!-- Ventana modal -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="cerrarModal()">&times;</span>
            <h2>Escojer Documento</h2>
            <iframe src="../complementos/formgenerarreporte.php"></iframe>
        </div>
    </div>

    <?php include '../complementos/implementos.php'; ?>

    <script type="text/javascript">
        // Función para abrir el modal
        function abrirModal() {
            document.getElementById("modal").style.display = "flex";
        }

        // Función para cerrar el modal
        function cerrarModal() {
            document.getElementById("modal").style.display = "none";
        }

        function cambiarContenido() {
            var opcion = document.getElementById("opcion").value;

            // Realizar la solicitud AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "../complementos/generarContenido.php?opcion=" + opcion, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById("contenido-dinamico").innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        // Cargar contenido de la opción 1 por defecto
        window.onload = function() {
            cambiarContenido();
        };
    </script>
</body>
</html>
