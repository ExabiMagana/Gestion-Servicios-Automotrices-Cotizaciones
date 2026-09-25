<?php 
include_once "../database/conexion.php";
include_once "../database/extrasConsultas.php";
include_once "../sesiones/sesionstart.php";
$datos=buscadorUsuario();

if (isset($_POST['guardar'])) {
    actualizarPerfil();
}

$volver=$_SESSION['previo'];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <link rel="stylesheet" type="text/css" href="../estilos/styleperfil.css">
    <script>
        // Función para activar la edición de los campos
        function habilitarEdicion() {
            // Cambiar de span a input
            document.getElementById("nombre-text").style.display = "none"; // Ocultar el texto del nombre
            document.getElementById("nombre").removeAttribute("readonly"); // Habilitar el input
            document.getElementById("nombre").style.display = "block"; // Mostrar el input del nombre

            document.getElementById("correo-text").style.display = "none"; // Ocultar el texto del correo
            document.getElementById("correo").removeAttribute("readonly"); // Habilitar el input
            document.getElementById("correo").style.display = "block"; // Mostrar el input del correo

            document.getElementById("nuevo-password").style.display = "block";
            document.getElementById("upload-foto").style.display = "block"; // Mostrar input para cargar imagen
            document.getElementById("foto-perfil").style.display = "none"; // Ocultar la imagen de perfil en modo de edición

            document.getElementById("btn-modificar").style.display = "none"; // Ocultar botón Modificar
            document.getElementById("btn-guardar").style.display = "inline-block"; // Mostrar botón Guardar
            document.getElementById("btn-cancelar").style.display = "inline-block"; // Mostrar botón Cancelar Edición
        }

        // Función para cancelar la edición
        function cancelarEdicion() {
            // Cambiar de input a span
            document.getElementById("nombre-text").style.display = "inline"; // Mostrar el texto del nombre
            document.getElementById("nombre").setAttribute("readonly", "readonly"); // Desactivar el input
            document.getElementById("nombre").style.display = "none"; // Ocultar el input del nombre

            document.getElementById("correo-text").style.display = "inline"; // Mostrar el texto del correo
            document.getElementById("correo").setAttribute("readonly", "readonly"); // Desactivar el input
            document.getElementById("correo").style.display = "none"; // Ocultar el input del correo

            document.getElementById("nuevo-password").style.display = "none"; // Ocultar campo de nueva contraseña
            document.getElementById("upload-foto").style.display = "none"; // Ocultar input para cargar imagen
            document.getElementById("foto-perfil").style.display = "block"; // Mostrar la imagen de perfil

            document.getElementById("btn-modificar").style.display = "inline-block"; // Mostrar botón Modificar
            document.getElementById("btn-guardar").style.display = "none"; // Ocultar botón Guardar
            document.getElementById("btn-cancelar").style.display = "none"; // Ocultar botón Cancelar Edición
        }

        // Función para enviar el formulario
        function guardarCambios() {
            document.getElementById("form-perfil").submit(); // Aquí puedes enviar el formulario si es necesario
        }
    </script>
