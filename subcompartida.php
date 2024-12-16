<?php
  ob_start();
  include "conexiondb.php";
  include_once "toast.php";
  $tipo = $_POST['tipo'];

  switch ($tipo) {
      case '5':
          loadSubconmigo($conexion);
          break;
      case '0':
          break;   
      default:
          header('location: tareadetalle.php');
          break;
  }

  function loadSubConmigo($conexion) {
    session_start();
      $id_tarea = $_POST['tarea'];

      if (isset($_SESSION['idTarea'])) {
        unset($_SESSION['idTarea']);
      }
      
      $_SESSION['idTarea'] = $id_tarea;
      
      $sql = "SELECT id_materia, nombre_tarea, prioridad, completada, id_compartir FROM tarea
            WHERE id_tarea = '$id_tarea';";
                    
      if(($result = $conexion->query($sql)) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conexion->error;
      }

      $result = mysqli_query($conexion, $sql);
      $tarea = mysqli_fetch_assoc($result);
      $id_compartir = $tarea["id_compartir"];
      if($tarea["completada"] == 1) {$tar = "Completada";} else {$tar = "No Completada";}
      unset($_POST['tipo']);
      require "tarea.php";
      $prio = prio($tarea["prioridad"]);

      echo '
      <div class="card-header bg-white mb-3">
        <div class="row">
          <div class="col-6 text-start d-flex align-items-center">
            Subtareas
          </div>
        </div>
      </div>

      <div class="card-body bg-white mb-3">
        <div class="row">
          <div class="col-10 text-start pb-4">
              <div>'. $tarea["nombre_tarea"] .' - '. $tar .' - Prioridad '. $prio .'</div>
          </div>
          <div class="col-2 text-center">
              <a href="#vernotifica" data-bs-toggle="modal" class="btn material-symbols-outlined" id="btn" title="Ver Notificaciones">notifications_active</a>
          </div>';


      $sql1 = "SELECT subt.* FROM subtarea AS subt
              JOIN tarea AS tar ON subt.id_tarea = tar.id_tarea
              JOIN compartir AS comp ON tar.id_compartir = comp.id_compartir
              WHERE tar.id_compartir = '$id_compartir';";
                    
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
            </div>
          </div>';
      }

      echo '</div>
      </div>';
  }
?>