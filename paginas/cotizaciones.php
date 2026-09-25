<?php 
	include_once '../sesiones/sesionstart.php'; 
	include_once '../database/cotizacionesConsultas.php';
	include_once '../PHP/procesarCotizacion.php';
	
	/*if (!isset($_POST['insertar'])) {
	  $cotizacion=true;
	}*/

	if (isset($_GET['exito']) && $_GET['exito'] == 'si') {
   			?>
	    <script type="text/javascript" defer>
	        window.onload = function() {
	            const mensajeExito = document.getElementById("mensaje-exito-enviar");
	            if (mensajeExito) {
	                mensajeExito.style.display = "block"; // Mostrar el mensaje
	                // Ocultar el mensaje después de 2 segundos
	                setTimeout(() => {
	                    mensajeExito.style.display = "none";
	                    cerrarModalEnvio(); // Cerrar el modal al ocultar el mensaje
	                    window.location.reload();
	                }, 2000);
	                // Permitir que el mensaje se cierre al hacer clic
	                mensajeExito.onclick = function() {
	                    mensajeExito.style.display = "none";
	                    window.location.reload();
	                };

	            } else {
	                console.log("Elemento con id 'mensaje-exito-insertar' no encontrado.");
	            }
	        };
	    </script>
	    <?php
	}
	$pagina='cotizaciones';

	if (isset($_SESSION['accesos'][$pagina]) && $_SESSION['accesos'][$pagina] == 1) {
	    if ($clase=='premium') {
	        $colorbody='#222831';
	    }elseif ($clase=='medium') {
	        $colorbody='#39494C';
	    }elseif ($clase=='basic') {
	        $colorbody='#738988';
	    }else{
	        $colorbody='#284860';
	    }
	    include_once '../complementos/implementos.php';
	    echo "
	        <style type='text/css'>
	            body {
	                background-color: $colorbody;
	                margin: 0;
	                font-family: Arial, sans-serif;
	            }

	            #modal-acceso {
	                position: fixed;
	                top: 50%;
	                left: 50%;
	                transform: translate(-50%, -50%);
	                background-color: #fff;
	                padding: 20px;
	                width: 40%;
	                max-width: 500px;
	                height: 300px;
	                border-radius: 8px;
	                border: 1px solid #f00;
	                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
	                z-index: 1000;
	                text-align: center;
	                display: flex;
	                flex-direction: column;
	                align-items: center;
	                justify-content: center;
	                gap: 15px;
	                color: #333;
	            }

	            #modal-acceso p {
	                font-size: 25px;
	                margin: 0;
	            }

	            #modal-acceso iconify-icon {
	                color: #17d0ed;
	                font-size: 4em;
	            }
	        </style>
	        <div id='modal-acceso'>
	            <iconify-icon icon='fluent:person-warning-32-regular'></iconify-icon>
	            <p><strong>No tienes acceso a esta página</strong></p>
	            <p>Redirigiendo al inicio...</p>
	            <iconify-icon icon='eos-icons:loading'></iconify-icon>
	        </div>";
	    echo "<script>
	            setTimeout(function() {
	                window.location.href = 'index.php';
	            }, 2000);
	          </script>";
	    exit();
	}
    $_SESSION['previo']="cotizaciones.php";
	
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotizaciones</title>
    <script
	src="https://code.jquery.com/jquery-3.3.1.min.js"
	integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
	crossorigin="anonymous"></script>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="../estilos/style.css">
