<div class="form-content">
    <div class="form-div">
        <form action="<?php $url = (isset($_GET['actualizar'])) ? "clientes.php?update=si" : "clientes.php"; echo "$url"; ?>" method="post" onsubmit="return validarFormulario()">
            <div>
                <label>
                    <h3>
                        <?php $title = (isset($_GET['actualizar'])) ? "Actualizar" : "Insertar"; echo "$title"; ?>                      
                    </h3>
                </label>
            </div>
            <div>
                <label>Nombres del cliente:</label>
                <input type="text" name="nombres" id="nombresCliente" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['nombres'] : ""; echo "$value"; ?>">
            </div>
            <div>
                <label>Apellidos del cliente:</label>
                <input type="text" name="apellidos" id="apellidosCliente" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['apellidos'] : ""; echo "$value"; ?>">
            </div>
            <div>
                <label>Teléfono:</label>
                <input type="tel" name="telefono" id="telefono" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['telefono'] : ""; echo "$value"; ?>">
            </div>
            <div>
                <label>Correo:</label>
                <input type="text" name="correo" id="correo" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['correo'] : ""; echo "$value"; ?>">
            </div>
            <div>
                <label>Dirección:</label>
                <input type="text" name="direccion" id="direccion" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['direccion'] : ""; echo "$value"; ?>">
            </div>
            
            <?php 
            if (isset($_GET['actualizar'])) { 
                echo "<input type='hidden' name='id' value=" . $_GET['id'] . ">";
            }
            ?>
            <div class="btn-form">
                <input type="submit" name="<?php $estado = (isset($_GET['actualizar'])) ? "update" : "enviar"; echo $estado; ?>" value="<?php $value = (isset($_GET['actualizar'])) ? "Modificar" : "Insertar"; echo "$value"; ?>">
                <a href="clientes.php"><input type="button" name="cancelar" value="Cancelar"></a>
            </div>
            <div id="errores" class="error-messages">
                <div id="error-nombres" class="error" style="display: none;"></div> 
                <div id="error-apellidos" class="error" style="display: none;"></div> 
                <div id="error-telefono" class="error" style="display: none;"></div>
                <div id="error-correo" class="error" style="display: none;"></div>
            </div>
        </form>
    </div>
</div>

<script>

function capitalizeWords(text) {
    return text.replace(/\b([A-Za-zÁÉÍÓÚÜÑ][a-záéíóúüñ]*)/g, (word) => 
        word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()
    );
}

// Agregar eventos de entrada para capitalizar nombres y apellidos en tiempo real
document.getElementById('nombresCliente').addEventListener('input', function () {
    this.value = capitalizeWords(this.value);
});

document.getElementById('apellidosCliente').addEventListener('input', function () {
    this.value = capitalizeWords(this.value);
});

const telefono = document.getElementById('telefono');
telefono.addEventListener('input', function(event) {
    let value = this.value.replace(/\D/g, ''); // Eliminar caracteres no numéricos
    if (value.length > 4) {
        value = value.substring(0, 4) + '-' + value.substring(4, 8);
    }
    this.value = value.substring(0, 9); // Limitar a 9 caracteres
});

function validarFormulario() {
    let esValido = true;

    // Limpiar mensajes de error
    limpiarErrores();

   // Validar Nombres del Cliente
    const nombresCliente = document.getElementById('nombresCliente');
    const errorNombres = document.getElementById('error-nombres');
    const nombrePattern = /^[A-ZÁÉÍÓÚÜÑ][a-záéíóúüñ]+(?:\s[A-ZÁÉÍÓÚÜÑ][a-záéíóúüñ]+){0,2}$/;

    if (nombresCliente.value.trim() === "") {
        errorNombres.textContent = "Al menos un nombre del cliente es requerido.";
        errorNombres.style.display = "block";
        nombresCliente.classList.add('input-error');
        nombresCliente.focus();
        esValido = false;
    } else if (!nombrePattern.test(nombresCliente.value.trim())) {
        errorNombres.textContent = "Los nombres deben contener solo letras.";
        errorNombres.style.display = "block";
        nombresCliente.classList.add('input-error');
        nombresCliente.focus();
        esValido = false;
    }

    // Validar Apellidos del Cliente
    const apellidosCliente = document.getElementById('apellidosCliente');
    const errorApellidos = document.getElementById('error-apellidos');
    const apellidoPattern = /^[A-ZÁÉÍÓÚÜÑ][a-záéíóúüñ]+(?:\s[A-ZÁÉÍÓÚÜÑ][a-záéíóúüñ]+)?$/;

    if (apellidosCliente.value.trim() === "") {
        errorApellidos.textContent = "Al menos un apellido del cliente es requerido.";
        errorApellidos.style.display = "block";
        apellidosCliente.classList.add('input-error');
        if (esValido) apellidosCliente.focus();
        esValido = false;
    } else if (!apellidoPattern.test(apellidosCliente.value.trim())) {
        errorApellidos.textContent = "Los apellidos deben contener solo letras.";
        errorApellidos.style.display = "block";
        apellidosCliente.classList.add('input-error');
        if (esValido) apellidosCliente.focus();
        esValido = false;
    }

    // Validar Teléfono
    const telefono = document.getElementById('telefono');
    const errorTelefono = document.getElementById('error-telefono');
    const telefonoRegex = /^\d{4}-\d{4}$/;
    if (!telefonoRegex.test(telefono.value.trim())) {
        errorTelefono.textContent = "El teléfono debe contener 8 números.";
        errorTelefono.style.display = "block";
        telefono.classList.add('input-error');
        if (esValido) telefono.focus();
        esValido = false;
    }

    // Validar Correo
    const correo = document.getElementById('correo');
    const errorCorreo = document.getElementById('error-correo');
    const patronCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (correo.value.trim() === "") {
        errorCorreo.textContent = "El correo es requerido.";
        errorCorreo.style.display = "block";
        correo.classList.add('input-error');
        if (esValido) correo.focus();
        esValido = false;
    } else if (!patronCorreo.test(correo.value.trim())) {
        errorCorreo.textContent = "Ingrese un correo válido.";
        errorCorreo.style.display = "block";
        correo.classList.add('input-error');
        if (esValido) correo.focus();
        esValido = false;
    }

    return esValido;
}

function limpiarErrores() {
    const errores = document.querySelectorAll('.error');
    const campos = document.querySelectorAll('.input-error');
    errores.forEach(function(error) {
        error.style.display = "none";
        error.textContent = "";
    });
    campos.forEach(function(campo) {
        campo.classList.remove('input-error');
    });
}
</script>
