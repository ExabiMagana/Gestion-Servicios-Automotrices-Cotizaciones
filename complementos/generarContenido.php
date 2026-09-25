 <?php 
$opcion = (isset($_GET['opcion'])) ? $_GET['opcion'] : 'opcion1';

include '../sesiones/sesionstart.php'; 
include_once '../database/conexion.php';
include_once '../database/reportesConsultas.php'; 
include '../PHP/procesarReportes.php';
$_SESSION['previo']="estadisticas.php";

switch ($opcion) {
    case 'opcion1':
?>    
    <table class="styled-table">
        <thead>
            <tr>
                <th colspan="5" style="text-align: center;">Reporte 1: Reporte de Ingresos Anuales y Promedio Mensual por Estado de Cotizaciones</th>
            </tr>
        </thead>
        <tbody>
            <?php consulta1($clase); ?>
        </tbody>
    </table>
<?php 
 break;
  case 'opcion2':
?>    
   <table class="styled-table">
        <thead>
            <tr>
                <th colspan="6" style="text-align: center;">Reporte 2: Reporte de Efectividad de los Mecánicos</th>
            </tr>
        </thead>
        <tbody>
            <?php consulta2($clase); ?>
        </tbody>
    </table>
<?php 
 break;
case 'opcion3':
?>    
   <table class="styled-table">
        <thead>
            <tr>
                <th colspan="6" style="text-align: center;">Reporte 3: Reporte de Rentabilidad de Servicios</th>
            </tr>
        </thead>
        <tbody>
            <?php consulta3($clase); ?>
        </tbody>
    </table>
<?php 
 break;
 case 'opcion4':
?>    
   <table class="styled-table">
        <thead>
            <tr>
                <th colspan="6" style="text-align: center;">Reporte 4: Reporte de Demanda de Servicios por Vehículo</th>
            </tr>
        </thead>
        <tbody>
            <?php consulta4($clase); ?>
        </tbody>
    </table>
<?php 
 break;
  case 'opcion5':
?>    
   <table class="styled-table">
        <thead>
            <tr>
                <th colspan="6" style="text-align: center;">Reporte 5: Reporte de Frecuencia de Clientes</th>
            </tr>
        </thead>
        <tbody>
            <?php consulta5($clase); ?>
        </tbody>
    </table>
<?php 
 break;
}
 ?>