<div class="topbar <?php 
    if ($_SESSION['nivel']<=2) {
        $clase="basic";
    }elseif ($_SESSION['nivel']>=3 AND $_SESSION['nivel']<5) {
        $clase="medium";
    }elseif ($_SESSION['nivel']==5) {
         $clase="premium";
    }echo "$clase";?>">
    <div class="toggle">
        <ion-icon name="menu-outline" style="color: #FFFFFF;"></ion-icon>
    </div>
    <div class='logo-center'>
        <span class='title'><?php echo $datos['nombre'] ?></span>
    </div>
    <div class="user">
        <a href=""><iconify-icon icon="ion:reload-circle-sharp"></iconify-icon></a>
        <label for="user-menu-toggle">
            <span class="user-text"><?php echo $_SESSION['username'] ?></span>
            <img src="<?php echo $_SESSION['foto'] ?>" alt="">
        </label>
    </div>
    <input type="checkbox" id="user-menu-toggle" style="display: none;">
    <ul class="dropdown-menu" id="dropdown-menu">
        <li><a href="perfil.php">Mi Perfil</a></li>
        <li><a href="../sesiones/sesionclose.php?salir=si">Cerrar Sesión</a></li>
    </ul>
</div>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
    var checkbox = document.getElementById('user-menu-toggle');
    var list = document.getElementById('dropdown-menu');

    checkbox.addEventListener('change', function () {
        if (checkbox.checked) {
            list.classList.add('dropdown-menu-active');
        } else {
            list.classList.remove('dropdown-menu-active');
        }
    });
});

</script>