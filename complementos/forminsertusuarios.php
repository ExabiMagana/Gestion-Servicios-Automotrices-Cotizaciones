<div class="form-content">
    <div class="form-div">
        <form action="<?php $url = (isset($_GET['actualizar'])) ? "usuarios.php?update=si" : "usuarios.php"; echo "$url"; ?>" method="post" enctype="multipart/form-data" onsubmit="return validarFormulario(event);">
            <div>
                <h3>
                    <?php $title = (isset($_GET['actualizar'])) ? "Actualizar" : "Insertar"; echo "$title"; ?>                      
                </h3>
            </div>
            <div class="file-upload-container">
                <input type="file" name="foto" id="fileInput" accept="image/*" onchange="previewImage(event)" />
                <label for="fileInput" class="file-upload-button">Seleccionar Imagen</label>
            </div>
            <div class="image-preview-container">
                <label for="fileInput"><img id="imagePreview" src="" alt="Vista previa de la imagen" class="image-preview" /></label>
            </div>
            <div>
                <label for="nombreUsuario">Nombre usuario:</label>
                <input type="text" id="nombreUsuario" name="txt-nombre" value="<?php echo isset($datos['nombre_usuario']) ? $datos['nombre_usuario'] : ""; ?>">
            </div>
            <div>
                <label for="correo">Correo:</label>
                <input type="text" id="correo" name="correo" value="<?php echo isset($datos['correo']) ? $datos['correo'] : ""; ?>">
            </div>
            
            <?php 
            if (!isset($_GET['actualizar'])) { 
            ?>
                <div>
                    <label for="contra">Contraseña:</label>
                    <input type="text" id="contra" name="contra">
                </div>
            <?php 
            } else {
                if (isset($_GET['id'])) {
                    echo "<input type='hidden' name='id' value=" . $_GET['id'] . ">";
                }
            }
            if (!isset($_GET['actualizar']) || $_SESSION['id_user'] != $_GET['id']) { 
            ?>
            <div class="select-container">
                <label for="rol-sel">Rol a desempeñar:</label>&nbsp;
                <?php 
                $value = (isset($_GET['actualizar']) && isset($datos['id_rol'])) 
                    ? generarSelect_update($datos['id_rol']) 
                    : generarSelect(); 
                echo "$value"; 
                ?>
                <input type="button" class="rol-button" name="rolmodal" value="Agregar" onclick="modal();">
            </div>
            <?php 
                 $value = (isset($_GET['actualizar']) && isset($datos['estado'])) 
                    ? generarSelect_enum($datos['estado'])  
                    : "";
                echo "$value"; 
            } ?>
            <div class="btn-form">
                <input type="submit" name="<?php $estado = (isset($_GET['actualizar'])) ? "update" : "enviar"; echo $estado; ?>" value="<?php $value = (isset($_GET['actualizar'])) ? "Modificar" : "Insertar"; echo $value; ?>">
                <a href="usuarios.php"><input type="button" name="cancelar" value="Cancelar"></a>
            </div>
            <div id="errores" class="error-messages">
                <div id="error-foto" class="error" style="display: none;"></div> <!-- Mensaje de error para el archivo -->
                <div id="error-nombre" class="error" style="display: none;"></div> <!-- Mensaje de error para el nombre -->
                <div id="error-correo" class="error" style="display: none;"></div> <!-- Mensaje de error para el correo -->
                <div id="error-contra" class="error" style="display: none;"></div> <!-- Mensaje de error para la contraseña -->
                <div id="error-rol" class="error" style="display: none;"></div> <!-- Mensaje de error para el rol -->
            </div>
        </form>
    </div>
</div>

