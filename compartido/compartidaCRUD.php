<?php
session_start();
ob_start();
include "../conexiondb.php";
include "../toast.php";

$tipo = $_POST['tipo'];

switch ($tipo) {
    case 'Guardar':
        agregar($conexion);
        break;
    case 'Cancelar':
        cancelar($conexion);
        break;
    case 'EditarA':
        editar($conexion);
        break;
    case 'EditarI':
        editari($conexion);
        break;
    case 'Modificar':
        modificar($conexion);
        break;
    case 'EliminarA':
        eliminara($conexion);
        break;
    case 'EliminarI':
        eliminari($conexion);
    break;
    case 'Rechazar':
        rechazar($conexion);
    break;
    case 6:
        rechazar($conexion);
            break;
    default:
        echo "ERROR";
        break;
}

function agregar($conexion) {
    $usuario = $_SESSION["id_usuario"];
    $semestre = $_POST['semestre'];
    $materias = $_POST['materias'];
    $nombre = $_POST['nombre'];
    $fecha = $_POST['fecha_inicio'];
    $hora = $_POST['hora'];
    $prioridad = 2;
    $comentario = $_POST['comentario'];
    $idcompartir = $_POST['id_tarea'];
    $amigo = $_POST['amigo'];
    //Insertar la tarea para el amigo
    $sql = "INSERT INTO tarea(id_usuario, id_semestre, id_materia, nombre_tarea, fecha_inicio, hora_inicio, prioridad, observacion, completada, id_compartir) VALUES
    ('$usuario', '$semestre', '$materias', '$nombre', '$fecha', '$hora', '$prioridad', '$comentario', 0, '$idcompartir')";
    if(($result = $conexion->query($sql)) === FALSE) {
        echo "Error: " . $sql . "<br>" . $conexion->error;
    }
    //Cambiar el estado de la tarea para el usuario anfitrión
    $sql2 = "UPDATE compartir SET compartir=1 WHERE id_compartir = '$idcompartir';";
    if(($result = $conexion->query($sql2)) === FALSE) {
        echo "Error: " . $sql2 . "<br>" . $conexion->error;
    }
    $toastmensaje = toast(TRUE, "Tarea Guardada");
    $_SESSION["toastmesaje"] = $toastmensaje;
    header('location: ../compartida.php');
    
    //Enviar notificación de que se aceptó la tarea
    $not = "INSERT INTO notificacionat(id_usuario, id_amigo, tipo, leido, fecha) VALUES
        ('$usuario', '$amigo','aceptó la tarea', 0, now())";
    if(($result = $conexion->query($not)) === FALSE) {
        echo "Error: " . $not . "<br>" . $conexion->error;
    }
}

function cancelar($conexion) {
    $id_tarea = $_POST['bcancelar'];
    $id_compartir = $_POST['idCompartir'];
    $sql = "DELETE FROM tarea WHERE id_tarea = '$id_tarea';"; 
    if(($result = $conexion->query($sql)) === FALSE) {
        echo "Error: " . $sql . "<br>" . $conexion->error;}
    $sql2 = "DELETE FROM compartir WHERE id_compartir = '$id_compartir';"; 
    if(($result = $conexion->query($sql2)) === FALSE) {
        echo "Error: " . $sql2 . "<br>" . $conexion->error;}
    $toastmensaje = toast(TRUE, "Se canceló la solicitud");
    $_SESSION["toastmesaje"] = $toastmensaje;
    header('location: ../compartida.php');
    /*Enviar notificación de que acepto la tarea
    No es posible eliminar la notificación porque no existe un campo almacenando el id de la solicitud o tarea
    Modificar la db?
    Eliminar notificación de solicitud de tarea
    $not = "DELETE FROM  notificacionat WHERE id = $id";
    if(($result = $conexion->query($not)) === FALSE) {
        echo "Error: " . $not . "<br>" . $conexion->error;}*/
}

function prio($pri) {
    switch ($pri) {
        case '1':
            return "Baja";
            break;
        case '2':
            return "Media";
            break;
        case '3':
            return "Alta";
            break;   
        default:
            break;
    }
}

