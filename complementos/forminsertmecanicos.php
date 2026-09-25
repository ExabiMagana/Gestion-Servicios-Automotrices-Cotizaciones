<div class="form-content">
    <div class="form-div">
        <form action="<?php $url = (isset($_GET['actualizar'])) ? "mecanicos.php?update=si" : "mecanicos.php"; echo "$url"; ?>" method="post" enctype="multipart/form-data" onsubmit="return validarFormulario()">
            <div>
            	<label>
            		<h3>
            			<?php $title = (isset($_GET['actualizar'])) ? "Actualizar" : "Insertar"; echo "$title"; ?>						
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
                <label>Nombre mecánico:</label>
                <input type="text" name="txt-nombre" id='nombreMecanico' value="<?php $value = (isset($_GET['actualizar'])) ? $datos['nombre'] : ""; echo "$value"; ?>">
            </div>
            <div>
                <label>Teléfono:</label>
                <input type="tel" name="telefono" id="telefono" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['telefono'] : ""; echo "$value"; ?>">
            </div>
            <div>
                <label>Especialidad:</label>
                <input type="text" name="especialidad" id="especialidad" value="<?php $value =(isset($_GET['actualizar'])) ? $datos['especialidad'] : "";echo"$value";?>">
            </div>
            <div>
                <label>Fecha de Nacimiento:</label>
                <input type="date" name="nacimiento" id="nacimiento" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['fecha_nacimiento'] : ""; echo "$value"; ?>">
            </div>
            <div>
                <label>Fecha de Contratación:</label>
                <input type="date" name="contrato" id='contrato' value="<?php $value = (isset($_GET['actualizar'])) ? $datos['fecha_contrato'] : ""; echo "$value"; ?>">
            </div>
            
            <?php 

            if (isset($_GET['actualizar'])) { 
                echo "<input type='hidden' name='id' value=" . $_GET['id'] . ">";
                generarSelect_enum($datos['estado']);
            }

            ?>

            <div class="btn-form">
                <input type="submit" name="<?php $estado = (isset($_GET['actualizar'])) ? "update" : "enviar"; echo $estado; ?>" value="<?php $value = (isset($_GET['actualizar'])) ? "Modificar" : "Insertar"; echo "$value"; ?>">
                <a href="mecanicos.php"><input type="button" name="cancelar" value="Cancelar"></a>
            </div>
            <div id="errores" class="error-messages">
                <div id="error-foto" class="error"></div> 
                <div id="error-nombre" class="error"></div> 
                <div id="error-telefono" class="error"></div>
                <div id="error-especialidad" class="error"></div>
                <div id="error-nacimiento" class="error"></div>
                <div id="error-contrato" class="error"></div>
            </div>
        </form>
    </div>
</div>
<script>
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

        // Validar Nombre del Mecánico
        const nombreMecanico = document.getElementById('nombreMecanico');
        const errorNombre = document.getElementById('error-nombre');
        if (nombreMecanico.value.trim() === "") {
            errorNombre.textContent = "El nombre del mecánico es requerido.";
            errorNombre.style.display = "block";
            nombreMecanico.classList.add('input-error');
            nombreMecanico.focus();
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

        // Validar Fecha de Nacimiento
        const nacimiento = document.getElementById('nacimiento');
        const errorNacimiento = document.getElementById('error-nacimiento');
        const fechaActual = new Date();
        const fechaLimiteNacimientoMenor18 = new Date(fechaActual.getFullYear() - 18, fechaActual.getMonth(), fechaActual.getDate());
        const fechaLimiteNacimientoMayor65 = new Date(fechaActual.getFullYear() - 65, fechaActual.getMonth(), fechaActual.getDate());

        if (nacimiento.value === "") {
            errorNacimiento.textContent = "La fecha de nacimiento es requerida.";
            errorNacimiento.style.display = "block";
            nacimiento.classList.add('input-error');
            if (esValido) nacimiento.focus();
            esValido = false;
        } else {
            const fechaNacimiento = new Date(nacimiento.value);
            if (fechaNacimiento > fechaActual) {
                errorNacimiento.textContent = "La fecha de nacimiento no puede ser futura.";
                errorNacimiento.style.display = "block";
                nacimiento.classList.add('input-error');
                if (esValido) nacimiento.focus();
                esValido = false;
            } else if (fechaNacimiento > fechaLimiteNacimientoMenor18) {
                errorNacimiento.textContent = "La edad debe ser al menos 18 años.";
                errorNacimiento.style.display = "block";
                nacimiento.classList.add('input-error');
                if (esValido) nacimiento.focus();
                esValido = false;
            } else if (fechaNacimiento < fechaLimiteNacimientoMayor65) {
                errorNacimiento.textContent = "La edad no puede ser mayor a 65 años.";
                errorNacimiento.style.display = "block";
                nacimiento.classList.add('input-error');
                if (esValido) nacimiento.focus();
                esValido = false;
            }
        }

        // Validar Fecha de Contratación
        const contrato = document.getElementById('contrato');
        const errorContrato = document.getElementById('error-contrato');

        if (contrato.value === "") {
            errorContrato.textContent = "La fecha de contratación es requerida.";
            errorContrato.style.display = "block";
            contrato.classList.add('input-error');
            if (esValido) contrato.focus();
            esValido = false;
        } else {
            const fechaContrato = new Date(contrato.value);
            const fechaNacimientoValor = new Date(nacimiento.value);
            if (fechaContrato < fechaNacimientoValor) {
                errorContrato.textContent = "La fecha de contratación no puede ser menor que la fecha de nacimiento.";
                errorContrato.style.display = "block";
                contrato.classList.add('input-error');
                if (esValido) contrato.focus();
                esValido = false;
            } else {
                const fechaLimiteContratacion = new Date(fechaNacimientoValor.getFullYear() + 16, fechaNacimientoValor.getMonth(), fechaNacimientoValor.getDate());
                if (fechaContrato < fechaLimiteContratacion) {
                    errorContrato.textContent = "Debe haber al menos 16 años de diferencia entre la fecha de nacimiento y la de contratación.";
                    errorContrato.style.display = "block";
                    contrato.classList.add('input-error');
                    if (esValido) contrato.focus();
                    esValido = false;
                }
            }
            if (fechaContrato > fechaActual) {
                errorContrato.textContent = "La fecha de contratación no puede ser futura.";
                errorContrato.style.display = "block";
                contrato.classList.add('input-error');
                if (esValido) contrato.focus();
                esValido = false;
            }
        }

        return esValido;
    }

    function limpiarErrores() {
        const errores = document.querySelectorAll('.error');
        const campos = document.querySelectorAll('.input-error');
        errores.forEach(function(error) {
            error.textContent = "";
            error.style.display = "none"; // Oculta los mensajes de error
        });
        campos.forEach(function(campo) {
            campo.classList.remove('input-error');
        });
    }
</script>
