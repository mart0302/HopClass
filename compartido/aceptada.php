<?php
ob_start();
include "conexiondb.php";
$usuario = $_SESSION["id_usuario"];

echo '
      <div class="card-header bg-white mb-3">
        <h6>Compartidas por ti</h6>
      </div>';
$parametrizar = $conexion->prepare("SELECT t.*, c.id_amigo, c.compartir, u.nombre AS nombre_amigo, u.apellido_paterno
                                    FROM tarea AS t 
                                    INNER JOIN compartir AS c ON t.id_compartir = c.id_compartir 
                                    INNER JOIN usuario AS u ON c.id_amigo = u.id_usuario 
                                    WHERE t.id_usuario = ? AND t.id_compartir <> 0 AND c.compartir = 1 AND c.id_amigo <> t.id_usuario;");
$parametrizar->bind_param("i", $usuario);
$parametrizar->execute();
$buscartareas = $parametrizar->get_result();
while ($mostrartareas = mysqli_fetch_array($buscartareas)){
  if($mostrartareas["completada"] == 1) {$tare = "Completada";} else {$tare = "No Completada";}

  $id_materia = $mostrartareas["id_materia"];

  switch ($mostrartareas["prioridad"]) {
    case '1':
        $pri = "Baja";
        break;
    case '2':
        $pri = "Media";
        break;
    case '3':
        $pri = "Alta";
        break;   
    default:
        break;
  }

  $sql1 = "SELECT nombre_materia, nombre_profesor FROM materia WHERE id_materia = '$id_materia'; ";
  if(($result1 = $conexion->query($sql1)) === FALSE) {
    echo "Error: " . $sql1 . "<br>" . $conexion->error;
  }
  $result1 = mysqli_query($conexion, $sql1); 
  $materia = mysqli_fetch_assoc($result1);
  echo '
  <div class="card mb-3 mt-3"> 
    <div class="card-body row">
      <div class="col-6 mb-3 text-start d-flex align-items-center">'. $materia["nombre_materia"] .' - '. $materia["nombre_profesor"] .'</div>
      <div class="col-6 mb-3 d-flex align-items-center justify-content-end"><form id="completo" action="tarea.php" method="post" style="margin-bottom: 0px;"><input type="submit" value="'. $tare .'" class="btn" id="btn"><input type="hidden" value="'. $mostrartareas["id_tarea"] .'" name="id_tarea"><input type="hidden" value="6" name="tipo"></form></div>
      <hr>
      <div class="col-7">    
        <div class="text-start">' . $mostrartareas["nombre_tarea"] . '</div>
      </div>
      <div class="col-5 text-start d-flex align-items-center">
        <div>Fecha inicio: ' . $mostrartareas["fecha_inicio"] . ' <br>Hora Inicio: ' . $mostrartareas["hora_inicio"] . '</div>
      </div>
      <div class="mt-2 col-7 text-start">
        <div>' . $mostrartareas["observacion"] . '</div>
      </div>
      <div class="mt-2 col-5 text-star d-flex align-items-center">
        <div>Prioridad: ' . $pri . '</div>
      </div>
      <div class="col-12 mb-3 text-start">Compartida con: '.$mostrartareas["nombre_amigo"].'</div>
    </div>
    <div class="card-footer bg-light text-muted">
      <div class="row">
        <div class="col-3 text-center">
          <form action="compartido/compartidaCRUD.php" method="post" id="formD" style="margin-bottom: 0px;">
            <input type="hidden" value="EliminarA" name="tipo">
            <input type="hidden" value="'.$mostrartareas["id_compartir"].'" name="idCompartir">
            <input type="hidden" value="'. $mostrartareas["id_tarea"] .'" name="eliminara">
            <button type="submit" class="btn material-symbols-outlined" id="btn" title="Eiminar Tarea">delete_forever</button>
          </form>
        </div>
        <div class="col-2 text-center"> 
          <form action="compartido/compartidaCRUD.php" method="post" id="formE" style="margin-bottom: 0px;">
            <input type="hidden" value="EditarA" name="tipo">
            <input type="hidden" value="'.$mostrartareas["id_compartir"].'" name="id_compartir">
            <input type="hidden" value="'. $mostrartareas["id_tarea"] .'" name="id_tarea">
            <button type="submit" class="btn material-symbols-outlined" id="btn" title="Editar Tarea">edit</button>
          </form>
        </div>
        <div class="col-2 text-center">
          <a href="#vernotifica" data-bs-toggle="modal">
            <button type="button" class="btn material-symbols-outlined" id="btn" title="Ver Notificaciones" onClick="modalNotificaciones('. $mostrartareas["id_tarea"] .')">notifications_active</button>
          </a>
        </div>
        <div class="col-2 text-center">
          <form id="'. $mostrartareas["id_tarea"] .'" style="margin-bottom: 0px;" class="subcom">
            <input type="hidden" value="4" id="tiposub">
            <input type="hidden" value="'. $mostrartareas["id_tarea"] .'" name="idTarea">
            <button type="submit" class="btn material-symbols-outlined p-0" id="btn-subtarea" title="Ver Subtareas">library_add</button>
          </form>
        </div>
        <div class="col-3 text-center">
          <a href="estudio.php" class="btn material-symbols-outlined" id="btn" title="Abrir Sesión de Estudios">spa</a>
        </div>
      </div>
    </div>
  </div>';
}

echo '
      <div class="card-header bg-white mb-3">
        <h6>Compartidas conmigo</h6>
      </div>';
$parametrizar = $conexion->prepare("SELECT t.*, c.id_amigo, c.compartir, u2.nombre AS usuario
                                    FROM tarea AS t 
                                    INNER JOIN compartir AS c ON t.id_compartir = c.id_compartir 
                                    INNER JOIN usuario AS u2 ON c.id_usuario = u2.id_usuario 
                                    WHERE t.id_usuario = ? AND t.id_compartir <> 0 AND c.compartir = 1 AND c.id_amigo = ?");
$parametrizar->bind_param("ii", $usuario, $usuario);
$parametrizar->execute();
$buscartareas = $parametrizar->get_result();
while ($mostrartareas = mysqli_fetch_array($buscartareas)){
  if($mostrartareas["completada"] == 1) {$tare = "Completada";} else {$tare = "No Completada";}

  $id_materia = $mostrartareas["id_materia"];

  switch ($mostrartareas["prioridad"]) {
    case '1':
        $pri = "Baja";
        break;
    case '2':
        $pri = "Media";
        break;
    case '3':
        $pri = "Alta";
        break;   
    default:
        break;
  }

  $sql1 = "SELECT nombre_materia, nombre_profesor FROM materia WHERE id_materia = '$id_materia'; ";
  if(($result1 = $conexion->query($sql1)) === FALSE) {
    echo "Error: " . $sql1 . "<br>" . $conexion->error;
  }
  $result1 = mysqli_query($conexion, $sql1); 
  $materia = mysqli_fetch_assoc($result1);
  echo '
  <div class="card mb-3 mt-3"> 
    <div class="card-body row">
      <div class="col-6 mb-3 text-start d-flex align-items-center">'. $materia["nombre_materia"] .' - '. $materia["nombre_profesor"] .'</div>
      <div class="col-6 mb-3 d-flex align-items-center justify-content-end"><form id="completo" action="tarea.php" method="post" style="margin-bottom: 0px;"><input type="submit" value="'. $tare .'" class="btn" id="btn"><input type="hidden" value="'. $mostrartareas["id_tarea"] .'" name="id_tarea"><input type="hidden" value="6" name="tipo"></form></div>
      <hr>
      <div class="col-7">    
        <div class="text-start">' . $mostrartareas["nombre_tarea"] . '</div>
      </div>
      <div class="col-5 text-start d-flex align-items-center">
        <div>Fecha inicio: ' . $mostrartareas["fecha_inicio"] . ' <br>Hora Inicio: ' . $mostrartareas["hora_inicio"] . '</div>
      </div>
      <div class="mt-2 col-7 text-start">
        <div>' . $mostrartareas["observacion"] . '</div>
      </div>
      <div class="mt-2 col-5 text-star d-flex align-items-center">
        <div>Prioridad: ' . $pri . '</div>
      </div>
      <div class="col-12 mb-3 text-start">Compartida con: '.$mostrartareas["usuario"].'</div>
    </div>
    <div class="card-footer bg-light text-muted">
      <div class="row">
        <div class="col-3 text-center">
          <form action="compartido/compartidaCRUD.php" method="post" id="EliminarI" style="margin-bottom: 0px;">
            <input type="hidden" value="EliminarI" name="tipo">
            <input type="hidden" value="'.$mostrartareas["id_compartir"].'" name="idCompartir">
            <input type="hidden" value="'. $mostrartareas["id_tarea"] .'" name="eliminari">
            <button type="submit" class="btn material-symbols-outlined" id="btn" title="Eiminar Tarea">delete_forever</button>
          </form>
        </div>
        <div class="col-2 text-center"> 
          <form action="compartido/compartidaCRUD.php" method="post" id="EditarI" style="margin-bottom: 0px;">
            <input type="hidden" value="EditarI" name="tipo">
            <input type="hidden" value="'.$mostrartareas["id_compartir"].'" name="id_compartir">
            <input type="hidden" value="'. $mostrartareas["id_tarea"] .'" name="id_tarea">
            <button type="submit" class="btn material-symbols-outlined" id="btn" title="Editar Tarea">edit</button>
          </form>
        </div>
        <div class="col-2 text-center">
          <a href="#vernotifica" data-bs-toggle="modal">
            <button type="button" class="btn material-symbols-outlined" id="btn" title="Ver Notificaciones" onClick="modalNotificaciones('. $mostrartareas["id_tarea"] .')">notifications_active</button>
          </a>
        </div>
        <div class="col-2 text-center">
          <form id="'. $mostrartareas["id_tarea"] .'" class="subcomdos" style="margin-bottom: 0px;">
            <input type="hidden" value="5" id="tiposubdos">
            <input type="hidden" value="'. $mostrartareas["id_tarea"] .'" name="idTarea">
            <input type="hidden" value="'.$mostrartareas["id_compartir"].'" name="id_compartir">
            <button type="submit" class="btn material-symbols-outlined p-0" id="btn-subtarea2-' . $mostrartareas["id_tarea"] . '" title="Ver Subtareas">library_add</button>
          </form>
        </div>
        <div class="col-3 text-center">
          <a href="estudio.php" class="btn material-symbols-outlined" id="btn" title="Abrir Sesión de Estudios">spa</a>
        </div>
      </div>
    </div>
  </div>';
}
?>