<script>
    function validarFormulario(event) {
        let esValido = true;

        // Limpiar mensajes de error
        limpiarErrores();

        // Validar Nombre de usuario
        const nombreUsuario = document.getElementById('nombreUsuario');
        const errorUsuario = document.getElementById('error-nombre');

        // Expresión regular para validar al menos 6 caracteres, una mayúscula y un número
        const usuarioRegex = /^(?=.*[A-Z])[A-Za-z\d]{6,}$/; 

        if (nombreUsuario.value.trim() === "") {
            errorUsuario.textContent = "El nombre de usuario es requerido."; 
            errorUsuario.style.display = "block"; // Mostrar mensaje de error
            nombreUsuario.classList.add('input-error');
            nombreUsuario.focus();
            esValido = false;
        } 
        // Validar si el nombre de usuario cumple con el patrón
        else if (!usuarioRegex.test(nombreUsuario.value)) {
            errorUsuario.textContent = "El nombre de usuario debe tener al menos 6 caracteres e incluir una mayúscula.";
            errorUsuario.style.display = "block"; // Mostrar mensaje de error
            nombreUsuario.classList.add('input-error');
            if (esValido) nombreUsuario.focus(); 
            esValido = false;
        } else {
            errorUsuario.style.display = "none"; // Ocultar si no hay error
        }

        // Validar Correo
        const correo = document.getElementById('correo');
        const errorCorreo = document.getElementById('error-correo');
        const patronCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (correo.value.trim() === "") {
            errorCorreo.textContent = "El correo es requerido.";
            errorCorreo.style.display = "block"; // Mostrar mensaje de error
            correo.classList.add('input-error');
            if (esValido) correo.focus();
            esValido = false;
        } else if (!patronCorreo.test(correo.value.trim())) {
            errorCorreo.textContent = "Ingrese un correo válido.";
            errorCorreo.style.display = "block"; // Mostrar mensaje de error
            correo.classList.add('input-error');
            if (esValido) correo.focus();
            esValido = false;
        } else {
            errorCorreo.style.display = "none"; // Ocultar si no hay error
        }

        // Validar Contraseña (solo para nuevos usuarios)
        const actualizar = "<?php echo isset($_GET['actualizar']) ? 'true' : 'false'; ?>";
        if (actualizar === 'false') {
            const contra = document.getElementById('contra');
            const errorContra = document.getElementById('error-contra');

            // Expresión regular para validar al menos 8 caracteres, incluyendo al menos una letra
            const contraRegex = /^(?=.*[A-Za-z])[A-Za-z\d]{8,}$/; 

            if (contra.value.trim() === "") {
                errorContra.textContent = "La contraseña es requerida.";
                errorContra.style.display = "block"; // Mostrar mensaje de error
                contra.classList.add('input-error');
                if (esValido) contra.focus();
                esValido = false;
            } 
            // Validar si la contraseña cumple con el patrón
            else if (!contraRegex.test(contra.value)) {
                errorContra.textContent = "La contraseña debe tener al menos 8 caracteres e incluir al menos una letra.";
                errorContra.style.display = "block"; // Mostrar mensaje de error
                contra.classList.add('input-error');
                if (esValido) contra.focus();
                esValido = false;
            } else {
                errorContra.style.display = "none"; // Ocultar si no hay error
            }
        }

        // Validar archivo de imagen
        const fileInput = document.getElementById('fileInput');
        const errorFoto = document.getElementById('error-foto');

        // No es obligatorio seleccionar una imagen
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
            
            // Validar el tipo de archivo solo si se ha seleccionado una imagen
            if (!tiposPermitidos.includes(file.type)) {
                errorFoto.textContent = "Solo se permiten archivos JPG, PNG o GIF.";
                errorFoto.style.display = "block"; // Mostrar mensaje de error
                fileInput.classList.add('input-error');
                if (esValido) fileInput.focus();
                esValido = false;
            } else {
                errorFoto.style.display = "none"; // Ocultar si no hay error
                fileInput.classList.remove('input-error'); // Quitar clase de error si es válido
            }
        }

        // Validar Rol
        const rolSelect = document.querySelector('select[name="rol"]');
        const spanSelect = document.querySelector('.select2');
        const errorRol = document.getElementById('error-rol');
        if (rolSelect && rolSelect.value == "") {
            rolSelect.value = "Seleccione un rol.";
            errorRol.textContent = "Debe elegir un rol para el usuario";
            errorRol.style.display = "block"; // Mostrar mensaje de error
            spanSelect.classList.add('select2-error');
            if (esValido) rolSelect.focus();
            esValido = false;
        } else {
            errorRol.style.display = "none"; // Ocultar si no hay error
        }

        return esValido; // Devuelve el resultado de la validación
    }

    // Función para limpiar los mensajes de error
    function limpiarErrores() {
        const errores = document.querySelectorAll('.error');
        const campos = document.querySelectorAll('.input-error');
        errores.forEach(function(error) {
            error.textContent = "";
            error.style.display = "none"; // Ocultar mensaje de error
        });
        campos.forEach(function(campo) {
            campo.classList.remove('input-error');
        });
    }
</script>
