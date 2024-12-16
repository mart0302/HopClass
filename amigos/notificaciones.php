<?php
    ob_start();
    include "conexiondb.php";
    $usuario = $_SESSION["id_usuario"];
    $parametrizar = $conexion->prepare("SELECT * FROM notificacionat WHERE id_amigo = ? ORDER BY id_notificacion desc");
    $parametrizar->bind_param("i", $usuario);
    $parametrizar->execute();
    $buscaramigos = $parametrizar->get_result(); 

    $noleidas = 0;
    echo '<a id="notificacion" href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-boundary="viewport">
            <i class="bx bx-bell"></i>
            <span class="num"></span>
          </a>
          <div id="notificaciones" class="dropdown-menu dropdown-menu-right" aria-labelledby="notificacion" style="right: 0;">';       
                    while ($mostraramigos = mysqli_fetch_array($buscaramigos)) {
                        //Crear un query para mostrar el nombre del usuario
                        $parametrizarnom = $conexion->prepare("SELECT nombre, apellido_paterno FROM usuario WHERE id_usuario = (SELECT id_usuario FROM notificacionat WHERE id_amigo = ? LIMIT 1)");
                        $parametrizarnom->bind_param("i", $usuario);
                        $parametrizarnom->execute();
                        $nombre = $parametrizarnom->get_result();  
                        $mostrarnom = mysqli_fetch_assoc($nombre);
                            if ($mostraramigos['tipo']==='te envío una solicitud' || $mostraramigos['tipo']===' y tú ya son amigos'){
                                echo '<a href="amigos.php" class="dropdown-item">
                                        <i class="fa fa-users text-aqua"></i> ' . $mostrarnom['nombre'].'  '.$mostrarnom['apellido_paterno'] . ' ' . $mostraramigos['tipo'] . ' 
                                      </a>';
                            }
                            else{
                                echo '<a href="compartida.php" class="dropdown-item">
                                        <i class="fa fa-users text-aqua"></i> ' . $mostrarnom['nombre'].'  '.$mostrarnom['apellido_paterno'] . ' ' . $mostraramigos['tipo'] . ' 
                                      </a>'; 
                            }
                        if ($mostraramigos['leido'] == 0) {
                            $noleidas++;
                        }
                    }
        echo '</div>'; 
    //Utilizar un script para mostrar el número total de notas junto a notificación
    echo '<script>$(".num").text('.$noleidas.');</script>';
    //Actualización para que se cambie el valor de las notificaciones después de ser leídas a 1 y ya no se contalizaben
    $actualizar_leido = $conexion->prepare("UPDATE notificacionat SET leido = '1' WHERE id_amigo = ? AND leido = '0'");
    $actualizar_leido->bind_param("i", $usuario);
?>

<!--Biblioteca para la función $ para seleccionar el elemento HTML y para el evento click-->

<script>
    $('#notificacion').click(function(not) {
        not.preventDefault(); 
        $.ajax({
            type: 'POST',
            url: 'amigos/marcar_leido.php',
            success: function(data) {
                $('.num').text('0'); 
                <?php $actualizar_leido->execute(); ?>
            }
        });
    });
</script>


