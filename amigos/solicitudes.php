<?php
    ob_start();
    include "conexiondb.php";
    $usuario= $amigo = $_SESSION["id_usuario"];
    $parametrizar = $conexion->prepare("SELECT a.id, u.id_usuario, u.nombre FROM amigo AS a JOIN usuario AS u 
                                        ON a.id_usuario = u.id_usuario WHERE a.id_amigo = ? AND a.estado=0");
    $parametrizar->bind_param("i", $amigo);
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
                                <input type="hidden" value="'.$mostraramigos["id_usuario"].'" name="baceptar">
                                <input type="hidden" value="'.$mostraramigos["id"].'" name="bsolicitud">
                                <p><button class="button" id="bsolicitud" name="tipo" value="3">Aceptar</button></p>
                            </form>
                            <form action="amigos/amigosCRUD.php" method="post" id="cancelar" >
                                <input type="hidden" value="'.$mostraramigos["id_usuario"].'" name="baceptar">
                                <input type="hidden" value="'.$mostraramigos["id"].'" name="bsolicitud">
                            <p><button class="button" id="bsolicitud" name="tipo" value="5">Rechazar</button></p>
                            </form>

                        </div>
                    </div>
               </div>';
    }
    echo '</div>';
?>