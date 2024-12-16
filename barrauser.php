<?php
    $nombreu="SELECT nombre, apellido_paterno from usuario WHERE id_usuario=$usuario";
    $busqueda=mysqli_query($conexion,$nombreu);
    $username=mysqli_fetch_array($busqueda);
    echo '
        <div class="dropdown dropend nombreusuario">
            <button type="button" class="btn botonu" data-bs-toggle="dropdown">
                <i class="bx bx-user-circle" style="color:white;" ></i><p class="textU">'.$username['nombre'].' '.$username['apellido_paterno'].'</p>
            </button>
            <ul class="dropdown-menu">
                <div class="text-start dropdown-item"><form id="formF" action="sesion.php" method="post" style="margin-bottom: 0px;"><input type="submit" value="Cerrar Sesión" name="tipo" class="btn" id="boton"></form></div>
                <li><a class="dropdown-item" href="#eliminar" data-bs-toggle="modal" class="btn">Eliminar cuenta</a></li>
            </ul>
        </div>';
?>             