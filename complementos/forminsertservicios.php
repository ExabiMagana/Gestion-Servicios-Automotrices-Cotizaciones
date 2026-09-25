<div class="form-content">
    <div class="form-div">
        <form action="<?php $url = (isset($_GET['actualizar'])) ? "servicios.php?update=si" : "servicios.php"; echo "$url"; ?>" method="post" onsubmit="return validarFormulario()">
            <div>
                <label>
                    <h3>
                        <?php $title = (isset($_GET['actualizar'])) ? "Actualizar" : "Insertar"; echo "$title"; ?>                      
                    </h3>
                </label>
            </div>
            <div>
                <label>Nombres del servicio:</label>
                <input type="text" name="servicio" id="servicio" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['nombre_servicio'] : ""; echo "$value"; ?>">
            </div>
            <div>
                <label>Descripción:</label>
                <input type="text" name="descripcion" id="descripcion" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['descripcion'] : ""; echo "$value"; ?>">
            </div>
            <div>
                <label>Precio:</label>
                <input type="text" name="precio" id="precio" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['precio'] : ""; echo "$value"; ?>">
            </div>
            <?php 
            if (isset($_GET['actualizar'])) { 
                echo "<input type='hidden' name='id' value=" . $_GET['id'] . ">";
            }
            ?>
            <div class="btn-form">
                <input type="submit" name="<?php $estado = (isset($_GET['actualizar'])) ? "update" : "enviar"; echo $estado; ?>" value="<?php $value = (isset($_GET['actualizar'])) ? "Modificar" : "Insertar"; echo "$value"; ?>">
                <a href="servicios.php"><input type="button" name="cancelar" value="Cancelar"></a>
            </div>
            <div id="errores" class="error-messages">
                <div id="error-servicio" class="error" style="display: none;"></div> 
                <div id="error-precio" class="error" style="display: none;"></div>
            </div>
        </form>
    </div>
</div>

<script>
    function validarFormulario() {
        let esValido = true;

        // Limpiar mensajes de error
        limpiarErrores();

        // Validar Nombres del servicio
        const servicio = document.getElementById('servicio');
        const errorServicio = document.getElementById('error-servicio');
        if (servicio.value.trim() === "") {
            errorServicio.textContent = "El nombre del servicio es requerido.";
            errorServicio.style.display = "block"; // Mostrar el mensaje de error
            servicio.classList.add('input-error');
            servicio.focus();
            esValido = false;
        } else {
            errorServicio.style.display = "none"; // Ocultar si no hay error
        }

        // Validar Precio (número positivo)
        const precio = document.getElementById('precio');
        const errorPrecio = document.getElementById('error-precio');
        const precioRegex = /^[0-9]+(\.[0-9]{1,2})?$/;
        if (!precioRegex.test(precio.value.trim())) {
            errorPrecio.textContent = "El precio debe ser un valor numérico válido.";
            errorPrecio.style.display = "block"; // Mostrar el mensaje de error
            precio.classList.add('input-error');
            if (esValido) precio.focus();
            esValido = false;
        } else {
            errorPrecio.style.display = "none"; // Ocultar si no hay error
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
