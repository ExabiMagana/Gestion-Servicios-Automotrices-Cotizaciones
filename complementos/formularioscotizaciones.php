<?php session_start(); 
include '../database/cotizacionesConsultas.php';
?>
<div class="form-content cotizacion">
    <form action="#" method="post">

        <div class="form-div">
            <div class="titulo">
                 <label>
                    <h3>
                        Cotización:             
                    </h3>
                </label>
            </div>
            <div class="texto">
                <label>Vehiculo:</label>
                <select name="vehiculo">
                    <option value="0">Seleccione el Vehiculo</option>
                    <?php generarSelectVehiculo(); ?>
                </select>
                <ion-icon name="add"></ion-icon>
            </div>
            <div class="texto">
                <label>Mecánico Encargado:</label>
                <select name="mecanico">
                    <option value="0">Seleccione un Mecanico</option>
                    <?php generarSelectMecanico(); ?>
                </select>
                <ion-icon name="add"></ion-icon>
            </div>
        </div>

        <div class="form-div">
            <div class="titulo">
                <label>
                    <h3>Servicios:</h3>
                </label>
            </div>
            <div class="select-container">
                <select name="servicio[]" id="servicio-select">
                    <option value="0">Seleccione el Servicio</option>
                    <?php generarSelect(); ?>
                </select>
                <ion-icon name="add"></ion-icon>
            </div>
            <div class="btn-form">
                <input type="button" name="guardar-servicio" value="Añadir" onclick="recargarLista();">
            </div>
            <div class="view-list servicios-list" id="id-list-servicios"></div>
        </div>

        <div class="form-div">
            <div class="titulo">
                <label>
                    <h3>Repuestos:</h3>
                </label>
            </div>
            <div class="select-container">
                <select name="repuesto[]" id="repuesto-select">
                    <option value="0">Seleccione el Repuesto</option>
                    <?php generarSelectRepuestos(); ?>
                </select>
                <ion-icon name="add"></ion-icon>
            </div>
             <div class="btn-form">
                <input type="button" name="guardar-repuesto" value="Añadir" onclick="recargarListaRepuesto()">
            </div>
            <div class="view-list repuestos-list" id="id-list-repuestos"><ul></ul></div>
        </div>

        <div class="btn-form">
            <input type="submit" name="insertar" value="Insertar Cotización" class="btn-add">
        </div>
    </form>

</div>