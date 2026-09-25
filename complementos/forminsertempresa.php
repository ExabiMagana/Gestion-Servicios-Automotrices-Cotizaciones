<div class="form-content">
    <div class="form-div">
        <form id="empresaForm" action="empresa.php?update=si" method="post" enctype="multipart/form-data" onsubmit="return validarFormulario()">
            <div>
                <label>
                    <h3>
                        Editar Datos                        
                    </h3>
                </label>
            </div>
            <div class="file-upload-container">
                <input type="file" name="foto" id="fileInput" accept="image/*" onchange="previewImage(event)" />
                <label for="fileInput" class="file-upload-button">Seleccionar Imagen</label>
            </div>
            <div class="image-preview-container">
                <label for="fileInput"><img id="imagePreview" src="" alt="Vista previa de la imagen" class="image-preview" /></label>
            </div>
            <div>
                <label>Nombres de la Empresa:</label>
                <input type="text" name="nombre" id="nombreEmpresa" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['nombre'] : "" ; echo $value;?>">
            </div>
            <div>
                <label>Teléfono Celular:</label>
                <input type="text" name="celular" id="telefonoCelular" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['celular'] : "" ; echo $value;?>">
            </div>
            <div>
                <label>Teléfono Fijo:</label>
                <input type="text" name="fijo" id="telefonoFijo" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['fijo'] : "" ; echo $value;?>">
            </div>
            <div>
                <label>Dirección:</label>
                <input type="text" name="direccion" id="direccionEmpresa" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['direccion'] : "" ; echo $value;?>">
            </div>
            <div>
                <label>Correo:</label>
                <input type="text" name="correo" id="correoEmpresa" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['correo'] : "" ; echo $value;?>">
            </div>
            <div>
                <label>Eslogan:</label>
                <input type="text" name="slogan" id="sloganEmpresa" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['slogan'] : "" ; echo $value;?>">
            </div>
            <div class="btn-form">
                <input type="submit" name="update" value="Modificar">
                <a href="empresa.php?mostrar=si"><input type="button" name="cancelar" value="Cancelar"></a>
            </div>
            <div id="errores" class="error-messages">
                <div id="error-nombre" class="error"></div>
                <div id="error-celular" class="error"></div>
                <div id="error-fijo" class="error"></div>
                <div id="error-correo" class="error"></div>
            </div>
        </form>
    </div>
</div>

<script>
    const telefonoCelular = document.getElementById('telefonoCelular');
    telefonoCelular.addEventListener('input', function(event) {
        let value = this.value.replace(/\D/g, ''); // Eliminar caracteres no numéricos
        if (value.length > 4) {
            value = value.substring(0, 4) + '-' + value.substring(4, 8);
        }
        this.value = value.substring(0, 9); // Limitar a 9 caracteres
    });

    // Formatear el número de teléfono fijo
    const telefonoFijo = document.getElementById('telefonoFijo');
    telefonoFijo.addEventListener('input', function(event) {
        let value = this.value.replace(/\D/g, ''); // Eliminar caracteres no numéricos
        if (value.length > 4) {
            value = value.substring(0, 4) + '-' + value.substring(4, 8);
        }
        this.value = value.substring(0, 9); // Limitar a 9 caracteres
    });

    function validarFormulario() {
        let esValido = true;

        // Limpiar mensajes de error y ocultarlos
        document.querySelectorAll('.error').forEach(error => {
            error.textContent = "";
            error.style.display = "none"; // Ocultar el mensaje de error por defecto
        });

        // Limpiar clases de error de todos los inputs
        document.querySelectorAll('input[type="text"]').forEach(input => {
            input.classList.remove('input-error');
        });

        // Validar Nombre de la Empresa
        const nombreEmpresa = document.getElementById('nombreEmpresa');
        if (nombreEmpresa.value.trim() === "") {
            const errorNombre = document.getElementById('error-nombre');
            errorNombre.textContent = "El nombre de la empresa es requerido.";
            errorNombre.style.display = "block"; // Mostrar el mensaje de error
            nombreEmpresa.classList.add('input-error'); // Añadir clase de error
            esValido = false;
        }

        // Validar Teléfono Celular
        const telefonoRegex = /^\d{4}-\d{4}$/;
        if (!telefonoRegex.test(telefonoCelular.value.trim())) {
            const errorCelular = document.getElementById('error-celular');
            errorCelular.textContent = "El teléfono celular debe tener el formato 0000-0000.";
            errorCelular.style.display = "block";
            telefonoCelular.classList.add('input-error'); // Añadir clase de error
            esValido = false;
        }

        // Validar Teléfono Fijo (opcional)
        if (telefonoFijo.value.trim() !== "" && !telefonoRegex.test(telefonoFijo.value.trim())) {
            const errorFijo = document.getElementById('error-fijo');
            errorFijo.textContent = "El teléfono fijo debe tener el formato 0000-0000.";
            errorFijo.style.display = "block";
            telefonoFijo.classList.add('input-error'); // Añadir clase de error
            esValido = false;
        }

        // Validar Correo Electrónico
        const correoEmpresa = document.getElementById('correoEmpresa');
        const correoRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (!correoRegex.test(correoEmpresa.value.trim())) {
            const errorCorreo = document.getElementById('error-correo');
            errorCorreo.textContent = "El correo debe tener un formato válido.";
            errorCorreo.style.display = "block";
            correoEmpresa.classList.add('input-error'); // Añadir clase de error
            esValido = false;
        }

        return esValido;
    }
</script>
