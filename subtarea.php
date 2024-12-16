<?php 
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
          creaSub($conexion);
          break;
      case '2':
          eliminarSub($conexion);
          break;
      case '3':
          modificarSub($conexion);
          break; 
      case '4':
          loadSub($conexion);
          break;
      case '5':
          updateSub($conexion);
          break;
      case '6':
          pasIdTar($conexion); //funcion para pasar el id actualizado de la tarea
          break;
      case '7':
          cambioCO($conexion);
          break;
      case '0':
          break;   
      default:
          header('location: tareadetalle.php');
          break;
  }

  function creaSub($conexion) {
    session_start();
    
    // Obtener los valores de los campos del formulario: Nombre y Descripción
    $id_tarea = $_POST["id_tar"];
    $nombre_subtarea = $_POST["nombreSubtarea"];
    $descripcion_subtarea = $_POST["descrSubtarea"];
    $esN = $_POST["esN"];
    
    $sql = "SELECT id_usuario, id_semestre, id_materia
            FROM tarea WHERE id_tarea = '$id_tarea'";
    
    if(($result = $conexion->query($sql)) === FALSE) {
      echo "Error: " . $sql . "<br>" . $conexion->error;
    }

    $result = mysqli_query($conexion, $sql);
    $tarea = mysqli_fetch_assoc($result);

    $id_usuario = $tarea["id_usuario"];
    $id_semestre = $tarea["id_semestre"];
    $id_materia = $tarea["id_materia"];

    $sql = "INSERT INTO subtarea(id_usuario, id_semestre, id_materia, id_tarea, nombre_subtarea, descripcion_subtarea, fecha_inicio, hora_inicio, completada) 
            VALUES ('$id_usuario','$id_semestre','$id_materia','$id_tarea','$nombre_subtarea','$descripcion_subtarea',CURDATE(),CURTIME(), 0)"; 

    if(($result = $conexion->query($sql)) === FALSE) {
      echo "Error: " . $sql . "<br>" . $conexion->error;
    }

    if($esN == 0) {
      $toastmensaje = toast(TRUE, "Subtarea Creada Correctamente");
      $_SESSION["toastmesaje"] = $toastmensaje;
      header('location: tareadetalle.php');
    }
    else {
      $toastmensaje = toast(TRUE, "Subtarea Compartida Creada Correctamente");
      $_SESSION["toastmesaje"] = $toastmensaje;
      header('location: compartida.php#resultados');
    }
  }

  function eliminarSub($conexion){
    session_start();

    $id_subtarea = $_POST['id_subtarea'];

    $sql = "SELECT id_compartir FROM tarea WHERE id_tarea IN (SELECT id_tarea FROM subtarea WHERE id_subtarea = '$id_subtarea')";
    
    if(($result1 = $conexion->query($sql)) === FALSE) {
      echo "Error: " . $sql . "<br>" . $conexion->error;
    }

    $result1 = mysqli_query($conexion, $sql);
    $tarea = mysqli_fetch_assoc($result1);
    $esN = $tarea["id_compartir"];

    $sql = "DELETE FROM subtarea WHERE id_subtarea = '$id_subtarea';";
            
    if(($result = $conexion->query($sql)) === FALSE) {
        echo "Error: " . $sql . "<br>" . $conexion->error;
    }
    
    if($esN == 0) {
      $toastmensaje = toast(TRUE, "Subtarea Eliminada Correctamente");
      $_SESSION["toastmesaje"] = $toastmensaje;

      header('location: tareadetalle.php');
    }
    else {
      $toastmensaje = toast(TRUE, "Subtarea Compartida Eliminada Correctamente");
      $_SESSION["toastmesaje"] = $toastmensaje;

      header('location: compartida.php');
    }
  }

  function modificarSub($conexion){
    $id_subtarea = $_POST['id_subtarea'];

    $sql = "SELECT nombre_subtarea, descripcion_subtarea FROM subtarea
        WHERE id_subtarea = '$id_subtarea';";
                
        if(($result = $conexion->query($sql)) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conexion->error;
        }

        $result = mysqli_query($conexion, $sql);
        $allnotas = mysqli_fetch_assoc($result);

        $name = $allnotas["nombre_subtarea"];
        $des = $allnotas["descripcion_subtarea"];
        
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
                        <form action="subtarea.php" method="post">
                            <div class="card-header row">
                                <div class="col-6"><img src="img/Icon v2.png" alt="Logo" class="logoig"></div>
                                <div class="col-6 pt-2 text-end"><input type="submit" value="Modificar Subtarea" class="btn" id="btn1"></div>                   
                            </div>
                            <div class="card-body row">
                                <div class="col-md-6 mx-auto">
                                <div class="container">
                                    <label for="nombre_subtarea" class="fw-bold col-8 form-label">Nombre Subtarea:</label>
                                    <input type="text" name="nombre_subtarea" value="'. $name .'" class="col-4 form-control" maxlength="50" pattern="^(?=.*[a-zA-Z0-9])(?! )[\sa-zA-ZáéíóúÁÉÍÓÚñÑ0-9!@#$%^&*()_+\-=[\]{};:\\|,.<>/?]{1,50}$"
                                    title="Letras mayúsculas, minúsculas, números, carácteres especiales y espacios permitidos" required>
                                    <div class="pt-4"></div>
                                    <label for="des" class="fw-bold col-8 form-label">Descripción Subtarea:</label>
                                    <textarea name="des" class="col-4 form-control" style="height:100px;" maxlength="100" pattern="^(?=.*[a-zA-Z0-9])(?! )[\sa-zA-ZáéíóúÁÉÍÓÚñÑ0-9!@#$%^&*()_+\-=[\]{};:\\|,.<>/?]{1,100}$" required>'. $des .'</textarea>
                                    <input type="hidden" value="5" name="tipo">
                                    <input type="hidden" value="'. $id_subtarea .'" name="id_subtarea">
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

    function updateSub($conexion) {
      session_start();

      $id_subtarea = $_POST['id_subtarea'];
      $newNombre = $_POST['nombre_subtarea'];
      $newDes = $_POST['des'];

      $sql = "SELECT id_compartir FROM tarea WHERE id_tarea IN (SELECT id_tarea FROM subtarea WHERE id_subtarea = '$id_subtarea')";
    
      if(($result1 = $conexion->query($sql)) === FALSE) {
        echo "Error: " . $sql . "<br>" . $conexion->error;
      }

      $result1 = mysqli_query($conexion, $sql);
      $tarea = mysqli_fetch_assoc($result1);
      $esN = $tarea["id_compartir"];

      $sql = "UPDATE subtarea 
        SET nombre_subtarea  = '$newNombre', descripcion_subtarea = '$newDes'
        WHERE id_subtarea  = '$id_subtarea';";
              
      if(($result = $conexion->query($sql)) === FALSE) {
          echo "Error: " . $sql . "<br>" . $conexion->error;
      }

      if($esN == 0) {
        $toastmensaje = toast(TRUE, "Subtarea Modificada Correctamente");
        $_SESSION["toastmesaje"] = $toastmensaje;
  
        header('location: tareadetalle.php');
      }
      else {
        $toastmensaje = toast(TRUE, "Subtarea Compartida Modificada Correctamente");
        $_SESSION["toastmesaje"] = $toastmensaje;
  
        header('location: compartida.php');
      }
  }

  function loadSub($conexion) {
    session_start();
      $id_tarea = $_POST['tarea'];

      if (isset($_SESSION['idTarea'])) {
        unset($_SESSION['idTarea']);
      }
      
      $_SESSION['idTarea'] = $id_tarea;
      
      $sql = "SELECT id_materia, nombre_tarea, prioridad, completada FROM tarea
            WHERE id_tarea = '$id_tarea';";
                    
      if(($result = $conexion->query($sql)) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conexion->error;
      }

      $result = mysqli_query($conexion, $sql);
      $tarea = mysqli_fetch_assoc($result);

      if($tarea["completada"] == 1) {$tar = "Completada";} else {$tar = "No Completada";}
      unset($_POST['tipo']);
      require "tarea.php";
      $prio = prio($tarea["prioridad"]);

      echo '
      <div class="card-header bg-white mb-3">
        <div class="row">
          <div class="col-6 text-start">
            Subtareas
          </div>
          <div class="col-6 text-end" >
              <a href="#creasubtarea" data-bs-toggle="modal" class="btn material-symbols-outlined" id="btn">add</a>
          </div>
        </div>
      </div>

      <div class="card-body bg-white mb-3">
        <div class="row">
          <div class="col-10 text-start pb-4">
              <div>'. $tarea["nombre_tarea"] .' - '. $tar .' - Prioridad '. $prio .'</div>
          </div>
          <div class="col-2 text-end">
              <a href="#vernotifica" data-bs-toggle="modal" class="btn material-symbols-outlined" id="btn" title="Ver Notificaciones" onClick="modalNotificaciones('. $id_tarea .')">notifications_active</a>
          </div>';


      $sql1 = "SELECT id_subtarea, nombre_subtarea, descripcion_subtarea, DATE_FORMAT(fecha_inicio, '%d-%m-%Y') AS fecha_inicio, hora_inicio, completada FROM subtarea
            WHERE id_tarea = '$id_tarea'
            ORDER BY fecha_inicio DESC, hora_inicio DESC;";
                    
      if(($result1 = $conexion->query($sql1)) === FALSE) {
            echo "Error: " . $sql1 . "<br>" . $conexion->error;
      }

      $result1 = mysqli_query($conexion, $sql1);

      while ($subtarea = mysqli_fetch_assoc($result1)) {
        if($subtarea["completada"] == 1) {$tar = "Completada";} else {$tar = "No Completada";}
        
        echo '
          <div class="card mt-3">
            <div class="row">
              <div class="col-6 text-start d-flex align-items-center">'. $subtarea["nombre_subtarea"] .'</div>
              <div class="col-6 text-end d-flex align-items-center justify-content-end"><form id="completo1" action="subtarea.php" method="post" style="margin-bottom: 0px;"><input type="submit" value="'. $tar .'" class="btn" id="btn"><input type="hidden" value="'. $subtarea["id_subtarea"] .'" name="id_subtarea"><input type="hidden" value="7" name="tipo"></form></div>
              <hr>

              <div class="col-12 mb-4">    
                  <div class="text-center">' . $subtarea["descripcion_subtarea"] . '</div>
              </div>
              <div class="col-6 text-start d-flex align-items-center justify-content-center">
                  <div>Fecha inicio: ' . $subtarea["fecha_inicio"] . '</div>
              </div>
              <div class="col-6 text-center d-flex align-items-center justify-content-center">
                  <div>Hora incio: ' . $subtarea["hora_inicio"] . '</div>
              </div>

              <div class="col-6 text-center mt-3 mb-2">
                <form id="formL" action="subtarea.php" method="post" style="margin-bottom: 0px;">
                    <input type="hidden" value="2" name="tipo">
                    <input type="hidden" value="'. $subtarea["id_subtarea"] .'" name="id_subtarea">
                    <button type="submit" class="btn material-symbols-outlined" id="btn" title="Eiminar Subtarea">delete_forever</button>
                </form>
              </div>
              <div class="col-6 text-center mt-3 mb-2">
                <form id="formM" action="subtarea.php" method="post" style="margin-bottom: 0px;">
                    <input type="hidden" value="3" name="tipo">
                    <input type="hidden" value="'. $subtarea["id_subtarea"] .'" name="id_subtarea">
                    <button type="submit" class="btn material-symbols-outlined" id="btn" title="Editar Subtarea">edit</button>
                </form>
              </div>
            </div>
          </div>';
      }

      echo '</div>
      </div>';
  }

  function pasIdTar($conexion) {
    session_start();
    $id_tarea = $_SESSION['idTarea'];
    
    $quer = "SELECT id_compartir FROM tarea
        WHERE id_tarea = '$id_tarea';";
                    
    if(($result1 = $conexion->query($quer)) === FALSE) {
        echo "Error: " . $quer . "<br>" . $conexion->error;
    }
    $result1 = mysqli_query($conexion, $quer);
    $es = mysqli_fetch_assoc($result1);
    $compartir = $es["id_compartir"];

    // Enviar la variable de sesión actualizada como respuesta AJAX
    echo json_encode(array('id_tarea' => $id_tarea, 'compartir' => $compartir));
  }

  function cambioCO($conexion) {
    session_start();
    $id_subtarea = $_POST["id_subtarea"];

    $sql = "SELECT completada FROM subtarea
        WHERE id_subtarea = '$id_subtarea';";
                    
    if(($result = $conexion->query($sql)) === FALSE) {
        echo "Error: " . $sql . "<br>" . $conexion->error;
    }

    $result = mysqli_query($conexion, $sql);
    $es = mysqli_fetch_assoc($result);

    if($es["completada"] == 0) { //No completa cambia a completa
        $sql1 = "UPDATE subtarea
              SET completada = 1
              WHERE id_subtarea = '$id_subtarea';";
                        
        if(($result1 = $conexion->query($sql1)) === FALSE) {
              echo "Error: " . $sql1 . "<br>" . $conexion->error;
        }
    }
    else { //Completa cambia a incompleta
        $sql1 = "UPDATE subtarea
            SET completada = 0
            WHERE id_subtarea = '$id_subtarea';";
                        
        if(($result1 = $conexion->query($sql1)) === FALSE) {
              echo "Error: " . $sql1 . "<br>" . $conexion->error;
        }
    }

    $toastmensaje = toast(TRUE, "Estado actualizado");
    $_SESSION["toastmesaje"] = $toastmensaje;

    header('Location: tareadetalle.php');
  }
?>