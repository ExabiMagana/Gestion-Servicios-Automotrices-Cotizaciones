<?php 

include '../database/loginConsultas.php';

$datos = verificarLogueo();

if ($datos && $datos['estado'] == "ACTIVO") {
    // Si el usuario está activo, inicia sesión
    session_start();
    $_SESSION['verificacion'] = true;
    $_SESSION['username'] = $datos['nombre_usuario'];
    $_SESSION['rol'] = $datos['nombre_rol'];
    $_SESSION['id_user'] = $datos['id_usuario'];
    $_SESSION['estado'] = $datos['estado'];
    $_SESSION['foto'] = $datos['foto'];
    $_SESSION['nivel'] = $datos['nivel'];
    $_SESSION['arreglo'] = array();
    $_SESSION['arregloRepuestos'] = array();
    $_SESSION['accesos'] = $datos['accesos'];

    // Redirige al index si la sesión fue iniciada correctamente
    header("location:../paginas/index.php");
    exit;

} elseif ($datos && $datos['estado'] == "INACTIVO") {
    // Si el usuario existe pero está inactivo, redirige con el error2
    header("location:../sesiones/sesionclose.php?error2=1");
    exit;

} else {
    // Si el usuario no fue encontrado o no coincide la contraseña
    header("location:../sesiones/sesionclose.php?error=1");
    exit;
}

?>