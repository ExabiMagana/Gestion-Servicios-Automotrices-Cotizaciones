<?php
if (isset($_POST['insertar'])) {
  insertarCotizacion();
}elseif (isset($_GET['eliminar'])) {
  eliminarCotizacion();
}elseif (isset($_GET['detalles'])) {
  $datos=buscarActualizarCotizacion();
  $_SESSION['cotizacion_detalles'] = $datos;
  echo "<script>window.location.href='detallescotizacion.php';</script>";
}elseif (isset($_POST['guardar'])) {
  $id_cotizacion = $_SESSION['cotizacion_detalles']['cotizacion']['id_cotizacion'];
    actualizarCotizacion($id_cotizacion);
  echo "<script>alert('Estados actualizados exitosamente'); window.location.href='detallescotizacion.php';</script>";
}

?>
