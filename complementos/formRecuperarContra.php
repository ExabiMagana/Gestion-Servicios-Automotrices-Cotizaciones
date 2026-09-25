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
            <form id="recoverForm" action="../PHP/procesarRecuperar.php" method="POST">
                <div class="input-group">
                    <input type="password" id="password" name="password" placeholder="Introduce tu nueva Contraseña" required>
                    <span class="input-icon"><ion-icon name="mail"></ion-icon></span>
                </div>
                <div class="input-group">
                    <input type="password" id="confirm-password" name="confirm-password" placeholder="Introducela nuevamente" required>
                    <span class="input-icon"><ion-icon name="mail"></ion-icon></span>
                </div>
                <div class="input-group">
                    <input type="text" id="code" name="code" placeholder="Introduce el código" required>
                    <span class="input-icon"><ion-icon name="mail"></ion-icon></span>
                </div>
                <button type="submit" name="cambiar">Enviar</button>
            </form>
            <p><a href="../index.php">Volver al Login</a></p>
        </div>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
