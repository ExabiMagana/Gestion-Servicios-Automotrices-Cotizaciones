<div class="form-content">
    <div class="form-div">
        <form action="<?php $url = (isset($_GET['actualizar'])) ? "repuestos.php?update=si" : "repuestos.php"; echo "$url"; ?>" method="post" onsubmit="return validarFormulario()">
            <div>
                <label>
                    <h3>
                        <?php $title = (isset($_GET['actualizar'])) ? "Actualizar" : "Insertar"; echo "$title"; ?>                      
                    </h3>
                </label>
            </div>
            <div>
                <label>Repuesto:</label>
                <input type="text" name="repuesto" id="repuesto" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['nombre_repuesto'] : ""; echo "$value"; ?>">
            </div>
            <div>
                <label>Proveedor:</label>
                <input type="tel" name="proveedor" id="proveedor" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['proveedor'] : ""; echo "$value"; ?>">
            </div>
            <div>
                <label>Precio Unitario:</label>
                <input type="text" name="precio_unitario" id="precio_unitario" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['precio_unitario'] : ""; echo "$value"; ?>">
            </div>
            <div>
                <label>Marca:</label>
                <input type="text" name="marca" id="marca" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['marca'] : ""; echo "$value"; ?>">
            </div>
            <div>
                <label>Repuesto para:</label>
                <input type="text" name="para" id="repuesto_para" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['para'] : ""; echo "$value"; ?>">
            </div>
            <?php 
                if (isset($_GET['actualizar'])) { 
                    echo "<input type='hidden' name='id' value=" . $_GET['id'] . ">";
                }
            ?>
            <div class="btn-form">
                <input type="submit" name="<?php $estado = (isset($_GET['actualizar'])) ? "update" : "enviar"; echo $estado; ?>" value="<?php $value =(isset($_GET['actualizar'])) ? "Modificar" : "Insertar"; echo "$value"; ?>">
                <a href="repuestos.php"><input type="button" name="cancelar" value="Cancelar"></a>
            </div>
            <div id="errores" class="error-messages">
                <div id="error-repuesto" class="error" style="display: none;"></div> 
                <div id="error-precio_unitario" class="error" style="display: none;"></div> 
                <div id="error-marca" class="error" style="display: none;"></div>
            </div>
        </form>
    </div>
</div>
<script>
    function validarFormulario() {
        let esValido = true;

        // Limpiar mensajes de error
        limpiarErrores();

        // Validar Repuesto
        const repuesto = document.getElementById('repuesto');
        const errorRepuesto = document.getElementById('error-repuesto');
        if (repuesto.value.trim() === "") {
            errorRepuesto.textContent = "El nombre del repuesto es requerido.";
            errorRepuesto.style.display = "block"; // Mostrar el mensaje de error
            repuesto.classList.add('input-error');
            repuesto.focus();
            esValido = false;
        } else {
            errorRepuesto.style.display = "none"; // Ocultar si no hay error
        }

        // Validar Precio Unitario (número positivo)
        const precioUnitario = document.getElementById('precio_unitario');
        const errorPrecioUnitario = document.getElementById('error-precio_unitario');
        const precioRegex = /^[0-9]+(\.[0-9]{1,2})?$/;
        if (!precioRegex.test(precioUnitario.value.trim())) {
            errorPrecioUnitario.textContent = "El precio unitario debe ser un valor numérico válido.";
            errorPrecioUnitario.style.display = "block"; // Mostrar el mensaje de error
            precioUnitario.classList.add('input-error');
            if (esValido) precioUnitario.focus();
            esValido = false;
        } else {
            errorPrecioUnitario.style.display = "none"; // Ocultar si no hay error
        }

        // Validar Marca
        const marca = document.getElementById('marca');
        const errorMarca = document.getElementById('error-marca');
        if (marca.value.trim() === "") {
            errorMarca.textContent = "La marca es requerida.";
            errorMarca.style.display = "block"; // Mostrar el mensaje de error
            marca.classList.add('input-error');
            if (esValido) marca.focus();
            esValido = false;
        } else {
            errorMarca.style.display = "none"; // Ocultar si no hay error
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