function editar($conexion){
    $id_tarea = $_POST['id_tarea'];
    $id_compartir = $_POST['id_compartir'];
    $sql = "SELECT nombre_tarea, prioridad, observacion FROM tarea WHERE id_tarea = '$id_tarea';";
    if(($result = $conexion->query($sql)) === FALSE) {
        echo "Error: " . $sql . "<br>" . $conexion->error;
    }
    $result = mysqli_query($conexion, $sql);
    $tarea = mysqli_fetch_assoc($result);

    $nombre = $tarea["nombre_tarea"];
    $descripcion = $tarea["observacion"];
    $prioridad = $tarea["prioridad"];

    echo '
    <html>
    <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Panel</title>
    <link rel="icon" href="img/Icon v2.png">
    <link rel="stylesheet" href="css/principal.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Abril+Fatface|Poppins">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <div class="container-fluid mt-5">
            <div class="row">
                <div class="card col-md-8 mx-auto">
                    <form action="compartidaCRUD.php" method="post">
                        <div class="card-header row">
                            <div class="col-6"><img src="img/Icon v2.png" alt="Logo" class="logoig"></div>
                            <div class="col-6 pt-2 text-end"><input type="submit" value="Modificar Tarea" class="btn" id="btn1"></div>                   
                        </div>
                        <div class="card-body row">
                            <div class="col-md-6 mx-auto">
                            <div class="container">
                                <label for="nombre_tarea" class="fw-bold col-8 form-label">Nombre Tarea:</label>
                                <input type="text" name="nombre_tarea" value="'. $nombre .'" class="col-4 form-control" maxlength="50" pattern="^(?=.*[a-zA-Z0-9])(?! )[\sa-zA-ZáéíóúÁÉÍÓÚñÑ0-9!@#$%^&*()_+\-=[\]{};:\\|,.<>/?]{1,50}$"
                                title="Letras mayúsculas, minúsculas, números, carácteres especiales y espacios permitidos" required>
                                <div class="pt-4"></div>
                                <label for="observacion" class="fw-bold col-8 form-label">Descripción Tarea:</label>
                                <textarea name="observacion" class="col-4 form-control" style="height:100px;" maxlength="100" pattern="^(?=.*[a-zA-Z0-9])(?! )[\sa-zA-ZáéíóúÁÉÍÓÚñÑ0-9!@#$%^&*()_+\-=[\]{};:\\|,.<>/?]{1,100}$" required>'. $descripcion .'</textarea>
                                <label for="prioridad" class="fw-bold col-8 form-label mt-2">Prioridad Actual: '. prio($prioridad) .' <br><br>Nueva:</label>
                                <select class="form-select" name="prioridad" id="prioridad">
                                    <option value="1">Baja</option>
                                    <option value="2">Media</option>
                                    <option value="3">Alta</option>
                                </select>
                                <input type="hidden" value="Modificar" name="tipo">
                                <input type="hidden" value="'. $id_tarea .'" name="id_tarea">
                                <input type="hidden" value="'. $id_compartir .'" name="id_compartir">
                                <div class="pt-3"></div>
                            </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
    </html>';
}

