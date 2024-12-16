<?php
ob_start();
include "conexiondb.php";

echo '
      <div class="card-header bg-white mb-3">
        <h6>Pendientes</h6>
      </div>';

    $usuario = $_SESSION["id_usuario"];
    $parametrizar = $conexion->prepare("SELECT t.*, c.id_amigo, c.compartir, u.nombre AS amigo, u.apellido_paterno
                                        FROM tarea AS t 
                                        INNER JOIN compartir AS c ON t.id_compartir = c.id_compartir 
                                        INNER JOIN usuario AS u ON c.id_amigo = u.id_usuario 
                                        WHERE t.id_usuario = ? AND t.id_compartir <> 0 AND c.compartir = 0;");
    $parametrizar->bind_param("i", $usuario);
    $parametrizar->execute();
    $buscartareas = $parametrizar->get_result();
    while ($mostrartareas = mysqli_fetch_array($buscartareas)){
      
        echo '
              <div class="card mb-3 mt-3"> 
                          <div class="card-body row">
                              <div class="col-7">    
                                  <div class="text-start">' . $mostrartareas["nombre_tarea"] . '</div>
                              </div>
                              <div class="col-5 text-start">
                                  <div>Fecha inicio: ' . $mostrartareas["fecha_inicio"] . ' <br>Hora Inicio: ' . $mostrartareas["hora_inicio"] . '</div>
                              </div>
                              <div class="mt-2 col-7 text-start">
                                <div>' . $mostrartareas["observacion"] . '</div>
                              </div>
                              <div class="col-12 mb-3 text-start">Compartida con: '.$mostrartareas["amigo"].' '.$mostrartareas["apellido_paterno"].'</div>
                          </div>
                          <div class="card-footer bg-light text-muted">
                              <div class="row">
                                  <div class="col-6 text-center">
                                      <form action="compartido/compartidaCRUD.php" method="post" id="formE" style="margin-bottom: 0px;">
                                          <input type="hidden" value="Cancelar" name="tipo">
                                          <input type="hidden" value="'.$mostrartareas["id_compartir"].'" name="idCompartir">
                                          <input type="hidden" value="'. $mostrartareas["id_tarea"] .'" name="bcancelar" id="bcancelar">
                                          <button type="submit" class="btn material-symbols-outlined p-0" id="btn" title="Cancelar">delete_forever</button>
                                      </form>
                                  </div>
                                  <div class="col-6 text-center">
                                      <form id="'. $mostrartareas["id_tarea"] .'" style="margin-bottom: 0px;" class="subcomcuatro">
                                          <input type="hidden" value="4" id="tiposub">
                                          <input type="hidden" value="'. $mostrartareas["id_tarea"] .'" name="idTarea">
                                          <button type="submit" class="btn material-symbols-outlined p-0" id="btn-subtarea" title="Ver Subtareas">library_add</button>
                                      </form>
                                  </div>
                              </div>
                          </div>
                      </div>';
    }
?>