// Ventana modal
var modal = document.getElementById("ventanaModal");

// Botón que abre el modal
var boton = document.getElementById("abrirModal");

// Cierra la ventana
var span = document.getElementsByClassName("cerrar")[0];

// Al dar click en el botón la ventana se abre
boton.addEventListener("click", function() {
  modal.style.display = "block";
});

// Al dar click en el botón la ventana se cierra
span.addEventListener("click", function() {
  modal.style.display = "none";
});

// Si el usuario hace click fuera de la ventana se cierra
window.addEventListener("click", function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
});
