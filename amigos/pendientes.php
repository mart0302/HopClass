<?php
    ob_start();
    include "conexiondb.php";
    $usuario = $_SESSION["id_usuario"];

    $parametrizar = $conexion->prepare("SELECT a.id, u.id_usuario, u.nombre FROM amigo AS a JOIN usuario AS u 
                                        ON a.id_amigo = u.id_usuario WHERE a.id_usuario = ? AND a.estado=0;");
    $parametrizar->bind_param("i", $usuario);
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
                                <input type="hidden" value="2" name="tipo">
                                <p><button class="button" id="bcancelar" name="bcancelar" value="'.$mostraramigos["id"].'">Cancelar</button></p>
                            </form>
                        </div>
                    </div>
               </div>';
    }
    echo '</div>';
?>