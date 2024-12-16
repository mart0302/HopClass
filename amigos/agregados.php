<?php
    ob_start();
    include "conexiondb.php";
    $usuario= $amigo = $_SESSION["id_usuario"];
    $parametrizar = $conexion->prepare("SELECT a.id, u.id_usuario, u.nombre FROM amigo AS a JOIN usuario AS u 
                                        ON a.id_amigo = u.id_usuario WHERE a.id_usuario = ? AND a.estado=1
                                        UNION
                                        SELECT a.id, u.id_usuario, u.nombre  FROM amigo AS a JOIN usuario AS u 
                                        ON a.id_usuario = u.id_usuario WHERE a.id_amigo = ? AND a.estado=1");
    $parametrizar->bind_param("ii", $usuario, $amigo);
    $parametrizar->execute();
    $buscaramigos = $parametrizar->get_result();
    echo '<div class="row">';
    while ($mostraramigos = mysqli_fetch_array($buscaramigos)){
        echo '<div class="column">
                    <div class="card">
                        <i class="bx bx-user" style="width:100%; font-size:8vw"></i>
                        <div class="container">
                            <h2>'.$mostraramigos["nombre"].'</h2>
                            <form action="amigos/amigosCRUD.php" method="post" id="cancelar" >
                                <input type="hidden" value="4" name="tipo">
                                <input type="hidden" value="'.$mostraramigos["id_usuario"].'" name="eliminarnot">
                                <p><button class="button" id="beliminar" name="beliminar" value="'.$mostraramigos["id"].'">Eliminar</button></p>
                            </form>
                        </div>
                    </div>
               </div>';
    }
    echo '</div>';
?>