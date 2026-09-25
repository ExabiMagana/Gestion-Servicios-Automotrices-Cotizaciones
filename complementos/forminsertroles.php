<div class="form-content">
    <div class="form-div">
        <form action="<?php $url = (isset($_GET['actualizar'])) ? "roles.php?update=si" : "roles.php"; echo "$url"; ?>" method="post" enctype="multipart/form-data" onsubmit="return validarFormulario()">
            <div>
                <label>
                    <h3>
                        <?php $title = (isset($_GET['actualizar'])) ? "Actualizar" : "Insertar"; echo "$title"; ?>                      
                    </h3>
                </label>
                <?php 
                    if ($_SESSION['nivel'] == 5) {
                        echo "<input type='checkbox' name='abrir-opciones-avanzadas' id='abrir-opciones-avanzadas' style='display: none;'>
                        <label for='abrir-opciones-avanzadas'>Opciones Avanzadas&emsp;<iconify-icon icon='oui:app-advanced-settings'></iconify-icon></label>";
                    }
                ?>
            </div>
            <div>
                <label for="nombreRol">Nombre Rol:</label>
                <input type="text" name="txt-nombre" id="nombreRol" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['nombre_rol'] : ""; echo "$value"; ?>">
            </div>
            
            <?php 
                if (isset($_GET['actualizar'])) { 
                    echo "<input type='hidden' name='id' value=" . $_GET['id'] . ">";
                    if ($datos['nivel'] < 5) {
                         generarSelect_enum($datos['estado'], obtenerValoresEnum(conectarse(), 'roles', 'estado'));
                         generarSelect_nivel($datos['nivel'], obtenerValoresEnum(conectarse(), 'roles', 'nivel'));
                    }
                } else {
                    generarSelect_nivelInsert();
                }
            ?>
            <div class="btn-form">
                <input type="submit" name="<?php $estado = (isset($_GET['actualizar'])) ? "update" : "enviar"; echo $estado; ?>" value="<?php $value = (isset($_GET['actualizar'])) ? "Modificar" : "Insertar"; echo "$value"; ?>">
                <a href="roles.php"><input type="button" name="cancelar" value="Cancelar"></a>
            </div>
            <div id="errores" class="error-messages">
                <div id="error-nombre" class="error" style="display: none;"></div> <!-- Mensaje de error para el nombre -->
            </div>
           <div id="opciones-avanzadas" style="display: none;">
                <p><strong>Definir Acceso de Páginas</strong></p>
                <input type="checkbox" id="usuarios" name="estadousuario" value="1" <?php echo (isset($datos['usuarios']) && $datos['usuarios'] == 1) ? 'checked' : ''; ?>>
                <label for="usuarios">Usuarios</label>

                <input type="checkbox" id="mecanicos" name="estadomecanic" value="1" <?php echo (isset($datos['mecanicos']) && $datos['mecanicos'] == 1) ? 'checked' : ''; ?>>
                <label for="mecanicos">Mecánicos</label>

                <input type="checkbox" id="servicios" name="estadoservice" value="1" <?php echo (isset($datos['servicios']) && $datos['servicios'] == 1) ? 'checked' : ''; ?>>
                <label for="servicios">Servicios</label>

                <input type="checkbox" id="cotizaciones" name="estadocotices" value="1" <?php echo (isset($datos['cotizaciones']) && $datos['cotizaciones'] == 1) ? 'checked' : ''; ?>>
                <label for="cotizaciones">Cotizaciones</label>

                <input type="checkbox" id="repuestos" name="estadorepuest" value="1" <?php echo (isset($datos['repuestos']) && $datos['repuestos'] == 1) ? 'checked' : ''; ?>>
                <label for="repuestos">Repuestos</label>

                <input type="checkbox" id="reportes" name="estadoreporte" value="1" <?php echo (isset($datos['reportes']) && $datos['reportes'] == 1) ? 'checked' : ''; ?>>
                <label for="reportes">Reportes</label>

                <input type="checkbox" id="clientes" name="estadocliente" value="1" <?php echo (isset($datos['clientes']) && $datos['clientes'] == 1) ? 'checked' : ''; ?>>
                <label for="clientes">Clientes</label>

                <input type="checkbox" id="vehiculos" name="estadovehicle" value="1" <?php echo (isset($datos['vehiculos']) && $datos['vehiculos'] == 1) ? 'checked' : ''; ?>>
                <label for="vehiculos">Vehículos</label>
            </div>
        </form>
    </div>
</div>

<script>
    // Mostrar u ocultar las opciones avanzadas según el estado del checkbox
    document.getElementById('abrir-opciones-avanzadas').addEventListener('change', function() {
        const opcionesAvanzadas = document.getElementById('opciones-avanzadas');
        opcionesAvanzadas.style.display = this.checked ? 'flex' : 'none';
    });

    function validarFormulario() {
        let esValido = true;

        // Limpiar mensajes de error
        limpiarErrores();

        // Validar Nombre de Rol
        const nombreRol = document.getElementById('nombreRol');
        const errorNombre = document.getElementById('error-nombre');
        if (nombreRol.value.trim() === "") {
            errorNombre.textContent = "El nombre del rol es requerido.";
            errorNombre.style.display = "block"; // Mostrar el mensaje de error
            nombreRol.classList.add('input-error');
            nombreRol.focus();
            esValido = false;
        } else {
            errorNombre.style.display = "none"; // Ocultar si no hay error
        }

        return esValido;
    }

    // Función para limpiar los mensajes de error
    function limpiarErrores() {
        const errores = document.querySelectorAll('.error');
        const campos = document.querySelectorAll('.input-error');
        errores.forEach(function(error) {
            error.textContent = "";
            error.style.display = "none"; // Ocultar todos los mensajes de error
        });
        campos.forEach(function(campo) {
            campo.classList.remove('input-error');
        });
    }
</script>