</head>
<body>
<div id="mensaje-error" class="mensaje-exito" style="display:none; background-color: red;"><p>No se puede editar su perfil pues su contraseña actual es incorrecta</p></div>
<div id="mensaje-exito-actualizar" class="mensaje-exito mensaje-exito-estado" style="display:none;"><p>¡Su perfil se actualizó con éxito!</p></div>
<div class="profile-container">
    <a href="<?php echo $volver; ?>" class="icon-close">
        <iconify-icon icon="zondicons:close-outline" width="1.5rem" height="1.5rem"></iconify-icon>
    </a>
    <h2>Mi Perfil</h2>
    
    <form id="form-perfil" action="perfil.php" method="POST" enctype="multipart/form-data">
        <!-- Imagen de perfil -->
        <img id="foto-perfil" src="<?php echo htmlspecialchars($_SESSION['foto']); ?>" alt="Foto de Perfil" style="width: 100px; height: 100px; border-radius: 50%;">
        
        <div id="upload-foto" style="display: none;"> <!-- Oculto inicialmente -->
            <div class="image-container">
                <div class="image-wrapper">
                   

                    <img id="foto-perfil" src="<?php echo htmlspecialchars($_SESSION['foto']); ?>" alt="Foto de Perfil" style="width: 100px; height: 100px; border-radius: 50%;">
                    <div class="overlay" onclick="document.getElementById('foto-input').click();">Cambiar Foto</div> <!-- Aquí activas el input -->
                    <input type="file" id="foto-input" name="nueva_foto" accept="image/*" style="display: none;" onchange="previewImage(event)">
                </div>
                <div class="image-wrapper" id="new-image-wrapper" style="display: none;">
                    <img id="foto-preview" style="width: 100px; height: 100px; border-radius: 50%;" alt="Vista Previa de Foto">
                    <div class="overlay" onclick="cancelImage()">Eliminar</div>
                    <button type="button" id="cancel-button" onclick="cancelImage()">Eliminar</button>
                </div>
            </div>
        </div>
        
        <p>
            <strong>ID de Usuario:</strong><br>
            <?php echo htmlspecialchars($_SESSION['id_user']) ?>
        </p>
        
        <p>
            <strong>Rol:</strong><br>
            <?php echo htmlspecialchars($_SESSION['nombre_rol']); ?>
        </p>

        <!-- Nombre de Usuario -->
        <p id="nombre-container">
            <strong>Nombre de Usuario:</strong><br>
            <span id="nombre-text"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <input type="text" id="nombre" name="nombre_usuario" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly style="display: none;">
        </p>

        <!-- Correo -->
        <p id="correo-container">
            <strong>Correo:</strong><br>
            <span id="correo-text"><?php echo htmlspecialchars($_SESSION['correo']); ?></span>
            <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($_SESSION['correo']); ?>" readonly style="display: none;">
        </p>
     
        <!-- Campo para cambiar la contraseña, inicialmente oculto -->
        <p id="nuevo-password" style="display: none;">

            <!-- Campo para la contraseña actual -->
            <strong>Contraseña Actual:</strong><br>
            <input type="password" name="password_actual" id="password_actual" placeholder="Su Contraseña Actual"><br><br>

            <strong>Nueva Contraseña:</strong><br>
            <input type="password" name="nueva_password" placeholder="Su Nueva contraseña"> 
        </p>

        <div class="buttons">
            <button type="button" onclick="window.location.href = '<?php echo $volver; ?>'">Volver</button>
            <button type="button" id="btn-modificar" onclick="habilitarEdicion()">Modificar</button>
            <button type="button" id="btn-cancelar" style="display: none;" onclick="cancelarEdicion()">Cancelar</button>
            <button type="submit" id="btn-guardar" name='guardar' style="display: none;">Guardar</button>
        </div>
    </form>
</div>


<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('foto-preview');
            output.src = reader.result;
            output.style.display = 'block'; // Mostrar la vista previa

            // Mostrar la imagen nueva y el botón de cancelar
            document.getElementById('new-image-wrapper').style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function cancelImage() {
        // Ocultar la imagen nueva y el botón de cancelar
        document.getElementById('new-image-wrapper').style.display = 'none';

        // Resetear el input de archivo
        document.getElementById('foto-input').value = ''; // Limpiar el input
        document.getElementById('foto-preview').style.display = 'none'; // Ocultar la vista previa
    }

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('mostrarMensajeError')) {
        const mensajeError = document.getElementById("mensaje-error");
        mensajeError.style.display = "block"; // Mostrar el mensaje de error
        // Eliminar el parametro de la url
        urlParams.delete('mostrarMensajeError'); // Eliminar el parámetro
        window.history.replaceState({}, document.title, window.location.pathname + '?' + urlParams.toString());
        // Ocultar el mensaje después de 2 segundos
        setTimeout(() => {
            mensajeError.style.display = "none";
        },4000);
        // Permitir que el mensaje se cierre al hacer clic
        mensajeError.onclick = function() {
            // Limpiar el parámetro de la URL al cerrar el mensaje
            urlParams.delete('mostrarMensajeError'); // Eliminar el parámetro
            window.history.replaceState({}, document.title, window.location.pathname + '?' + urlParams.toString());
            mensajeError.style.display = "none";
        };
        habilitarEdicion();
    }else if (urlParams.has('mensajeExitoActualizar')) {
        const mensajeExitoInsert = document.getElementById("mensaje-exito-actualizar");
        mensajeExitoInsert.style.display = "block"; // Mostrar el mensaje
        urlParams.delete('mensajeExitoActualizar'); // Eliminar el parámetro
        window.history.replaceState({}, document.title, window.location.pathname + '?' + urlParams.toString());
        // Ocultar el mensaje después de 2 segundos
        setTimeout(() => {
            mensajeExitoInsert.style.display = "none";
        }, 2000);
        // Permitir que el mensaje se cierre al hacer clic
            mensajeExitoInsert.onclick = function() {
            mensajeExitoInsert.style.display = "none";
        };
    }
</script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
</body>
</html>
