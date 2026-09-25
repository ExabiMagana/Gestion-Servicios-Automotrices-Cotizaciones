<?php
session_start();
$datos = $_SESSION['cotizacion_detalles'];

if (!$datos) {
    echo "No se encontraron detalles de la cotización.";
    exit();
}

$cotizacion = $datos['cotizacion'];
$repuestos = $datos['repuestos'];
$servicios = $datos['servicios'];
$volver = $_SESSION['previo']."?opcion=mostrar"; // Ruta para el enlace de "volver"
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Cotización</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            color: #333;
            margin: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            position: relative;
        }
        h3 {
            color: #0073e6;
        }
        p, label {
            margin: 8px 0;
        }
        .button, .icon-close, .volver-btn {
            display: inline-block;
            padding: 10px 15px;
            color: white;
            background-color: #0073e6;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 15px;
            text-align: center;
        }
        .icon-close {
            position: absolute;
            top: 10px;
            right: 10px;
            color: #dc3545;
            background-color: transparent;
            padding: 5px 10px;
        }
        a{
            text-decoration: none;
        }
        .volver-btn {
            background-color: #0073e6;
            position: absolute;
            bottom: 10px;
            left: 10px;
        }
        .hidden {
            display: none;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            max-width: 300px;
        }
        .modal-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .modal-buttons button {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-confirm {
            background-color: #28a745;
            color: white;
        }
        .btn-cancel {
            background-color: #dc3545;
            color: white;
        }
        select {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            width: 100%;
        }
        .button-group {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<!-- Iniciar el formulario -->
<form id="formCotizacion" action="cotizaciones.php?opcion=mostrar" method="POST">
    <input type="hidden" name="guardar" value="guardar_cambios">
    <div class="container">
        <!-- Icono de cerrar -->
        <a href="<?php echo $volver; ?>" class="icon-close"><iconify-icon icon="zondicons:close-outline" width="1.5rem" height="1.5rem"></iconify-icon></a>

        <!-- Información general de la cotización -->
        <p><strong>Cotización:</strong> <?php echo htmlspecialchars($cotizacion['id_cotizacion']); ?></p>
        <p><strong>Cliente:</strong> <?php echo htmlspecialchars($cotizacion['cliente']); ?></p>
        <p><strong>Vehículo:</strong> <?php echo htmlspecialchars($cotizacion['vehiculo']); ?></p>

        <!-- Estado de la Cotización -->
        <p><strong>Estado de la Cotización:</strong>
            <select id="estadoCotizacion" name="estado_cotizacion" onchange="actualizarEstados()" disabled>
                <option value="pendiente" <?php if ($cotizacion['estado'] == 'pendiente') echo 'selected'; ?>>Pendiente</option>
                <option value="en_proceso" <?php if ($cotizacion['estado'] == 'en proceso') echo 'selected'; ?>>En Proceso</option>
                <option value="parcialmente" <?php if ($cotizacion['estado'] == 'parcialmente') echo 'selected'; ?>>Parcialmente</option>
                <option value="completado" <?php if ($cotizacion['estado'] == 'completado') echo 'selected'; ?>>Completado</option>
                <option value="cancelado" <?php if ($cotizacion['estado'] == 'cancelado') echo 'selected'; ?>>Cancelado</option>
            </select>
        </p>

        <!-- Repuestos y Servicios -->
        <?php if (!empty($repuestos)): ?>
            <h3>Repuestos</h3>
            <?php foreach ($repuestos as $repuesto): ?>
                <p>
                    <strong><?php echo htmlspecialchars($repuesto['nombre_repuesto']); ?>:</strong>
                    <select class="estado-select" name="estado_repuesto[<?php echo $repuesto['id_repuesto']; ?>]" disabled>
                        <option value="pendiente" <?php if ($repuesto['estado_repuesto'] == 'pendiente') echo 'selected'; ?>>Pendiente</option>
                        <option value="completado" <?php if ($repuesto['estado_repuesto'] == 'aprobado') echo 'selected'; ?>>Aprobado</option>
                        <option value="cancelado" <?php if ($repuesto['estado_repuesto'] == 'cancelado') echo 'selected'; ?>>Cancelado</option>
                    </select>
                </p>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (!empty($servicios)): ?>
            <h3>Servicios</h3>
            <?php foreach ($servicios as $servicio): ?>
                <p>
                    <strong><?php echo htmlspecialchars($servicio['nombre_servicio']); ?>:</strong>
                    <select class="estado-select" name="estado_servicio[<?php echo $servicio['id_servicio']; ?>]" disabled>
                        <option value="pendiente" <?php if ($servicio['estado_servicio'] == 'pendiente') echo 'selected'; ?>>Pendiente</option>
                        <option value="completado" <?php if ($servicio['estado_servicio'] == 'aprobado') echo 'selected'; ?>>Aprobado</option>
                        <option value="cancelado" <?php if ($servicio['estado_servicio'] == 'cancelado') echo 'selected'; ?>>Cancelado</option>
                    </select>
                </p>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Botones de acción -->
        <div class="button-group">
            <button type="button" class="button" id="modificarBtn" onclick="alternarModificacion()">Modificar</button>
            <button type="button" class="button hidden" id="guardarBtn" onclick="mostrarConfirmacion()">Guardar Cambios</button>
        </div>

        <!-- Botón de volver en la parte inferior izquierda -->
        <a href="<?php echo $volver; ?>" class="volver-btn">Volver</a>
    </div>
</form>

<!-- Modal de confirmación -->
<div id="modalConfirmacion" class="modal">
    <div class="modal-content">
        <p>¿Estás seguro de guardar los cambios?</p>
        <div class="modal-buttons">
            <button class="btn-confirm" onclick="confirmarEnvio()">Sí</button>
            <button class="btn-cancel" onclick="cerrarModal(); reiniciarSeleccionEstado()">No</button>
        </div>
    </div>
</div>


<script>
    const originalValues = {
        estadoCotizacion: '<?php echo htmlspecialchars($cotizacion['estado']); ?>',
        repuestos: <?php echo json_encode(array_column($repuestos, 'estado_repuesto', 'id_repuesto')); ?>,
        servicios: <?php echo json_encode(array_column($servicios, 'estado_servicio', 'id_servicio')); ?>
    };

    function alternarModificacion() {
        const isEditMode = document.getElementById('modificarBtn').innerText === 'Modificar';
        
        document.getElementById('estadoCotizacion').disabled = !isEditMode;
        document.querySelectorAll('.estado-select').forEach(select => {
            select.disabled = !isEditMode;
        });
        document.getElementById('guardarBtn').classList.toggle('hidden', !isEditMode);

        // Cambiar el texto del botón según el estado actual
        document.getElementById('modificarBtn').innerText = isEditMode ? 'Cancelar' : 'Modificar';
        
        if (!isEditMode) reiniciarSeleccionEstado();
    }

    function reiniciarSeleccionEstado() {
        document.getElementById('estadoCotizacion').value = originalValues.estadoCotizacion;
        document.querySelectorAll('.estado-select').forEach(select => {
            const id = select.name.match(/\[(\d+)\]/)[1];
            select.value = originalValues.repuestos[id] || originalValues.servicios[id];
        });
    }

    function actualizarEstados() {
        const estadoCotizacion = document.getElementById('estadoCotizacion').value;
        
        document.querySelectorAll('.estado-select').forEach(select => {
            select.value = estadoCotizacion;
        });
    }

    function mostrarConfirmacion() {
        document.getElementById('modalConfirmacion').style.display = 'flex';
    }

    function confirmarEnvio() {
        document.getElementById('formCotizacion').submit();
    }

    function cerrarModal() {
        document.getElementById('modalConfirmacion').style.display = 'none';
    }
</script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
</body>
</html>
