<?php
include "conexiondb.php";

$id_usuario = $_SESSION["id_usuario"];

$sql = "SELECT notificacion.id_tarea, fecha, nombre_tarea, id_compartir 
        FROM notificacion 
        JOIN tarea ON notificacion.id_tarea = tarea.id_tarea
        WHERE notificacion.id_usuario = '$id_usuario' AND tarea.completada = 0;";

$sql1 = "SELECT nombre_tarea, fecha_inicio, id_compartir 
        FROM tarea
        WHERE id_usuario = '$id_usuario' AND completada = 0;";


// Realizar la consulta a la base de datos para obtener las notificaciones
$resultado = mysqli_query($conexion, $sql);
$resultado2 = mysqli_query($conexion, $sql1);

$eventos_js = "";

// Verificar si se obtuvieron resultados
if ($resultado->num_rows > 0) {
    // Array para almacenar las notificaciones
    $notificaciones = array();

    // Recorrer los resultados y agregar las notificaciones al array
    while ($fila = $resultado->fetch_assoc()) {
        $noti = array(
            "nombre_tarea" => $fila["nombre_tarea"],
            "fecha" => $fila["fecha"],
            "compartida" => $fila["id_compartir"]
        );
        $notificaciones[] = $noti;
    }
    

    // Generar el código JavaScript para los eventos del calendario
    
    foreach ($notificaciones as $noti) {
        $nombre_tarea = $noti["nombre_tarea"];
        $fecha = $noti["fecha"];
        $com = $noti["compartida"];
        if($com == 0){$link = 'tareadetalle.php';} else {$link = 'compartida.php';}
        
        $evento = "{ title: 'Notificación: $nombre_tarea',
                     start: '$fecha',
                     color: '#8D99AE',
                     url: '$link' },";
        $eventos_js .= $evento;
    }
}

if($resultado2->num_rows > 0) {
    $tareas = array();

    while ($fila = $resultado2->fetch_assoc()) {
        $tar = array(
            "nombre_tarea" => $fila["nombre_tarea"],
            "fecha_inicio" => $fila["fecha_inicio"],
            "compartida" => $fila["id_compartir"]
        );
        $tareas[] = $tar;
    }
    

    // Generar el código JavaScript para los eventos del calendario

    foreach ($tareas as $tar) {
        $nombre_tarea = $tar["nombre_tarea"];
        $fecha = $tar["fecha_inicio"];
        $com = $tar["compartida"];
        if($com == 0){$link = 'tareadetalle.php';} else {$link = 'compartida.php';}
        
        $evento = "{ title: 'Tarea: $nombre_tarea',
                     start: '$fecha',
                     color: '#2B2D42',
                     url: '$link' },";
        $eventos_js .= $evento;
    }
}

if($eventos_js !== '') {
    // Imprimir el código JavaScript
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    events: [$eventos_js]
                });
                calendar.render();
            });
        </script>";
}

?>