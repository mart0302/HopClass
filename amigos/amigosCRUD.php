<?php
session_start();
ob_start();
include "../conexiondb.php";
include "../toast.php";

$tipo = $_POST['tipo'];

switch ($tipo) {
    case 1:
        agregar($conexion);
        break;
    case 2:
        cancelar($conexion);
        break;
    case 3:
        aceptar($conexion);
        break;
    case 4:
        eliminar($conexion);
        break;
    case 5:
        rechazar($conexion);
            break;
    default:
        echo "ERROR";
        break;
}

function agregar($conexion) {
    $id_amigo = $_POST['bagregar'];
    $usuario = $_SESSION["id_usuario"];
    $sql = "INSERT INTO amigo(id_usuario, id_amigo, estado) VALUES
            ('$usuario', '$id_amigo', 0)";
    if(($result = $conexion->query($sql)) === FALSE) {
        echo "Error: " . $sql . "<br>" . $conexion->error;
    }
    $toastmensaje = toast(TRUE, "Solicitud enviada");
    $_SESSION["toastmesaje"] = $toastmensaje;
    header('location: ../amigos.php');

    //Enviar notificación de solicitud
    $not = "INSERT INTO notificacionat(id_usuario, id_amigo, tipo, leido, fecha) VALUES
    ('$usuario', '$id_amigo','te envío una solicitud', 0, now())";
    if(($result = $conexion->query($not)) === FALSE) {
        echo "Error: " . $not . "<br>" . $conexion->error;
    }
}

function cancelar($conexion) {
    $id = $_POST['bcancelar'];
    $sql = "DELETE FROM amigo WHERE id = '$id';"; 
    if(($result = $conexion->query($sql)) === FALSE) {
        echo "Error: " . $sql . "<br>" . $conexion->error;}
    $toastmensaje = toast(TRUE, "Se canceló la solicitud");
    $_SESSION["toastmesaje"] = $toastmensaje;
    header('location: ../amigos.php');

    //Eliminar notificación de solicitud cuando cancela
    $not = "DELETE FROM  notificacionat WHERE id = $id";
    if(($result = $conexion->query($not)) === FALSE) {
        echo "Error: " . $not . "<br>" . $conexion->error;}
}

function aceptar($conexion) {
    $id = $_POST['bsolicitud'];
    $id_amigo = $_POST['baceptar'];
    $usuario = $_SESSION["id_usuario"];
    //Comprueba que los campos no esten vacíos
    if (!isset($_POST['bsolicitud'], $_POST['baceptar'], $_SESSION["id_usuario"])) {
        echo "Faltan datos necesarios para aceptar la solicitud.";
        return;
    }
    //Actualiza el valor de estado para confirmar la solicitud
    $sql = "UPDATE amigo SET estado=1 WHERE id = '$id';";
    if(($result = $conexion->query($sql)) === FALSE) {
        echo "Error: " . $sql . "<br>" . $conexion->error;
    }
    $toastmensaje = toast(TRUE, "Ahora son amigos!!");
    $_SESSION["toastmesaje"] = $toastmensaje;
    header('location: ../amigos.php');

    //Enviar notificación de que aceptaron la solicitud
    $not = "INSERT INTO notificacionat(id_usuario, id_amigo, tipo, leido, fecha) VALUES ('$usuario', '$id_amigo', ' y tú ya son amigos', 0, now())";
    if(($result = $conexion->query($not)) === FALSE) {
    echo "Error: " . $not . "<br>" . $conexion->error;
    }
    //Eliminar notificación de que enviaron solicitud
    $eliminarnot = "DELETE FROM  notificacionat WHERE ((id_usuario = $id_amigo AND id_amigo= $usuario) OR (id_usuario = $usuario AND id_amigo= $id_amigo))  AND tipo='te envío una solicitud'";
    if(($result = $conexion->query($eliminarnot)) === FALSE) {
        echo "Error: " . $eliminarnot . "<br>" . $conexion->error;
    }
}

function eliminar($conexion) {
    $id = $_POST['beliminar'];
    $id_amigo = $_POST['eliminarnot'];
    $usuario = $_SESSION["id_usuario"];
    //Comprueba que los campos no esten vacíos
    if (!isset($_POST['beliminar'], $_POST['eliminarnot'], $_SESSION["id_usuario"])) {
        echo "Faltan datos necesarios para aceptar la solicitud.";
        return;
    }
    $sql = "DELETE FROM amigo WHERE id = '$id';";
    if(($result = $conexion->query($sql)) === FALSE) {
        echo "Error: " . $sql . "<br>" . $conexion->error;}
    $toastmensaje = toast(TRUE, "Eliminaste a tu amigo");
    $_SESSION["toastmesaje"] = $toastmensaje;
    header('location: ../amigos.php');
    
    //Eliminar la notificación de que ya son amigos cuando cualquiera de los dos usuarios elimine al amigo
    $not = "DELETE FROM  notificacionat WHERE ((id_usuario = $id_amigo AND id_amigo= $usuario) OR (id_usuario = $usuario AND id_amigo= $id_amigo))  AND (tipo=' y tú ya son amigos' OR tipo='solicitud de tarea' OR tipo='aceptó la tarea')";
    if(($result = $conexion->query($not)) === FALSE) {
        echo "Error: " . $not . "<br>" . $conexion->error;
    }

    //Eliminar las tareas compartidas de la tabla tarea entre ambos amigos
    $tareas = "DELETE t FROM tarea AS t INNER JOIN compartir AS c ON t.id_compartir=c.id_compartir WHERE (c.id_usuario = $id_amigo AND c.id_amigo= $usuario) OR (c.id_usuario = $usuario AND c.id_amigo= $id_amigo)";
    if(($result = $conexion->query($tareas)) === FALSE) {
        echo "Error: " . $tareas . "<br>" . $conexion->error;
    }
    //Eliminar las tareas compartidas de la tabla tarea entre ambos amigos
    $tareas = "DELETE FROM compartir WHERE (id_usuario = $id_amigo AND id_amigo= $usuario) OR (id_usuario = $usuario AND id_amigo= $id_amigo)";
    if(($result = $conexion->query($tareas)) === FALSE) {
        echo "Error: " . $tareas . "<br>" . $conexion->error;
    }
}

function rechazar($conexion) {
    $id = $_POST['bsolicitud'];
    $id_amigo = $_POST['baceptar'];
    $usuario = $_SESSION["id_usuario"];
    //Comprueba que los campos no esten vacíos
    if (!isset($_POST['bsolicitud'], $_POST['baceptar'], $_SESSION["id_usuario"])) {
        echo "Faltan datos necesarios para aceptar la solicitud.";
        return;
    }
    $sql = "DELETE FROM amigo WHERE id = '$id';";
    if(($result = $conexion->query($sql)) === FALSE) {
        echo "Error: " . $sql . "<br>" . $conexion->error;}
    $toastmensaje = toast(TRUE, "Eliminaste a tu amigo");
    $_SESSION["toastmesaje"] = $toastmensaje;
    header('location: ../amigos.php');
    
    //Eliminar la notificación de que se envio solicitud
    $not = "DELETE FROM  notificacionat WHERE ((id_usuario = $id_amigo AND id_amigo= $usuario) OR (id_usuario = $usuario AND id_amigo= $id_amigo))  AND tipo='te envío una solicitud'";
    if(($result = $conexion->query($not)) === FALSE) {
        echo "Error: " . $not . "<br>" . $conexion->error;
    }
}
?>