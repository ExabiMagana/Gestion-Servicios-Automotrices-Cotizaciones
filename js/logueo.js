var checkbox = document.getElementById('viewpass');
function mostrarpass() {
    var password = document.getElementById('password');
	if (checkbox.checked) {
        password.setAttribute('type', 'text');
        lbelpass.innerHTML="<ion-icon name='eye-off' style='cursor: pointer;'></ion-icon>";
    } else {
        password.setAttribute('type', 'password');
        lbelpass.innerHTML="<ion-icon name='eye' style='cursor: pointer;'></ion-icon>";
    }
}

function validarFormulario() {
    let esValido = true;

    // Limpiar mensajes de error
    limpiarErrores();

    var username = document.getElementById('username');
    var password = document.getElementById('password');
    var errorUsername = document.getElementById('error-username');
    var errorPassword = document.getElementById('error-password');

    // Validar Nombre de Usuario
    if (username.value.trim() === "") {
        errorUsername.textContent = "Debe rellenar el campo de usuario.";
        username.classList.add('input-error');
        username.focus();
        esValido = false;
    } else if (username.value.trim().length < 6) {
        errorUsername.textContent = "El usuario debe contener mínimo 6 caracteres y sin espacios en blanco.";
        username.classList.add('input-error');
        username.focus();
        esValido = false;
    }

    // Validar Contraseña
    if (password.value.trim() === "") {
        errorPassword.textContent = "Debe rellenar el campo de contraseña.";
        password.classList.add('input-error');
        password.focus();
        esValido = false;
    } else if (password.value.trim().length < 8) {
        errorPassword.textContent = "La contraseña debe contener mínimo 8 caracteres y sin espacios en blanco.";
        password.classList.add('input-error');
        password.focus();
        esValido = false;
    }

    return esValido;
}

// Función para limpiar los mensajes de error
function limpiarErrores() {
    const errores = document.querySelectorAll('.error');
    const campos = document.querySelectorAll('.input-error');
    errores.forEach(function(error) {
        error.textContent = "";
    });
    campos.forEach(function(campo) {
        campo.classList.remove('input-error');
    });
}

// Event listeners
checkbox.addEventListener('change', mostrarpass);
document.addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
        validarFormulario(); // Llama a la función de validación
    }
});

const urlParams = new URLSearchParams(window.location.search);
if (urlParams.has('mensajeExitoEliminar')) {
    const mensajeExito = document.getElementById("mensaje-exito");
    mensajeExito.style.display = "block"; // Mostrar el mensaje
    urlParams.delete('mensajeExitoEliminar'); // Eliminar el parámetro
    window.history.replaceState({}, document.title, window.location.pathname + '?' + urlParams.toString());
    // Ocultar el mensaje después de 2 segundos
    setTimeout(() => {
        mensajeExito.style.display = "none";
    }, 3000);
    // Permitir que el mensaje se cierre al hacer clic
    mensajeExito.onclick = function() {
    mensajeExito.style.display = "none";
    };
}
