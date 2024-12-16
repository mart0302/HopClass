<?php
ob_start();
include "conexiondb.php";
include_once "toast.php";

if (isset($_POST['opc'])) {
    $opc = $_POST['opc'];
}
else {
    $opc = 0;
}

switch ($opc) {
    case '1':
        mostrarNotificaciones($conexion);
        break;
    case '2':
        eliminarNotificacion($conexion);
        break;
    case '3':
        crearNotificacion($conexion);
        break; 
    case '0':
        break;   
    default:
        header('location: principal.php');
        break;
}

function mostrarNotificaciones($conexion){
    if (isset($_POST['id_tarea'])) {
        $tarea = $_POST['id_tarea'];
                
            /* PEDIMOS DATOS DE TAREA */
        $consultaTarea = "SELECT id_materia, nombre_tarea, DATE_FORMAT(fecha_inicio, '%d-%m-%Y') AS fecha_inicio, hora_inicio FROM tarea
        WHERE id_tarea='$tarea'";
        $resultado_tarea = $conexion->query($consultaTarea);
        $datosTarea = $resultado_tarea->fetch_assoc();

        $id_materia = $datosTarea["id_materia"];
        /* pedimos datos de materia */
        $consultaMateria = "SELECT nombre_materia, nombre_profesor FROM materia WHERE id_materia = '$id_materia'";
        $resultados_materia=$conexion->query($consultaMateria);
        $datosMateria=$resultados_materia->fetch_assoc();
            
        $nombre_materia=$datosMateria['nombre_materia'];
        $nombre_profesor=$datosMateria['nombre_profesor'];

                /* PEDIMOS DATOS NOTIFICACIONES */
        $consultaNotificacion = "SELECT id_notificacion, DATE_FORMAT(fecha, '%d-%m-%Y') AS fecha, hora FROM notificacion WHERE id_tarea = '$tarea'";
        $resultado_notificacion = $conexion->query($consultaNotificacion);
        
                /* SE ESCRIBE UN ENCABEZADO CON LOS DATOS DE MATERIA Y TAREA */
        echo  
        '
        <div class="mb-3 mt-3"> 
            <div class="row">
                <div class="col-12 mb-3 text-start">'. $nombre_materia .' - '. $nombre_profesor .'</div>
                <div class="col-6 mb-3 d-flex align-items-center">    
                    <div class="text-start">' . $datosTarea["nombre_tarea"] . '</div>
                </div>
                <div class="col-6 mb-3 text-start d-flex align-items-center">
                    <div>Fecha inicio: ' . $datosTarea["fecha_inicio"] . ' <br>Hora Inicio: ' . $datosTarea["hora_inicio"] . '</div>
                </div>
            <hr>
            <p>Notificaciones: </p>
        ';

        $numNot=1;

                /* SE ESCRIBE UNA A UNA LA NOTIFICACION */

        while ($datosNotificacion = mysqli_fetch_assoc($resultado_notificacion)) {
            $fecha=$datosNotificacion['fecha'];
            $hora=$datosNotificacion['hora'];
            $idNotificacion=$datosNotificacion['id_notificacion'];

            echo  
                '
                <div class="col-2 d-flex align-items-center justify-content-center">
                    <p>'.$numNot.'</p>
                </div>
                <div class="col-8 d-flex align-items-center justify-content-center">
                    <div>Fecha Notificacion: ' . $fecha . ' <br>Hora Notificacion: ' . $hora . '</div>
                </div>
                <div class="col-2">
                    <button id="btn" type="button" class="btn material-symbols-outlined" title="Eliminar Notificación" onClick="eliminarNotificacion('.$idNotificacion.', '.$id_materia.', '.$tarea.'  )">delete_forever</button>
                </div>
                ';


            $numNot++;
        }

        if ($numNot<5) {

            echo
            '
            <div class="col-12 pt-3">
                <div id="seccionBoton" class="col-12 pt-2" style="display: none">
                    <div class="col-2">
                        <button type="button" class="btn material-symbols-outlined" title="Agregar Notificacion" onClick="agregarNotificacion('.$tarea.', '.$id_materia.')">library_add</button>
                    </div>
                </div>
                <button class="btn mt-2" id="btnNotificacionSeccion" type="button" onclick="crearNot(4, \'seccionBoton\')" class="col=4" >Nueva Notificacion</button>
            </div>


            ';
        }

        echo
        '
        </div>
        ';


    }

    else{
        echo "error";
    }
}

function eliminarNotificacion($conexion){
    session_start();

    $id_notificacion = $_POST['id_notificacion'];

    $sql = "DELETE FROM notificacion WHERE id_notificacion = '$id_notificacion';";
            
    if(($result = $conexion->query($sql)) === FALSE) {
        echo "Error: " . $sql . "<br>" . $conexion->error;
    }
    
    mostrarNotificaciones($conexion);
}

function crearNotificacion($conexion){

    $fechaNotif=$_POST["dia"];
    $horaNotif=$_POST["hora"];
    $tarea=$_POST["id_tarea"];
    $materia=$_POST["id_materia"];
    
    $consultaTarea = "SELECT id_usuario, id_semestre FROM tarea WHERE id_tarea= '$tarea'";
    $resultado_tarea = $conexion->query($consultaTarea);
    $fila_tarea = $resultado_tarea->fetch_assoc();
    $usuario=$fila_tarea['id_usuario'];
    $semestre=$fila_tarea['id_semestre'];

    $sqlNot="INSERT INTO notificacion (id_usuario, id_semestre, id_materia, id_tarea, fecha, hora) 
   VALUES ('$usuario', '$semestre', '$materia', '$tarea', '$fechaNotif', '$horaNotif')";
   
   if(($result = $conexion->query($sqlNot)) === FALSE) {
       echo "Error: " . $sqlNot . "<br>" . $conexion->error;

   }

   $toastmensaje = toast(TRUE, "Notificacion Creada Correctamente");
   $_SESSION["toastmesaje"] = $toastmensaje;
   
   mostrarNotificaciones($conexion);
}
?>
