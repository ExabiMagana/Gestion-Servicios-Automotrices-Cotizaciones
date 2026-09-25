<script type="text/javascript">
    let list = document.querySelectorAll(".navigation li");

    function activeLink() {
        list.forEach((item) => {
            item.classList.remove("hovered");
        });
        this.classList.add("hovered");
    }

    function removeLink() {
        this.classList.remove("hovered");
    }

    // Agregar eventos de mouseover y mouseout a cada elemento de navegación
    list.forEach((item) => {
        item.addEventListener("mouseover", activeLink);
        item.addEventListener("mouseout", removeLink);
    });

    const toggle = document.querySelector('.toggle');
    const navigation = document.querySelector('.navigation');
    const main = document.querySelector('.main');

    // Cargar el estado de la barra de navegación desde localStorage
    const isActive = localStorage.getItem('navActive') === 'true';
    if (isActive) {
        navigation.classList.add('active');
        main.classList.add('active');
    }

    // Manejar el toggle de navegación
    toggle.addEventListener('click', () => {
        navigation.classList.toggle('active');
        main.classList.toggle('active');
        
        // Guardar el estado en localStorage
        localStorage.setItem('navActive', navigation.classList.contains('active'));
    });
</script>

<script>
document.getElementById('foto-input').addEventListener('change', function(event) {
    // Obtén el archivo seleccionado
    var file = event.target.files[0];

    // Si hay un archivo seleccionado y es una imagen
    if (file && file.type.startsWith('image/')) {
        // Crear un objeto URL para la imagen seleccionada
        var reader = new FileReader();

        // Al cargar el archivo, muestra la vista previa
        reader.onload = function(e) {
            var preview = document.getElementById('foto-preview');
            preview.src = e.target.result;
            preview.style.display = 'block';  // Mostrar la imagen
        };

        // Leer el contenido del archivo como una URL de datos
        reader.readAsDataURL(file);
    } else {
        // Ocultar la vista previa si no es un archivo de imagen
        document.getElementById('foto-preview').style.display = 'none';
    }
});
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const output = document.getElementById('imagePreview');
        output.src = reader.result;
        output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}

</script>

<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>