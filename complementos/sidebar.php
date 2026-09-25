 <!-- =============== Navigation ================ -->
 <?php  
    include '../database/extrasConsultas.php';
    $datos=obtenerEmpresa(); 
 ?>
 <style type="text/css">
    .notaccess a {
        color: gainsboro!important;
        cursor: not-allowed;
        text-decoration: line-through!important;
    }
 </style>
    <div class="container">
        <div class="navigation <?php echo "$clase";?>">
            <ul>
                <li>
                    <a href="index.php" data-page="index">
                        <span class="icon">
                            <img src="<?php echo $datos['logo']; ?>">
                        </span>
                        <span class="title name"><?php echo $datos['nombre']; ?></span>
                    </a>
                </li>
                <!--<iconify-icon icon="mdi:reload"></iconify-icon>-->
                <li>
                    <a href="index.php" data-page="index">
                        <span class="icon">
                            <ion-icon name="home-outline"></ion-icon>
                        </span>
                        <span class="title">Inicio</span>
                    </a>
                </li>

                 <?php
                    if ($_SESSION['nivel']==5) {
                ?>
                        <li>
                            <a href="empresa.php" data-page="customers">
                                <span class="icon">
                                    <ion-icon name="business"></ion-icon>
                                </span>
                                <span class="title">Empresa</span>
                            </a>
                        </li>
                <?php
                    }
                ?>

                <?php 
                    if ($_SESSION['nivel']>2) {
                       ?>
                        <li class="<?php $claseAcceso = (isset($_SESSION['accesos']['usuarios']) && $_SESSION['accesos']['usuarios'] == 1) ? 'notaccess' : ''; echo $claseAcceso;?>">
                            <a href="usuarios.php" data-page="customers">
                                <span class="icon">
                                    <ion-icon name="person-circle-outline"></ion-icon>
                                </span>
                                <span class="title">Usuarios</span>
                            </a>
                        </li>
                         <?php
                        if ($_SESSION['nivel']==5) {
                            ?>
                        <li>
                            <a href="roles.php" data-page="customers">
                                <span class="icon">
                                    <iconify-icon icon="carbon:user-role"></iconify-icon>
                                </span>
                                <span class="title">Roles</span>
                            </a>
                        </li>
                       <?php
                        }
                    }
                 ?>    
                <li class="<?php $claseAcceso = (isset($_SESSION['accesos']['mecanicos']) && $_SESSION['accesos']['mecanicos'] == 1) ? 'notaccess' : ''; echo $claseAcceso;?>">
                    <a href="mecanicos.php" data-page="customers">
                        <span class="icon">
                            <ion-icon name="build-outline"></ion-icon>
                        </span>
                        <span class="title">Mecánicos</span>
                    </a>
                </li>
                <li class="<?php $claseAcceso = (isset($_SESSION['accesos']['clientes']) && $_SESSION['accesos']['clientes'] == 1) ? 'notaccess' : ''; echo $claseAcceso;?>">
                    <a href="clientes.php" data-page="messages">
                        <span class="icon">
                            <ion-icon name="man-outline"></ion-icon>
                        </span>
                        <span class="title">Clientes</span>
                    </a>
                </li>
                <li class="<?php $claseAcceso = (isset($_SESSION['accesos']['vehiculos']) && $_SESSION['accesos']['vehiculos'] == 1) ? 'notaccess' : ''; echo $claseAcceso;?>">
                    <a href="vehiculos.php" data-page="help">
                        <span class="icon">
                            <ion-icon name="car-sport-outline"></ion-icon>
                        </span>
                        <span class="title">Vehículos</span>
                    </a>
                </li>
                <li class="<?php $claseAcceso = (isset($_SESSION['accesos']['servicios']) && $_SESSION['accesos']['servicios'] == 1) ? 'notaccess' : ''; echo $claseAcceso;?>">
                    <a href="servicios.php" data-page="settings">
                        <span class="icon">
                            <iconify-icon icon="mdi:mechanic"></iconify-icon>
                        </span>
                        <span class="title">Servicios</span>
                    </a>
                </li>
                <li class="<?php $claseAcceso = (isset($_SESSION['accesos']['repuestos']) && $_SESSION['accesos']['repuestos'] == 1) ? 'notaccess' : ''; echo $claseAcceso;?>">
                    <a href="repuestos.php" data-page="password">
                        <span class="icon">
                            <ion-icon name="construct-outline"></ion-icon>
                        </span>
                        <span class="title">Repuestos</span>
                    </a>
                </li>
                <li class="<?php $claseAcceso = (isset($_SESSION['accesos']['cotizaciones']) && $_SESSION['accesos']['cotizaciones'] == 1) ? 'notaccess' : ''; echo $claseAcceso;?>">
                    <a href="cotizaciones.php" data-page="password">
                        <span class="icon">
                            <ion-icon name="journal-outline"></ion-icon>
                        </span>
                        <span class="title">Cotizaciones</span>
                    </a>
                </li>
                <li class="<?php $claseAcceso = (isset($_SESSION['accesos']['reportes']) && $_SESSION['accesos']['reportes'] == 1) ? 'notaccess' : ''; echo $claseAcceso;?>">
                    <a href="reportes.php" data-page="password">
                        <span class="icon">
                           <iconify-icon icon="mdi:file-report-outline"></iconify-icon>
                        </span>
                        <span class="title">Reportes</span>
                    </a>
                </li>
                <li>
                    <a href="../sesiones/sesionclose.php?salir=0">
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        <span class="title">Cerrar Sesión</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>