function editari($conexion){
    $id_tarea = $_POST['id_tarea'];
    $id_compartir = $_POST['id_compartir'];
    $sql = "SELECT nombre_tarea, prioridad, observacion FROM tarea WHERE id_tarea = '$id_tarea';";
    if(($result = $conexion->query($sql)) === FALSE) {
        echo "Error: " . $sql . "<br>" . $conexion->error;
    }
    $result = mysqli_query($conexion, $sql);
    $tarea = mysqli_fetch_assoc($result);

    $nombre = $tarea["nombre_tarea"];
    $descripcion = $tarea["observacion"];
    $prioridad = $tarea["prioridad"];

    echo '
    <html>
    <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Panel</title>
    <link rel="icon" href="img/Icon v2.png">
    <link rel="stylesheet" href="css/principal.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Abril+Fatface|Poppins">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <div class="container-fluid mt-5">
            <div class="row">
                <div class="card col-md-8 mx-auto">
                    <form action="compartidaCRUD.php" method="post">
                        <div class="card-header row">
                            <div class="col-6"><img src="img/Icon v2.png" alt="Logo" class="logoig"></div>
                            <div class="col-6 pt-2 text-end"><input type="submit" value="Modificar Tarea" class="btn" id="btn1"></div>                   
                        </div>
                        <div class="card-body row">
                            <div class="col-md-6 mx-auto">
                            <div class="container">
                                <label for="nombre_tarea" class="fw-bold col-8 form-label">Nombre Tarea:</label>
                                <input type="text" name="nombre_tarea" value="'. $nombre .'" class="col-4 form-control" readonly>
                                <div class="pt-4"></div>
                                <label for="observacion" class="fw-bold col-8 form-label">Descripción Tarea:</label>
                                <textarea name="observacion" class="col-4 form-control" style="height:100px;" maxlength="100" pattern="^(?=.*[a-zA-Z0-9])(?! )[\sa-zA-ZáéíóúÁÉÍÓÚñÑ0-9!@#$%^&*()_+\-=[\]{};:\\|,.<>/?]{1,100}$" required>'. $descripcion .'</textarea>
                                <label for="prioridad" class="fw-bold col-8 form-label mt-2">Prioridad Actual: '. prio($prioridad) .' <br><br>Nueva:</label>
                                <select class="form-select" name="prioridad" id="prioridad">
                                    <option value="1">Baja</option>
                                    <option value="2">Media</option>
                                    <option value="3">Alta</option>
                                </select>
                                <input type="hidden" value="Modificar" name="tipo">
                                <input type="hidden" value="'. $id_tarea .'" name="id_tarea">
                                <input type="hidden" value="'. $id_compartir .'" name="id_compartir">
                                <div class="pt-3"></div>
                            </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
    </html>';
}
    
function modificar($conexion) { 
    session_start();
    $id_tarea = $_POST['id_tarea'];
    $id_compartir = $_POST['id_compartir'];
    $newNombre = $_POST['nombre_tarea'];
    $newDes = $_POST['observacion'];
    $newprio = $_POST['prioridad'];

    $sql = "UPDATE tarea SET nombre_tarea  = '$newNombre' WHERE id_compartir  = '$id_compartir';";
    if(($result = $conexion->query($sql)) === FALSE) {
        echo "Error: " . $sql . "<br>" . $conexion->error;
    } 
    $sql2 = "UPDATE tarea SET prioridad = '$newprio', observacion = '$newDes' WHERE id_tarea  = '$id_tarea';";
    if(($result = $conexion->query($sql2)) === FALSE) {
        echo "Error: " . $sql2 . "<br>" . $conexion->error;
    } 
    
    $toastmensaje = toast(TRUE, "Tarea Modificada Correctamente");
    $_SESSION["toastmesaje"] = $toastmensaje;
    header('location: ../compartida.php');
}

function eliminara($conexion) {
    $usuario = $_SESSION["id_usuario"];
    $id_compartir = $_POST['idCompartir'];
    $id_tarea = $_POST["eliminara"];
    //Comprueba que los campos no esten vacíos
    if (!isset($_POST['eliminara'], $_POST['idCompartir'], $_SESSION["id_usuario"])) {
        echo "Faltan datos necesarios para aceptar la solicitud.";
        return;
    }
    //Eliminar la tarea de las cuenrtas de ambos usuarios
    $sql = "DELETE FROM tarea WHERE id_compartir = '$id_compartir';";
    if(($result = $conexion->query($sql)) === FALSE) {
        echo "Error: " . $sql . "<br>" . $conexion->error;}
    //Eliminar la tarea de las tabla compartidas
    $sql2 = "DELETE FROM compartir WHERE id_compartir = '$id_compartir';";
    if(($result = $conexion->query($sql2)) === FALSE) {
        echo "Error: " . $sql2 . "<br>" . $conexion->error;}
    $toastmensaje = toast(TRUE, "Eliminaste la tarea");
    $_SESSION["toastmesaje"] = $toastmensaje;
    header('location: ../compartida.php');
    
    /*No se pueden eliminar las notificaciones de la tarea compartida
    $not = "DELETE FROM  notificacionat WHERE ((id_usuario = $id_amigo AND id_amigo= $usuario) OR (id_usuario = $usuario AND id_amigo= $id_amigo))  AND (tipo=' y tú ya son amigos' OR tipo='solicitud de tarea' OR tipo='aceptó la tarea')";
    if(($result = $conexion->query($not)) === FALSE) {
        echo "Error: " . $not . "<br>" . $conexion->error;
    }*/
}

