<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Inicia la sesión solo si no está activa
}
    ob_start();
    include "conexiondb.php";
    include_once "toast.php";
    
    if (isset($_POST['tipo'])) {
        $tipo = $_POST['tipo'];
    }
    else {
        $tipo = 0;
    }

    switch ($tipo) {
        case '1':
            creaTarea($conexion);
            break;
        case '2':
            eliminarTarea($conexion);
            break;
        case '3':
            modificarTarea($conexion);
            break; 
        case '4':
            loadTarea($conexion);
            break;
        case '5':
            update($conexion);
            break;
        case '6':
            cambioCI($conexion); //Cambiar estado de completa incompleta
            break;
        case '0':
            break;   
        default:
            header('location: principal.php');
            break;
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

    function creaTarea($conexion) {
        session_start();
        // Obtener el id_usuario de la sesión
        $id_usuario = $_SESSION["id_usuario"];
        // Obtener los valores de los campos del formulario
        $id_materia = $_POST["id_materia"];
        $nombre_tarea = $_POST["nombre_tarea"];
        $observacion = $_POST["observacion"];
        $prioridad = $_POST["prioridad"];
        $horaTarea=$_POST["horaTarea"];
        $fechaTarea=$_POST["fechaTarea"];
        // Obtener el id_semestre desde la base de datos
        $consulta = "SELECT id_semestre FROM materia WHERE id_materia = '$id_materia'";
        $resultado_materia = $conexion->query($consulta);
        $fila_materia = $resultado_materia->fetch_assoc();
        $id_semestre = $fila_materia['id_semestre'];
        //Crea la tarea compartida
        $opc = $_POST['compartir'];
        if ($opc==="1"){
            $id_amigo = $_POST['amigos']; 
            //Insertar en la tabla de compartir
            $sql = "INSERT INTO compartir(id_usuario, id_amigo, compartir) VALUES
                ('$id_usuario', '$id_amigo', 0)";
            if(($result = $conexion->query($sql)) === FALSE) {
                echo "Error: " . $sql . "<br>" . $conexion->error;
            }
            //Insertar en tabla de tareas para el usuario que crea tarea
            $id_compartir = mysqli_insert_id($conexion);
            $sqltarea = "INSERT INTO tarea(id_usuario, id_semestre, id_materia, nombre_tarea, fecha_inicio, hora_inicio, prioridad, observacion, completada, id_compartir) VALUES
            ('$id_usuario', '$id_semestre', '$id_materia', '$nombre_tarea', '$fechaTarea', '$horaTarea', '$prioridad', '$observacion', 0, '$id_compartir')";
            if(($result = $conexion->query($sqltarea)) === FALSE) {
                echo "Error: " . $sqltarea . "<br>" . $conexion->error;
            }

            $id_tarea = mysqli_insert_id($conexion);

            /* registra notificacion */
            if($_POST["fechaNotif0"])
            {
                $contador=0;
                agregarNotificacion($contador, $id_usuario, $id_semestre, $id_materia, $id_tarea, $conexion);
                
            }

            //Crear la notificacion para el segundo usuario
            $not = "INSERT INTO notificacionat(id_usuario, id_amigo, tipo, leido, fecha) VALUES
                ('$id_usuario', '$id_amigo','solicitud de tarea', 0, now())";
            if(($result = $conexion->query($not)) === FALSE) {
                echo "Error: " . $not . "<br>" . $conexion->error;}
            
            $toastmensaje = toast(TRUE, "Solicitud de tarea enviada");
            $_SESSION["toastmesaje"] = $toastmensaje;
            header('location: compartida.php');
        }
        //Crea la tarea sin compartir
        else{
            // Preparar la consulta SQL
            $sql = "INSERT INTO tarea (id_usuario, id_semestre, id_materia, nombre_tarea, fecha_inicio, hora_inicio, prioridad, observacion, completada, id_compartir) 
                    VALUES ('$id_usuario', '$id_semestre', '$id_materia', '$nombre_tarea', '$fechaTarea', '$horaTarea', '$prioridad', '$observacion',0, 0)";
                    
            if(($result = $conexion->query($sql)) === FALSE) {
                echo "Error: " . $sql . "<br>" . $conexion->error;
            }

            $id_tarea = mysqli_insert_id($conexion); //Obtiene el ID de la consulta anterior (INSERT)

            /* registra notificacion */
            if($_POST["fechaNotif0"])
            {
                $contador=0;
                agregarNotificacion($contador, $id_usuario, $id_semestre, $id_materia, $id_tarea, $conexion);
                
            }
            
            $toastmensaje = toast(TRUE, "Tarea Creada Correctamente");
            $_SESSION["toastmesaje"] = $toastmensaje;

            header('location: tareadetalle.php');}
    } 

     function agregarNotificacion($i, $usuario, $semestre, $materia, $tarea, $conexion){
         $fechaNotif = $_POST["fechaNotif".$i];
         $horaNotif = $_POST["horaNotif".$i];
         
         
         $sqlNot="INSERT INTO notificacion (id_usuario, id_semestre, id_materia, id_tarea, fecha, hora) 
        VALUES ('$usuario', '$semestre', '$materia', '$tarea', '$fechaNotif', '$horaNotif')";
        echo $horaNotif;
        
        if(($result = $conexion->query($sqlNot)) === FALSE) {
            echo "Error: " . $sqlNot . "<br>" . $conexion->error;

        }
       $i=$i+1;

       if($_POST["fechaNotif".$i]){
            agregarNotificacion($i, $usuario, $semestre, $materia, $tarea, $conexion);
       }

    }
    
    function eliminarTarea($conexion){
        session_start();
        $id_tarea = $_POST['id_tarea'];

        $sql = "DELETE FROM tarea WHERE id_tarea = '$id_tarea';";
                
        if(($result = $conexion->query($sql)) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conexion->error;
        }
        
        $toastmensaje = toast(TRUE, "Tarea Eliminada Correctamente");
        $_SESSION["toastmesaje"] = $toastmensaje;
        
        header('location: tareadetalle.php');
    }
    
    function modificarTarea($conexion){
        $idTarea = $_POST['id_tarea'];

        $sql = "SELECT nombre_tarea, prioridad, observacion FROM tarea
        WHERE id_tarea = '$idTarea';";
                
        if(($result = $conexion->query($sql)) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conexion->error;
        }

        $result = mysqli_query($conexion, $sql);
        $allnotas = mysqli_fetch_assoc($result);

        $name = $allnotas["nombre_tarea"];
        $des = $allnotas["observacion"];
        $pri = $allnotas["prioridad"];
        
        $prio = prio($pri);

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
                        <form action="tarea.php" method="post">
                            <div class="card-header row">
                                <div class="col-6"><img src="img/Icon v2.png" alt="Logo" class="logoig"></div>
                                <div class="col-6 pt-2 text-end"><input type="submit" value="Modificar Tarea" class="btn" id="btn1"></div>                   
                            </div>
                            <div class="card-body row">
                                <div class="col-md-6 mx-auto">
                                <div class="container">
                                    <label for="nombre_tarea" class="fw-bold col-8 form-label">Nombre Tarea:</label>
                                    <input type="text" name="nombre_tarea" value="'. $name .'" class="col-4 form-control" maxlength="50" pattern="^(?=.*[a-zA-Z0-9])(?! )[\sa-zA-ZáéíóúÁÉÍÓÚñÑ0-9!@#$%^&*()_+\-=[\]{};:\\|,.<>/?]{1,50}$"
                                    title="Letras mayúsculas, minúsculas, números, carácteres especiales y espacios permitidos" required>
                                    <div class="pt-4"></div>
                                    <label for="observacion" class="fw-bold col-8 form-label">Descripción Tarea:</label>
                                    <textarea name="observacion" class="col-4 form-control" style="height:100px;" maxlength="100" pattern="^(?=.*[a-zA-Z0-9])(?! )[\sa-zA-ZáéíóúÁÉÍÓÚñÑ0-9!@#$%^&*()_+\-=[\]{};:\\|,.<>/?]{1,100}$" required>'. $des .'</textarea>
                                    <label for="prioridad" class="fw-bold col-8 form-label mt-2">Prioridad Actual: '. $prio .' <br><br>Nueva:</label>
                                    <select class="form-select" name="prioridad" id="prioridad">
                                        <option value="1">Baja</option>
                                        <option value="2">Media</option>
                                        <option value="3">Alta</option>
                                    </select>
                                    <input type="hidden" value="5" name="tipo">
                                    <input type="hidden" value="'. $idTarea .'" name="id_tarea">
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
        
    function update($conexion) { //------ $prio = prio($pri);
        session_start();
        $idTarea = $_POST['id_tarea'];
        $newNombre = $_POST['nombre_tarea'];
        $newDes = $_POST['observacion'];
        $prio = $_POST['prioridad'];

        $sql = "UPDATE tarea 
        SET nombre_tarea  = '$newNombre', observacion = '$newDes', prioridad = '$prio'
        WHERE id_tarea  = '$idTarea ';";
                
        if(($result = $conexion->query($sql)) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conexion->error;
        } 
        
        $toastmensaje = toast(TRUE, "Tarea Modificada Correctamente");
        $_SESSION["toastmesaje"] = $toastmensaje;
        
        header('location: tareadetalle.php');
    }

    function loadTarea($conexion) { 
        if(isset($_POST['materia']) != TRUE)
        {
            $id_usuario = $_SESSION["id_usuario"];
        
            $sql = "SELECT MAX(id_semestre) AS id_semestre FROM semestre
            WHERE id_usuario = '$id_usuario';";
    
            if(($result = $conexion->query($sql)) === FALSE) {
                echo "Error: " . $sql . "<br>" . $conexion->error;
            }
    
            $result = mysqli_query($conexion, $sql);
            $sem = mysqli_fetch_assoc($result);
    
            $id_sem = $sem["id_semestre"];
    
            // Aquí se asegura que las tareas que se consultan están asociadas al mismo usuario
            $sql = "SELECT id_tarea, id_materia, nombre_tarea, DATE_FORMAT(fecha_inicio, '%d-%m-%Y') AS fecha_inicio, hora_inicio, prioridad, observacion, completada 
                    FROM tarea
                    WHERE id_semestre = '$id_sem' 
                    AND id_compartir = 0
                    AND id_usuario = '$id_usuario'  -- Se añade el filtro por id_usuario
                    ORDER BY fecha_inicio DESC, hora_inicio DESC;";
    
            if(($result = $conexion->query($sql)) === FALSE) {
                echo "Error: " . $sql . "<br>" . $conexion->error;
            }
        } 
        else if($_POST['materia'] >= 19) // Por materias creadas
        {
            $idMateria = $_POST['materia'];
            $id_usuario = $_SESSION["id_usuario"]; // Obtener el id_usuario nuevamente para la consulta
            $sql = "SELECT id_tarea, id_materia, nombre_tarea, DATE_FORMAT(fecha_inicio, '%d-%m-%Y') AS fecha_inicio, hora_inicio, prioridad, observacion, completada 
                    FROM tarea
                    WHERE id_materia = '$idMateria' 
                    AND id_compartir = 0
                    AND id_usuario = '$id_usuario'  -- Se añade el filtro por id_usuario
                    ORDER BY fecha_inicio DESC, hora_inicio DESC;";
    
            if(($result = $conexion->query($sql)) === FALSE) {
                echo "Error: " . $sql . "<br>" . $conexion->error;
            }
        }
        else if($_POST['materia'] == 1) // Por completadas
        {
            $id_usuario = $_SESSION["id_usuario"]; // Obtener el id_usuario nuevamente para la consulta
            $sql = "SELECT id_tarea, id_materia, nombre_tarea, DATE_FORMAT(fecha_inicio, '%d-%m-%Y') AS fecha_inicio, hora_inicio, prioridad, observacion, completada 
                    FROM tarea
                    WHERE completada = 1 
                    AND id_compartir = 0
                    AND id_usuario = '$id_usuario'  -- Se añade el filtro por id_usuario
                    ORDER BY fecha_inicio DESC, hora_inicio DESC;";
    
            if(($result = $conexion->query($sql)) === FALSE) {
                echo "Error: " . $sql . "<br>" . $conexion->error;
            }
        }
        else if($_POST['materia'] == 2) // Por incompletas
        {
            $id_usuario = $_SESSION["id_usuario"]; // Obtener el id_usuario nuevamente para la consulta
            $sql = "SELECT id_tarea, id_materia, nombre_tarea, DATE_FORMAT(fecha_inicio, '%d-%m-%Y') AS fecha_inicio, hora_inicio, prioridad, observacion, completada 
                    FROM tarea
                    WHERE completada = 0 
                    AND id_compartir = 0
                    AND id_usuario = '$id_usuario'  -- Se añade el filtro por id_usuario
                    ORDER BY fecha_inicio DESC, hora_inicio DESC;";
    
            if(($result = $conexion->query($sql)) === FALSE) {
                echo "Error: " . $sql . "<br>" . $conexion->error;
            }
        }
    
        $result = mysqli_query($conexion, $sql); 
    
        while ($tarea = mysqli_fetch_assoc($result)) {
            if($tarea["completada"] == 1) {$tar = "Completada";} else {$tar = "No Completada";}
            
            $idMat = $tarea["id_materia"];
    
            $sql1 = "SELECT nombre_materia, nombre_profesor FROM materia
            WHERE id_materia = '$idMat'; ";
                    
            if(($result1 = $conexion->query($sql1)) === FALSE) {
                echo "Error: " . $sql1 . "<br>" . $conexion->error;
            }
    
            $result1 = mysqli_query($conexion, $sql1); 
            $mat = mysqli_fetch_assoc($result1);
    
            echo ' 
            <div class="card mb-3 mt-3"> 
                <div class="card-body row">
                    <div class="col-6 mb-3 text-start d-flex align-items-center">'. $mat["nombre_materia"] .' - '. $mat["nombre_profesor"] .'</div>
                    <div class="col-6 mb-3 d-flex align-items-center justify-content-end"><form id="completo" action="tarea.php" method="post" style="margin-bottom: 0px;"><input type="submit" value="'. $tar .'" class="btn" id="btn"><input type="hidden" value="'. $tarea["id_tarea"] .'" name="id_tarea"><input type="hidden" value="6" name="tipo"></form></div>
                    <hr>
    
                    <div class="col-7">    
                        <div class="text-start">' . $tarea["nombre_tarea"] . '</div>
                    </div>
                    <div class="col-5 text-start d-flex align-items-center">
                        <div>Fecha inicio: ' . $tarea["fecha_inicio"] . ' <br>Hora Inicio: ' . $tarea["hora_inicio"] . '</div>
                    </div>
                    <div class="mt-2 col-7 text-start">
                        <div>' . $tarea["observacion"] . '</div>
                    </div>
                    <div class="mt-2 mb-3 col-5 text-star d-flex align-items-center">
                        <div>Prioridad: ' . prio($tarea["prioridad"]) . '</div>
                    </div>
                </div>
                <div class="card-footer bg-light text-muted">
                    <div class="row"> 
                        <div class="col-3 text-start">
                            <form id="formD" action="tarea.php" method="post" style="margin-bottom: 0px;">
                                <input type="hidden" value="2" name="tipo">
                                <input type="hidden" value="'. $tarea["id_tarea"] .'" name="id_tarea">
                                <button type="submit" class="btn material-symbols-outlined" id="btn" title="Eiminar Tarea">delete_forever</button>
                            </form>    
                        </div>
                        <div class="col-2 text-start">
                            <form id="formE" action="tarea.php" method="post" style="margin-bottom: 0px;">
                                <input type="hidden" value="3" name="tipo">
                                <input type="hidden" value="'. $tarea["id_tarea"] .'" name="id_tarea">
                                <button type="submit" class="btn material-symbols-outlined" id="btn" title="Editar Tarea">edit</button>
                            </form>
                        </div>
                        <div class="col-2 text-center">
                            <a href="#vernotifica" data-bs-toggle="modal">
                                <button type="button" class="btn material-symbols-outlined" id="btn" title="Ver Notificaciones" onClick="modalNotificaciones('. $tarea["id_tarea"] .')">notifications_active</button>
                            </a>
                        </div>
                        <div class="col-2 text-end">
                            <form id="'. $tarea["id_tarea"] .'" style="margin-bottom: 0px;">
                                <input type="hidden" value="4" id="tipo">
                                <input type="hidden" value="'. $tarea["id_tarea"] .'" name="tarea">
                                <button type="submit" class="btn material-symbols-outlined" id="btn" title="Ver Subtareas">library_add</button>
                            </form>
                            
                        </div>
                        <div class="col-3 text-end">
                            <a href="estudio.php" class="btn material-symbols-outlined" id="btn" title="Abrir Sesión de Estudios">spa</a>
                        </div>
                    </div>
                </div>
            </div>';
        }
    }
    

    function cambioCI($conexion) {
        session_start();
        $id_tarea = $_POST["id_tarea"];

        $sql = "SELECT id_compartir FROM tarea WHERE id_tarea = '$id_tarea'";
    
        if(($result1 = $conexion->query($sql)) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conexion->error;
        }
        $result1 = mysqli_query($conexion, $sql);
        $tarea = mysqli_fetch_assoc($result1);
        $esN = $tarea["id_compartir"];

        $sql = "SELECT completada FROM tarea
            WHERE id_tarea = '$id_tarea';";

        $result = mysqli_query($conexion, $sql);
        $es = mysqli_fetch_assoc($result);

        if($es["completada"] == 0) { //No completa cambia a completa
            $sql1 = "UPDATE tarea
                SET completada = 1
                WHERE id_tarea = '$id_tarea';";

            if(($result1 = $conexion->query($sql1)) === FALSE) {
                echo "Error: " . $sql1 . "<br>" . $conexion->error;
            }

            $sql = "UPDATE subtarea
            SET completada = 1
            WHERE id_tarea = '$id_tarea';"; // Como tarea se marca completada, sus subtareas también se marcan completadas
            
            if(($result = $conexion->query($sql)) === FALSE) {
              echo "Error: " . $sql . "<br>" . $conexion->error;
            }
        }
        else { //Completa cambia a incompleta
            $sql1 = "UPDATE tarea
                SET completada = 0
                WHERE id_tarea = '$id_tarea';";
                        
            if(($result1 = $conexion->query($sql1)) === FALSE) {
                    echo "Error: " . $sql1 . "<br>" . $conexion->error;
            }
        }

        $toastmensaje = toast(TRUE, "Estado actualizado");
        $_SESSION["toastmesaje"] = $toastmensaje;

        if($esN == 0) {
            header('location: tareadetalle.php');
          }
          else {
            header('location: compartida.php');
          }
    }
?>