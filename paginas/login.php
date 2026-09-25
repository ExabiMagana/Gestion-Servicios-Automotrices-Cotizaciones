<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            <h2>Login</h2>
            <div id="errores" class="error-messages">
                <div id="error-username" class="error"></div> <!-- Mensaje de error para el archivo -->
                <div id="error-password" class="error"></div> <!-- Mensaje de error para el nombre -->
            </div>
            <span id="error"></span>
            <form id="loginForm" action="../PHP/procesarLogin.php" method="POST" onsubmit="return validarFormulario()">
                <div class="input-group">
                    <input type="text" id="username" name="username" placeholder="Nombre de Usuario">
                    <span class="input-icon"><ion-icon name="person"></ion-icon></span>
                </div>
                <div class="input-group">
                    <input type="password" id="password" name="password" placeholder="Contraseña">
                    <span class="input-icon"><input type="checkbox" name="viewpass" id="viewpass" style="display: none;"><label for="viewpass" id="label-pass"><ion-icon name="eye" style="cursor: pointer;"></ion-icon></label></span>
                </div>
                <button type="submit" id="button-iniciar">Iniciar</button>
            </form>
            <a href="../complementos/recuperarContraseña.php">Olvidé mi contraseña</a>
        </div>
    </div>
      <?php 
        if (isset($_GET['error'])) {
            echo "<script>
            var error = document.getElementById('error');
            error.innerHTML='Usuario o Contraseña errónea';
            document.addEventListener('DOMContentLoaded', function() {
                if (window.location.search.includes('error=1')) {
                    window.history.replaceState(null, null, window.location.pathname);
                }
            });
            </script>";
        } elseif (isset($_GET['error2'])) {
            echo "<script>
            var error = document.getElementById('error');
            error.innerHTML='Usuario Desactivado';
            document.addEventListener('DOMContentLoaded', function() {
                if (window.location.search.includes('error2=1')) {
                    window.history.replaceState(null, null, window.location.pathname);
                }
            });
            </script>";
        }
    ?>
    <div id="mensaje-exito" class="mensaje-exito" style="display:none;">¡Perfil de usuario eliminado con éxito!</div>
    <script src="script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
<script src="../js/logueo.js"></script>
</html>

