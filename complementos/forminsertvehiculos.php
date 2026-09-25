<div class="form-content">
    <div class="form-div">
        <form action="<?php $url = (isset($_GET['actualizar'])) ? "vehiculos.php?update=si" : "vehiculos.php"; echo "$url"; ?>" method="post" onsubmit="return validarFormulario()">
            <div>
                <label>
                    <h3>
                        <?php $title = (isset($_GET['actualizar'])) ? "Actualizar" : "Insertar"; echo "$title"; ?>                      
                    </h3>
                </label>
            </div>
            <div class="select-container">
                <label>Cliente:</label>
                <?php $value = (isset($_GET['actualizar'])) ? generarSelect_update($datos['cliente']) : generarSelect(); echo "$value"; ?>
                <input type="button" class="vehiculo-button" name="rolmodal" value="Agregar" onclick="modal();">
            </div>
            <div>
                <label>Marca:</label>
                <input type="text" name="marca" id="marca" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['marca'] : ""; echo "$value"; ?>">
            </div>
            <div>
                <label>Modelo:</label>
                <input type="text" name="modelo" id="modelo" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['modelo'] : ""; echo "$value"; ?>">
            </div>
            <div>
                <label>Año:</label>
                <input type="text" name="año" id="año" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['año'] : ""; echo "$value"; ?>">
            </div>
            <div>
                <label>Color:</label>
                <input type="text" name="color" id="color" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['color'] : ""; echo "$value"; ?>">
            </div>
            <div>
                <label>Tipo:</label>
                <input type="text" name="tipo" id="tipo" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['tipo'] : ""; echo "$value"; ?>" oninput="this.value = this.value.replace(/[^a-zA-Z\s-]/g, '').replace(/[^A-Z]/g, (match) => match.toUpperCase());">
            </div>
            <div>
                <label>Placas:</label>
                <input type="text" name="placas" id="placas" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['placas'] : ""; echo "$value"; ?>" oninput="this.value = this.value.toUpperCase();">
            </div>
            <div>
                <label>Chasis:</label>
                <input type="text" name="chasis" id="chasis" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['chasis'] : ""; echo "$value"; ?>">
            </div>
            <div>
                <label>Motor:</label>
                <input type="text" name="motor" id="motor" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['motor'] : ""; echo "$value"; ?>">
            </div>
            <div>
                <label>VIN:</label>
                <input type="text" name="vin" id="vin" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['vin'] : ""; echo "$value"; ?>">
            </div>
            <div>
                <label>Odometro:</label>
                <input type="text" name="odometro" id="odometro" value="<?php $value = (isset($_GET['actualizar'])) ? $datos['odometro'] : ""; echo "$value"; ?>">
            </div>
            
            <?php 
            if (isset($_GET['actualizar'])) { 
                echo "<input type='hidden' name='id' value=" . $_GET['id'] . ">";
                generarSelect_enum($datos['unidad_medida']);
            } else {
                genererSelect_medida();
            }
            ?>

            <div class="btn-form">
                <input type="submit" name="<?php $estado = (isset($_GET['actualizar'])) ? "update" : "enviar"; echo $estado; ?>" value="<?php $value = (isset($_GET['actualizar'])) ? "Modificar" : "Insertar"; echo "$value"; ?>">
                <a href="vehiculos.php"><input type="button" name="cancelar" value="Cancelar"></a>
            </div>
            <div>
                <div style="width: 100%;" id="errores" class="error-messages">
                    <div id="error-cliente" class="error" style="display: none;"></div> 
                    <div id="error-marca" class="error" style="display: none;"></div> 
                    <div id="error-modelo" class="error" style="display: none;"></div>
                    <div id="error-año" class="error" style="display: none;"></div> 
                    <div id="error-color" class="error" style="display: none;"></div>
                </div>
                <div style="width: 100%;" id="errores" class="error-messages">
                    <div id="error-tipo" class="error" style="display: none;"></div> 
                    <div id="error-placas" class="error" style="display: none;"></div> 
                    <div id="error-chasis" class="error" style="display: none;"></div>
                    <div id="error-motor" class="error" style="display: none;"></div> 
                    <div id="error-vin" class="error" style="display: none;"></div>
                    <div id="error-odometro" class="error" style="display: none;"></div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    function validarFormulario() {
        let esValido = true;

        // Limpiar mensajes de error
        limpiarErrores();

        // Validar Cliente
        const cliente = document.querySelector('.select-container select');
        const spanSelect= document.querySelector('.select2');
        const errorCliente = document.getElementById('error-cliente');
        if (cliente.value == "") {
            errorCliente.textContent = "El cliente es requerido.";
            spanSelect.classList.add('select2-error');
            errorCliente.style.display = 'block'; // Mostrar mensaje
            if (esValido) cliente.focus();
            esValido = false;
        }

        // Validar Marca
        const marca = document.getElementById('marca');
        const errorMarca = document.getElementById('error-marca');
        if (marca.value.trim() === "") {
            errorMarca.textContent = "La marca es requerida.";
            errorMarca.style.display = 'block'; // Mostrar mensaje
            marca.classList.add('input-error');
            marca.focus();
            esValido = false;
        }

        // Validar Modelo
        const modelo = document.getElementById('modelo');
        const errorModelo = document.getElementById('error-modelo');
        if (modelo.value.trim() === "") {
            errorModelo.textContent = "El modelo es requerido.";
            errorModelo.style.display = 'block'; // Mostrar mensaje
            modelo.classList.add('input-error');
            if (esValido) modelo.focus();
            esValido = false;
        }

        // Validar Año (debe ser un número de 4 dígitos y mayor a 1950)
        const año = document.getElementById('año');
        const errorAño = document.getElementById('error-año');
        const añoNum = parseInt(año.value.trim());

        if (isNaN(añoNum) || año.value.length !== 4 || añoNum < 1950) {
            errorAño.textContent = "El año debe ser un número de 4 cifras válido.";
            errorAño.style.display = 'block'; // Mostrar mensaje
            año.classList.add('input-error');
            if (esValido) año.focus();
            esValido = false;
        }

        // Validar Color
        const color = document.getElementById('color');
        const errorColor = document.getElementById('error-color');
        if (color.value.trim() === "") {
            errorColor.textContent = "El color es requerido.";
            errorColor.style.display = 'block'; // Mostrar mensaje
            color.classList.add('input-error');
            if (esValido) color.focus();
            esValido = false;
        }

        // Validar Tipo (solo letras, espacios y guiones)
        const tipo = document.getElementById('tipo');
        const errorTipo = document.getElementById('error-tipo');
        const tipoRegex = /^[a-zA-Z\s-]+$/;
        if (!tipoRegex.test(tipo.value.trim()) || tipo.value.trim() === "") {
            errorTipo.textContent = "El tipo es requerido.";
            errorTipo.style.display = 'block'; // Mostrar mensaje
            tipo.classList.add('input-error');
            if (esValido) tipo.focus();
            esValido = false;
        }

        // Validar Placas
        const placas = document.getElementById('placas');
        const errorPlacas = document.getElementById('error-placas');
        if (placas.value.trim() === "") {
            errorPlacas.textContent = "Las placas son requeridas.";
            errorPlacas.style.display = 'block'; // Mostrar mensaje
            placas.classList.add('input-error');
            if (esValido) placas.focus();
            esValido = false;
        }

        // Validar Chasis
        const chasis = document.getElementById('chasis');
        const errorChasis = document.getElementById('error-chasis');
        if (chasis.value.trim() === "") {
            errorChasis.textContent = "El chasis es requerido.";
            errorChasis.style.display = 'block'; // Mostrar mensaje
            chasis.classList.add('input-error');
            if (esValido) chasis.focus();
            esValido = false;
        }

        // Validar Motor
        const motor = document.getElementById('motor');
        const errorMotor = document.getElementById('error-motor');
        if (motor.value.trim() === "") {
            errorMotor.textContent = "El motor es requerido.";
            errorMotor.style.display = 'block'; // Mostrar mensaje
            motor.classList.add('input-error');
            if (esValido) motor.focus();
            esValido = false;
        }

        // Validar VIN
        const vin = document.getElementById('vin');
        const errorVin = document.getElementById('error-vin');
        if (vin.value.trim() === "") {
            errorVin.textContent = "El VIN es requerido.";
            errorVin.style.display = 'block'; // Mostrar mensaje
            vin.classList.add('input-error');
            if (esValido) vin.focus();
            esValido = false;
        }

        // Validar Odometro
        const odometro = document.getElementById('odometro');
        const errorOdometro = document.getElementById('error-odometro');

        // Si el odómetro no se proporciona, simplemente no se validará
        if (odometro.value.trim() !== "") {
            const odometroNum = parseFloat(odometro.value.trim());

            // Validar que no sea un número negativo
            if (odometroNum < 0) {
                errorOdometro.textContent = "El odómetro no puede ser un número negativo.";
                errorOdometro.style.display = 'block'; // Mostrar mensaje
                odometro.classList.add('input-error');
                if (esValido) odometro.focus();
                esValido = false;
            } else if (isNaN(odometroNum)) {
                errorOdometro.textContent = "El odómetro debe ser un número válido.";
                errorOdometro.style.display = 'block'; // Mostrar mensaje
                odometro.classList.add('input-error');
                if (esValido) odometro.focus();
                esValido = false;
            } else {
                errorOdometro.style.display = 'none'; // Limpiar mensaje de error si es válido
                odometro.classList.remove('input-error'); // Limpiar clase de error
            }
        }
       
        return esValido; // Si todo es válido, se envía el formulario
    }

    function limpiarErrores() {
        const errores = document.querySelectorAll('.error');
        errores.forEach(error => {
            error.textContent = ""; // Limpia el mensaje de error
            error.style.display = 'none'; // Oculta el mensaje
        });
        const inputs = document.querySelectorAll('input[type="text"]');
        inputs.forEach(input => {
            input.classList.remove('input-error'); // Limpia el estilo de error
        });
    }
</script>
