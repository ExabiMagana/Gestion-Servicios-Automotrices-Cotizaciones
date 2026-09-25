<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gráfico de Pastel de Cotizaciones por Estado</title>
    <!-- Enlace a los estilos de Bootstrap para consistencia visual -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body style="background: #FFF !important;">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <br>
            <!-- Formulario para seleccionar el año -->
            <form method="POST">
                <div class="form-group" style="width: 80% !important; margin: 0 auto !important">
                    <label>Año:</label>
                    <div style="display: flex !important; justify-content: space-between!important; width: 100%!important;">
                        <input type="number" step="1" class="form-control" style="width: 60%!important" name="year" required>
                        <button type="submit" class="btn btn-primary" style="width: 35%!important"><span class="glyphicon glyphicon-floppy-disk" style="width: 15%!important"></span> Buscar</button>
                    </div>
                </div>  
            </form>
        </div>
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title" style="text-align: center !important;">Cotizaciones 
                        <?php echo isset($_POST['year']) ? $_POST['year'] : date('Y'); ?>
                    </h3>
                </div>
                <div class="box-body">
                    <div class="chart" style="display: flex; justify-content: center;">
                        <canvas id="graficoPastel" style="height: 250px; width: 250px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
// Incluye tu archivo PHP que contiene la consulta y datos (asegúrate de la ruta correcta)
include('../Graficaschartjs/pasteldata.php'); 
?>

<script>
    // Datos de estados generados por PHP
    const datosEstado = {
        labels: ["En Proceso", "Pendiente", "Completado", "Cancelado", "Parcialmente"],
        datasets: [{
            data: [
                <?php echo $totalesEnProceso; ?>,
                <?php echo $totalesPendientes; ?>,
                <?php echo $totalesCompletados; ?>,
                <?php echo $totalesCancelados; ?>,
                <?php echo $totalesParciales; ?>
            ],
            backgroundColor: [
                'rgba(255, 206, 86, 0.7)',
                'rgba(54, 162, 235, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(255, 99, 132, 0.7)',
                'rgba(153, 102, 255, 0.7)'
            ],
            borderColor: [
                'rgba(255, 206, 86, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(153, 102, 255, 1)'
            ],
            borderWidth: 1
        }]
    };

    // Configuración del gráfico de pastel
    const ctx = document.getElementById('graficoPastel').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: datosEstado,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });
</script>
</body>
</html>