</head>
<body>
	<!-- Mensajes de Éxito / Mensajes de Error -->
	<div id="mensaje-exito" class="mensaje-exito" style="display:none;">¡Registro eliminado con éxito!</div>
	<div id="mensaje-exito-actualizar" class="mensaje-exito mensaje-exito-estado" style="display:none;">¡Registro Actualizado con éxito!</div>
   <div id="mensaje-exito-enviar" class="mensaje-exito mensaje-insertar" style="display:none; background-color: #ffbb00; color: black;">¡Cotización enviada éxitosamente!</div>
   <div id="mensaje-exito-insertar" class="mensaje-exito mensaje-insertar" style="display:none;">¡Registro Insertado con éxito!</div>
    <?php include '../complementos/sidebar.php'; ?>
    <div class="main">
        <?php include '../complementos/topbar.php'; ?>
        <div class="navigation-buttons">
            <button onclick="cargarContenido('formulario')">Agregar Cotización</button>
            <button onclick="cargarContenido('mostrar')">Mostrar Cotización</button>
        </div>
        <div class="content" id="content">
            <!-- El contenido cambiará dinámicamente aquí -->
        </div>
    </div>
     <?php include_once '../complementos/implementos.php'; ?>
    <script>

    	// Función para obtener el parámetro de la URL
		function obtenerParametroUrl(param) {
		    var urlParams = new URLSearchParams(window.location.search);
		    return urlParams.get(param);
		}

		$(document).ready(function(){
		    var opcion = obtenerParametroUrl('opcion'); // Obtener el valor del parámetro 'opcion'

		    if (opcion === 'mostrar') {
		        // Si la opción es 'mostrar', cargar la lista de cotizaciones
		        cargarContenido('mostrar');
		    } else {
		        // Por defecto, cargar el formulario
		        cargarContenido('formulario');
		    }

		    // Mostrar la lista de servicios y repuestos al cargar
		    mostrarLista();
		    mostrarListaRepuestos();
		});

		function cargarContenido(opcion) {
		    var contentDiv = document.getElementById('content');
		    var botones = document.querySelectorAll('.navigation-buttons button');
		    var clase = "<?php echo $clase ?>";

		    // Elimina la clase 'activo' de todos los botones
		    botones.forEach(function(boton) {
		        boton.classList.remove('activo');
		    });

		    // Asigna la clase 'activo' al botón correspondiente
		    if (opcion === 'mostrar') {
		        botones[1].classList.add('activo', clase);
		        actualizarParametroUrl('opcion', 'mostrar');  // Actualizar la URL con el parámetro 'opcion=mostrar'
		    } else if (opcion === 'formulario') {
		        botones[0].classList.add('activo', clase);
		        eliminarParametroUrl('opcion');  // Eliminar el parámetro 'opcion' de la URL si es 'formulario'
		    }

		    var xhr = new XMLHttpRequest();
		    if (opcion === 'mostrar') {
		        xhr.open('GET', '../complementos/mostrarcotizaciones.php', true); // Aquí se cargará la vista para mostrar cotizaciones
		    } else if (opcion === 'formulario') {
		        xhr.open('GET', '../complementos/formularioscotizaciones.php', true); // Aquí se cargará el formulario de agregar cotización
		    }

		    xhr.onload = function() {
		        if (this.status == 200) {
		            contentDiv.innerHTML = this.responseText;
		        }
		    };
		    xhr.send();
		}

		function actualizarParametroUrl(nombre, valor) {
		    var url = new URL(window.location.href);
		    url.searchParams.set(nombre, valor); // Añadir o actualizar el parámetro
		    window.history.replaceState(null, null, url);
		}

		function eliminarParametroUrl(nombre) {
		    var url = new URL(window.location.href);
		    url.searchParams.delete(nombre); // Eliminar el parámetro
		    window.history.replaceState(null, null, url);
		}


		// Modales de Confirmación
		let idMecanicoAEliminar;

        function cerrarModal() {
            document.getElementById("modal-confirmacion").style.display = "none";
        }

        function cerrarModalEnvio() {
		    document.getElementById("modal-confirmacion-envio").style.display = "none";
		}

		// Llama a la función que muestra el modal de eliminación
		function confirmarEliminacion(id) {
		    idMecanicoAEliminar = id; 
		    document.getElementById("modal-confirmacion").style.display = "flex"; 
		    document.getElementById("modal-mensaje").innerHTML = '¿Quieres eliminar este registro?<strong> Esta acción es irreversible.</strong>';
		}

		// Llama a la función que muestra el modal de envío
		function confirmarEnvio(id, nombreCliente, correoCliente) {
		    idCotizacionAEnviar = id; 
		    document.getElementById("modal-confirmacion-envio").style.display = "flex"; 
		    document.getElementById("modal-mensaje-envio").innerHTML = '¿Quieres enviar este correo a <br><strong>' + nombreCliente + '</strong> (<strong>' + correoCliente + '</strong>)?';
		}

		// Escuchar clics en el documento
		document.addEventListener("click", function(event) {
		    // Verifica si el clic se hizo en el botón confirmar para eliminar
		    if (event.target.matches("#modal-confirmacion .btn-confirmar")) {
		        window.location.href = 'cotizaciones.php?opcion=mostrar&eliminar=si&id=' + idMecanicoAEliminar + '&mostrarMensaje=true';
		    } 
		    // Verifica si el clic se hizo en el botón cancelar o en el botón cerrar del modal de eliminación
		    else if (event.target.matches("#modal-confirmacion .btn-cancelar") || event.target.matches("#modal-confirmacion .close")) {
		        cerrarModal();
		    }
		    // Verifica si el clic se hizo en el botón confirmar para enviar el correo
		    else if (event.target.matches("#modal-confirmacion-envio .btn-confirmar.envio")) {
		        document.getElementById("modal-titulo-envio").innerHTML = "Enviando..."; // Cambiar el título del modal
		        document.getElementById("modal-mensaje-envio").innerHTML = "Por favor, espera mientras se envía el correo.<br>Este proceso puede tardar unos minutos...<br><iconify-icon icon='eos-icons:loading'></iconify-icon>"; // Cambiar mensaje
		        document.getElementById("button-container").style.display = "none";
		        document.getElementById("close-envio").style.display = "none";
		        setTimeout(() => {
		            window.location.href = '../fpdf/index.php?generar=enviar&id=' + idCotizacionAEnviar; // Redirigir después de un breve retraso
		        }, 1000); // 1 segundo de retraso
		    } 
		    // Verifica si el clic se hizo en el botón cancelar o en el botón cerrar del modal de envío
		    else if (event.target.matches("#modal-confirmacion-envio .btn-cancelar.envio") || event.target.matches("#modal-confirmacion-envio .close.envio")) {
		        cerrarModalEnvio();
		    }
		});

        // Mostrar mensaje de éxito si el parámetro está presente
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('mostrarMensaje')) {
            const mensajeExito = document.getElementById("mensaje-exito");
            mensajeExito.style.display = "block"; // Mostrar el mensaje
            // Ocultar el mensaje después de 2 segundos
            setTimeout(() => {
                mensajeExito.style.display = "none";
            }, 2000);
            // Permitir que el mensaje se cierre al hacer clic
            mensajeExito.onclick = function() {
                mensajeExito.style.display = "none";
            };
        }else if (urlParams.has('mensajeExitoInsertar')) {
            const mensajeExitoInsert = document.getElementById("mensaje-exito-insertar");
            mensajeExitoInsert.style.display = "block"; // Mostrar el mensaje
            urlParams.delete('mensajeExitoInsertar'); // Eliminar el parámetro
            window.history.replaceState({}, document.title, window.location.pathname + '?' + urlParams.toString());
            // Ocultar el mensaje después de 2 segundos
            setTimeout(() => {
                mensajeExitoInsert.style.display = "none";
            }, 2000);
            // Permitir que el mensaje se cierre al hacer clic
            mensajeExitoInsert.onclick = function() {
                mensajeExitoInsert.style.display = "none";
            };
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


//agregar inicio
//*************************************SERVICIOS*************************************************
function eliminarServicio(id){
		$.ajax({
			type:"POST",
			url:"../complementos/datosCotizacionServicios.php",
			data:{"id" : id, "eliminar" : true},
			success:function(r){
				$('#id-list-servicios').html(r);
			}
		});

		mostrarLista();
	}

	function recargarLista(){
		var combo = document.getElementById("servicio-select");
		var comboselected = combo.options[combo.selectedIndex].text;
		$.ajax({
			type:"POST",
			url:"../complementos/datosCotizacionServicios.php",
			data:{"id" : $('#servicio-select').val(), "servicio" : comboselected},
			success:function(r){
				$('#id-list-servicios').html(r);
			}
		});

		mostrarLista();
	}

	function mostrarLista(){
		$.ajax({
			type:"POST",
			url:"../complementos/datosCotizacionServicios.php",
			data:{"id" : 1000, "mostrar" : 1},
			success:function(r){
				$('#id-list-servicios').html(r);
			}
		});
	}


//*************************************REPUESTOS*************************************************

	function eliminarRepuesto(id){
		$.ajax({
			type:"POST",
			url:"../complementos/datosCotizacionRepuestos.php",
			data:{"id" : id, "eliminar" : true},
			success:function(r){
				$('#id-list-repuestos').html(r);
			}
		});

		mostrarListaRepuestos();
	}

	function restarCantidad(id) {
    $.ajax({
        type: "POST",
        url: "../complementos/datosCotizacionRepuestos.php",  // Ajusta la ruta según la ubicación de tu archivo PHP
        data: { "id": id, "restar": true },
        success: function(r) {
            $('#id-list-repuestos').html(r); // Actualiza la lista de repuestos
        }
    });

    mostrarListaRepuestos();
}
	function recargarListaRepuesto(){
		var combo = document.getElementById("repuesto-select");
		var comboselected = combo.options[combo.selectedIndex].text;
		$.ajax({
			type:"POST",
			url:"../complementos/datosCotizacionRepuestos.php",
			data:{"id" : $('#repuesto-select').val(), "repuesto" : comboselected},
			success:function(r){
				$('#id-list-repuestos').html(r);
			}
		});

		//mostrarListaRepuestos();
	}

	function mostrarListaRepuestos(){
		$.ajax({
			type:"POST",
			url:"../complementos/datosCotizacionRepuestos.php",
			data:{"id" : 1000, "mostrar" : 1},
			success:function(r){
				$('#id-list-repuestos').html(r);
			}
		});
	}

	//agregar fin

       function removeParam(parameter) {
            var url = new URL(window.location.href);
            url.searchParams.delete(parameter);
            window.history.replaceState(null, null, url);
        }

        removeParam('exito');
    </script>
</body>
</html>