function eliminari($conexion) {
    $usuario = $_SESSION["id_usuario"];
    $id_compartir = $_POST['idCompartir'];
    $id_tarea = $_POST["eliminari"];
    //Comprueba que los campos no esten vacíos
    if (!isset($_POST['eliminari'], $_POST['idCompartir'], $_SESSION["id_usuario"])) {
        echo "Faltan datos necesarios para aceptar la solicitud.";
        return;
    }
    //Eliminar la tarea de la tabla del usuario invitado
    $sql = "DELETE FROM tarea WHERE id_compartir = '$id_compartir' AND id_usuario=$usuario;";
    if(($result = $conexion->query($sql)) === FALSE) {
        echo "Error: " . $sql . "<br>" . $conexion->error;}
    //Eliminar la tarea de la tabla compartida
    $sql2 = "DELETE FROM compartir WHERE id_compartir = '$id_compartir';";
        if(($result = $conexion->query($sql2)) === FALSE) {
            echo "Error: " . $sql2 . "<br>" . $conexion->error;}
    //Cambiar el valor de id_compartir a 0 para que ya no le aparezca al usuario como NO compartida
    $sql3 = "UPDATE tarea SET id_compartir=0 WHERE id_compartir= '$id_compartir';";
    if(($result = $conexion->query($sql3)) === FALSE) {
        echo "Error: " . $sql . "<br>" . $conexion->error;
    }
    $toastmensaje = toast(TRUE, "Eliminaste la tarea");
    $_SESSION["toastmesaje"] = $toastmensaje;
    header('location: ../compartida.php');
    
    /*No se pueden eliminar las notificaciones de la tarea compartida
    $not = "DELETE FROM  notificacionat WHERE ((id_usuario = $id_amigo AND id_amigo= $usuario) OR (id_usuario = $usuario AND id_amigo= $id_amigo))  AND (tipo=' y tú ya son amigos' OR tipo='solicitud de tarea' OR tipo='aceptó la tarea')";
    if(($result = $conexion->query($not)) === FALSE) {
        echo "Error: " . $not . "<br>" . $conexion->error;
    }*/
}

function rechazar($conexion) {
    $id_compartir = $_POST['idCompartir'];
    $usuario = $_SESSION["id_usuario"];
    $amigo = $_POST["idAmigo"];
    //Comprueba que los campos no esten vacíos
    if (!isset($_POST['idCompartir'], $_SESSION["id_usuario"], $_POST["idAmigo"])) {
        echo "Faltan datos necesarios para aceptar la solicitud.";
        return;
    }
    $sql = "DELETE FROM compartir WHERE id_compartir = '$id_compartir';";
    if(($result = $conexion->query($sql)) === FALSE) {
        echo "Error: " . $sql . "<br>" . $conexion->error;
    }

    //Actualizar a 0 el valor de la tarea compartida
    $sql2 = "UPDATE tarea SET id_compartir=0 WHERE id_compartir = '$id_compartir';";
    if(($result = $conexion->query($sql2)) === FALSE) {
        echo "Error: " . $sql2 . "<br>" . $conexion->error;
    }
    
    $toastmensaje = toast(TRUE, "No aceptaste la tarea");
    $_SESSION["toastmesaje"] = $toastmensaje;
    header('location: ../compartida.php');
    
    //Crear notificación de que se rechazo
    $not = "INSERT INTO notificacionat(id_usuario, id_amigo, tipo, leido, fecha) VALUES ('$usuario', '$amigo','rehazó la tarea', 0, now())";
    if(($result = $conexion->query($not)) === FALSE) {
    echo "Error: " . $not . "<br>" . $conexion->error;
}
}
?>