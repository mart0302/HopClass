<?php
    ob_start();
    include "../conexiondb.php";
    $usuario = $_SESSION["id_usuario"];
    $parametrizar = $conexion->prepare("UPDATE notificacionat SET leido = '1' WHERE id_amigo = ? AND leido = '0'");
    $parametrizar->bind_param("i", $usuario);
    $parametrizar->execute();
?>
