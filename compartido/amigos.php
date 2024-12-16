<?php
    ob_start();
    include "conexiondb.php";
    $usuario= $amigo = $_SESSION["id_usuario"];
    $parametrizar = $conexion->prepare("SELECT u.id_usuario, u.nombre FROM amigo AS a JOIN usuario AS u 
                                         ON a.id_amigo = u.id_usuario WHERE a.id_usuario = ? AND a.estado=1
                                         UNION
                                         SELECT u.id_usuario, u.nombre FROM amigo AS a JOIN usuario AS u 
                                         ON a.id_usuario = u.id_usuario WHERE a.id_amigo = ? AND a.estado=1");
    $parametrizar->bind_param("ii", $usuario, $amigo);
    $parametrizar->execute();
    $buscaramigos = $parametrizar->get_result();
    while ($mostraramigos = mysqli_fetch_array($buscaramigos)){
        $param = "SELECT id, id_usuario, id_amigo FROM amigo WHERE (id_usuario = ? AND id_amigo = '{$_SESSION['id_usuario']}') OR (id_usuario ='{$_SESSION['id_usuario']}' AND id_amigo = ?)";
        $parame = mysqli_prepare($conexion, $param);
        mysqli_stmt_bind_param($parame, "ii", $mostraramigos["id_usuario"], $mostraramigos["id_usuario"]);
        mysqli_stmt_execute($parame);
        $resultado = mysqli_stmt_get_result($parame);
        $busqueda = mysqli_fetch_assoc($resultado);
        if ($usuario!=$busqueda["id_amigo"]){
            echo '<option value="'.$busqueda["id_amigo"].'">'.$mostraramigos["nombre"].'</option>';
        }
        else{
            echo '<option value="'.$busqueda["id_usuario"].'">'.$mostraramigos["nombre"].'</option>';
        }
          }
?>