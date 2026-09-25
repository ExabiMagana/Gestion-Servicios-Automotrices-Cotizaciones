<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../estilos/stylereportes.css">
    <title>Generar Reporte</title>
</head>
<body>
<?php
include '../database/reportesConsultas.php'; 
?>
<div class="main-container">
    <!-- Formulario a la izquierda -->
    <div class="form-container">
        <form action="../fpdf/reportes.php" target="_blank" method="post" id="reporte-form">
            <input type="hidden" name="generar">
            <h3>Generar Reporte</h3>
            
            <!-- Selección del tipo de reporte -->
            <label for="tipo-reporte">Seleccionar tipo de reporte:</label>
            <select id="tipo-reporte" name="tipo-reporte" onchange="filtrarOpcionesFecha()">
                <option value="reporte1" selected>Reporte 1</option> <!-- Opción seleccionada por defecto -->
                <option value="reporte2">Reporte 2</option>
                <option value="reporte3">Reporte 3</option>
                <option value="reporte4">Reporte 4</option>
                <option value="reporte5">Reporte 5</option>
            </select>

            <!-- Opciones de Fecha -->
            <label for="opcion-fecha">Seleccionar intervalo de fechas:</label>
            <select id="opcion-fecha" name="opcionFecha" onchange="mostrarOpcionesFecha()">
                <option value="general">General</option>
                <option value="anio-actual">Año Actual</option>
                <option value="mes-actual">Mes Actual</option>
                <option value="anio-especifico">Año Específico</option>
                <option value="mes-especifico">Mes Específico</option>
                <option value="rango-fechas">Rango de Fechas</option>
            </select>

            <!-- Campos Condicionales para Año/Mes -->
            <div id="campo-anio" style="display:none;">
                <label for="anio">Seleccionar Año:</label>
                <input type="number" id="anio" name="anio" min="2000" max="2100" placeholder="Ej: 2024">
            </div>

            <div id="campo-mes" style="display:none;">
                <label for="mes">Seleccionar Mes:</label>
                <input type="number" id="mes" name="mes" min="1" max="12" placeholder="Ej: 10">
            </div>

            <!-- Campo para Rango de Fechas -->
            <div id="rango-fechas" style="display:none;">
                <label for="fecha-inicio">Fecha de Inicio:</label>
                <input type="date" id="fecha-inicio" name="fechaInicio">

                <label for="fecha-fin">Fecha de Fin:</label>
                <input type="date" id="fecha-fin" name="fechaFin">
            </div>

            <!-- Botón Generar PDF -->
            <button type="submit" id="generar-reporte-btn" name="generar"><i class="fa fa-file-pdf"></i> Generar PDF</button>
        </form>
    </div>
</div>

<script type="text/javascript">
    // Función para mostrar y ocultar opciones de fecha según el tipo de reporte seleccionado
    function filtrarOpcionesFecha() {
        var tipoReporte = document.getElementById('tipo-reporte').value;
        var opcionFecha = document.getElementById('opcion-fecha');
        
        // Restaurar todas las opciones antes de aplicar filtros
        opcionFecha.innerHTML = `
            <option value="general">General</option>
            <option value="anio-actual">Año Actual</option>
            <option value="mes-actual">Mes Actual</option>
            <option value="anio-especifico">Año Específico</option>
            <option value="mes-especifico">Mes Específico</option>
            <option value="rango-fechas">Rango de Fechas</option>
        `;

        // Si se selecciona Reporte 1, quitar "Mes Actual" y "Mes Específico"
        if (tipoReporte === 'reporte1') {
            for (var i = 0; i < opcionFecha.options.length; i++) {
                if (opcionFecha.options[i].value === 'mes-actual' || opcionFecha.options[i].value === 'mes-especifico') {
                    opcionFecha.options[i].style.display = 'none';
                }
            }
        }
    }

    // Función para mostrar campos específicos de fecha según la opción seleccionada
    function mostrarOpcionesFecha() {
        var opcionFecha = document.getElementById('opcion-fecha').value;
        
        // Ocultar todos los campos al cambiar la selección
        document.getElementById('campo-anio').style.display = 'none';
        document.getElementById('campo-mes').style.display = 'none';
        document.getElementById('rango-fechas').style.display = 'none';
        
        // Mostrar el campo según la opción elegida
        if (opcionFecha === 'anio-especifico') {
            document.getElementById('campo-anio').style.display = 'block';
        } else if (opcionFecha === 'mes-especifico') {
            document.getElementById('campo-mes').style.display = 'block';
        } else if (opcionFecha === 'rango-fechas') {
            document.getElementById('rango-fechas').style.display = 'block';
        }
    }

    // Inicializar filtro de opciones de fecha en carga de página
    window.onload = filtrarOpcionesFecha;
</script>
</body>
</html>
