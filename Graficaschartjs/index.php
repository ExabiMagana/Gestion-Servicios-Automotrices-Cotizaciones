<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Generar gráficas de barras con PHP usando Chart.js</title>
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
            <form method="POST">
                <div class="form-group" style="width: 80% !important; margin: 0 auto !important">
                    <label>Año:</label>
                    <div style="display: flex !important; justify-content: space-between!important;  width: 100%!important;">
                        <input type="number" step="1" class="form-control" style="width: 80%!important" name="year" required>
                        <button type="submit" class="btn btn-primary" style="width: 15%!important"><span style="width: 15%!important" class="glyphicon glyphicon-floppy-disk"></span> Buscar</button>
                    </div>
                </div>  
            </form>
        </div>
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title" style="text-align: center !important;">Apróximado de Ventas 
                    <?php 
                        if (isset($_POST['year'])) {
                             echo $_POST['year'];
                        }else{
                            echo date('Y');
                        } 
                    ?>
                    </h3>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <canvas id="barChart" style="height:290px"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('../Graficaschartjs/data.php'); ?>
<script>
 $(function () {
    var barChartData = {
        labels  : ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        datasets: [
            {
                label               : 'En Proceso',
                backgroundColor     : 'rgba(255, 206, 86, 0.7)',
                borderColor         : 'rgba(255, 206, 86, 1)',
                pointRadius         : false,
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(41,19,211,1)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(41,19,211,1)',
                data                : [<?php echo $totalesEnProceso; ?>]
            },
            {
                label               : 'Pendientes',
                backgroundColor     : 'rgba(54, 162, 235, 0.7)',
                borderColor         : 'rgba(54, 162, 235, 1)',
                pointRadius         : false,
                pointColor          : 'rgba(66, 66, 66, 1)',
                pointStrokeColor    : '#c1c7d1',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(66, 66, 66,1)',
                data                : [<?php echo $totalesPendientes; ?>]
            },
            {
                label               : 'Completadas',
                backgroundColor     : 'rgba(75, 192, 192, 0.7)',
                borderColor         : 'rgba(75, 192, 192, 1)',
                pointRadius         : false,
                pointColor          : 'rgba(19, 183, 16, 1)',
                pointStrokeColor    : '#c1c7d1',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(19, 183, 16, 1)',
                data                : [<?php echo $totalesCompletados; ?>]
            },
            {
                label               : 'Canceladas',
                backgroundColor     : 'rgba(255, 99, 132, 0.7)',
                borderColor         : 'rgba(255, 99, 132, 1)',
                pointRadius         : false,
                pointColor          : 'rgba(226, 38, 38,1)',
                pointStrokeColor    : '#c1c7d1',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(226, 38, 38,1)',
                data                : [<?php echo $totalesCancelados; ?>]
            },
            {
                label               : 'Parciales',
                backgroundColor     : 'rgba(153, 102, 255, 0.7)',
                borderColor         : 'rgba(153, 102, 255, 1)',
                pointRadius         : false,
                pointColor          : 'rgba(255, 229, 0, 0.9)',
                pointStrokeColor    : '#c1c7d1',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(255, 229, 0, 0.9)',
                data                : [<?php echo $totalesParciales; ?>]
            }
        ]
    }

    var barChartCanvas = $('#barChart').get(0).getContext('2d');
    var barChartOptions = {
        responsive              : true,
        maintainAspectRatio     : false,
        datasetFill             : false,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }

    new Chart(barChartCanvas, {
        type: 'bar',
        data: barChartData,
        options: barChartOptions
    });
});

</script>
</body>
</html>