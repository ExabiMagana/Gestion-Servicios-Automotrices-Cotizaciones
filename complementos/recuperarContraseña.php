<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Correo</title>
    <link rel="stylesheet" href="../estilos/stylelogin.css">
</head>
<body>
    <div class="split-screen">
        <div class="left-section">
            <div class="overlay"></div>
        </div>
        <div class="right-section">
            <div class="overlay"></div>
        </div>
    </div>
    <div class="login-container">
        <div class="login-box">
            <h2>Recuperar Contraseña</h2>
            <div id="errores" class="error-messages">
                <div id="error-correo" class="error"></div> 
            </div>
            <form id="recoverForm" action="../PHP/procesarRecuperar.php" method="POST"  onsubmit="return validarCorreo();">
                <div class="input-group">
                    <input type="text" id="email" name="email" placeholder="Introduce tu Correo Electrónico">
                    <span class="input-icon"><ion-icon name="mail"></ion-icon></span>
                </div>
                <button type="submit" name="recuperar">Enviar</button>
            </form>
            <p><a href="../index.php">Volver al Login</a></p>
        </div>
    </div>
    <div id="mensaje-error" class="mensaje-exito" style="display:none; background: red; color: white;">Correo no registrado</div>
    <div id="mensaje-error-envio" class="mensaje-exito" style="display:none; background: red; color: white;">No se pudo enviar el correo</div>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
<script>
function validarCorreo() {
    const emailInput = document.getElementById('email');
    const emailValue = emailInput.value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Expresión regular para validar el correo
    const errorCorreo = document.getElementById('error-correo'); // Asegúrate de tener un div para mostrar el error

    // Limpiar mensajes de error previos
    errorCorreo.textContent = ""; 
    emailInput.classList.remove('input-error'); // Limpiar clase de error

    // Verificar que el campo no esté vacío
    if (emailValue === "") {
        errorCorreo.textContent = "El campo de correo electrónico no puede estar vacío.";
        emailInput.classList.add('input-error'); // Agregar clase de error
        emailInput.focus();
        return false; // Prevenir el envío del formulario
    }

    // Verificar que el correo sea válido
    if (!emailRegex.test(emailValue)) {
        errorCorreo.textContent = "Por favor, introduce un correo electrónico válido.";
        emailInput.classList.add('input-error'); // Agregar clase de error
        emailInput.focus();
        return false; // Prevenir el envío del formulario
    }

    return true; // Si todas las validaciones pasan, se permite el envío del formulario
}

const urlParams = new URLSearchParams(window.location.search);
if (urlParams.has('mensajeError')) {
    const mensajeExito = document.getElementById("mensaje-error");
    mensajeExito.style.display = "block"; // Mostrar el mensaje
    urlParams.delete('mensajeError'); // Eliminar el parámetro
    window.history.replaceState({}, document.title, window.location.pathname + '?' + urlParams.toString());
    // Ocultar el mensaje después de 2 segundos
    setTimeout(() => {
        mensajeExito.style.display = "none";
    }, 2000);
    // Permitir que el mensaje se cierre al hacer clic
    mensajeExito.onclick = function() {
        mensajeExito.style.display = "none";
    };
}else if (urlParams.has('mensajeErrorEnvio')) {
    const mensajeExito = document.getElementById("mensaje-error-envio");
    mensajeExito.style.display = "block"; // Mostrar el mensaje
    urlParams.delete('mensajeErrorEnvio'); // Eliminar el parámetro
    window.history.replaceState({}, document.title, window.location.pathname + '?' + urlParams.toString());
    // Ocultar el mensaje después de 2 segundos
    setTimeout(() => {
        mensajeExito.style.display = "none";
    }, 2000);
    // Permitir que el mensaje se cierre al hacer clic
    mensajeExito.onclick = function() {
        mensajeExito.style.display = "none";
    };
}
</script>