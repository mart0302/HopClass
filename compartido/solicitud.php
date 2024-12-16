<?php
ob_start();
include "conexiondb.php";
$usuario = $_SESSION["id_usuario"];

echo '
      <div class="card-header bg-white mb-3">
        <h6>Solicitudes</h6>
      </div>';

$max= "SELECT MAX(id_semestre) AS maxsemestre FROM semestre WHERE id_usuario = $usuario";
$busquedams = mysqli_query($conexion, $max);
$maxsemestre = mysqli_fetch_array($busquedams)['maxsemestre']; 
$parametrizar = $conexion->prepare("SELECT t.*, u.nombre AS amigo, u.id_usuario, c.id_compartir, u.apellido_paterno
                                          FROM compartir AS c
                                          INNER JOIN tarea AS t ON c.id_compartir= t.id_compartir
                                          INNER JOIN usuario AS u ON c.id_usuario = u.id_usuario 
                                          WHERE c.id_amigo = ? AND c.compartir = 0;");
$parametrizar->bind_param("i", $usuario);
$parametrizar->execute();
$buscartareas = $parametrizar->get_result();
$contador = 1;
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
                        <div class="col-12 mb-3 text-start">Compartida con: '.$mostrartareas["amigo"].' '.$mostrartareas["apellido_paterno"].'</div>
                    </div>
                    <div class="card-footer bg-light text-muted">
                        <div class="row">
                            <div class="col-4 text-center">
                                <a class="dropdown-item btn material-symbols-outlined" href="#compartida'.$contador.'" data-bs-toggle="modal">done_outline</a>
                            </div>
                            <div class="col-4 text-center"> 
                                <form action="compartido/compartidaCRUD.php" method="post" id="formE" style="margin-bottom: 0px;">
                                    <input type="hidden" value="Rechazar" name="tipo">
                                    <input type="hidden" value="'.$mostrartareas["id_compartir"].'" name="idCompartir">
                                    <input type="hidden" value="'.$mostrartareas["id_usuario"].'" name="idAmigo">
                                    <input type="hidden" value="'. $mostrartareas["id_tarea"] .'" name="editar" id="editar">
                                    <button type="submit" class="btn material-symbols-outlined" id="btn" title="Rechazar">delete_forever</button>
                                </form>
                            </div>
                            <div class="col-4 text-center">
                                <form id="'. $mostrartareas["id_tarea"] .'" style="margin-bottom: 0px;" class="subcomtres">
                                    <input type="hidden" value="5" id="tiposub">
                                    <input type="hidden" value="'. $mostrartareas["id_tarea"] .'" name="idTarea">
                                    <button type="submit" class="btn material-symbols-outlined" id="btn-subtarea" title="Ver Subtareas">library_add</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>';
          echo '<div class="modal fade" id="compartida'.$contador.'">
                <div class="modal-dialog row">
                <div class="modal-content col-ls-12">
                <form action="compartido/compartidaCRUD.php" method="post" id="Aceptar">
                    <div class="modal-header">
                        <div class="col-6"><img src="img/Icon v2.png" alt="Logo" class="logoig"></div>
                        <div class="col-6 text-end"><input type="submit" value="Guardar" name="tipo" class="btn" id="btn1"></div>
                    </div>
                    <div class="modal-body row">
                            <div class="col-12">';
                                  $parametrizar = $conexion->prepare("SELECT id_materia, nombre_materia FROM materia WHERE id_usuario =? AND id_semestre = ?");
                                  $parametrizar->bind_param("ii", $usuario, $maxsemestre);
                                  $parametrizar->execute();
                                  $buscarmaterias = $parametrizar->get_result();
                                  echo '<input type="hidden" id="semestre" name="semestre" value="'.$maxsemestre.'">
                                  <label class="fw-bold col-8 form-label" for="materia">Materia:</label>
                                  <select class="form-select" name="materias" id="materias">';
                                  while ($mostrarmaterias = mysqli_fetch_array($buscarmaterias)){
                                    echo '<option value="'.$mostrarmaterias["id_materia"].'">'.$mostrarmaterias["nombre_materia"].'</option>';
                                  }
                                  echo '</select>
                                  <input type= "hidden" value="'.$mostrartareas["id_tarea"].'">';
                                  $param = $conexion->prepare("SELECT * FROM tarea WHERE id_tarea=?");
                                  $param->bind_param("i", $mostrartareas["id_tarea"]);
                                  $param->execute();
                                  $buscartarea = $param->get_result();

                                  while ($mostrartarea = mysqli_fetch_array($buscartarea)){
                                  echo '<label for="nombre" class="fw-bold col-8 form-label">Nombre tarea:</label>
                                    <input type="text" name="nombre" id ="nombre" class="col-4 form-control" value="'.$mostrartarea["nombre_tarea"].'"readonly>
                                    <label for="fecha_inicio" class="fw-bold col-8 form-label">Fecha de inicio:</label>
                                    <input type="date" name="fecha_inicio" id ="fecha_inicio" class="col-4 form-control" value="'.$mostrartarea["fecha_inicio"].'" readonly>
                                    <label for="hora" class="fw-bold col-8 form-label">Hora inicio:</label>
                                    <input type="time" name="hora" id ="hora" class="col-4 form-control" value="'.$mostrartarea["hora_inicio"].'" readonly>
                                    <label for="prioridad" class="fw-bold col-8 form-label mt-2">Prioridad</label>
                                    <select class="form-select" name="prioridad" id="prioridad">
                                        <option value="1">Baja</option>
                                        <option value="2">Media</option>
                                        <option value="3">Alta</option>
                                    </select>
                                    <label for="comentario" class="fw-bold col-8 form-label">Descripción Tarea:</label>
                                    <textarea name="comentario" class="col-4 form-control" style="height:100px;" maxlength="100" pattern="^(?=.*[a-zA-Z0-9])(?! )[\sa-zA-ZáéíóúÁÉÍÓÚñÑ0-9!@#$%^&*()_+\-=[\]{};:\\|,.<>/?]{1,100}$" required></textarea>
                                    <input type="hidden" id="id_tarea" name="id_tarea" value="'.$mostrartarea["id_compartir"].'">
                                    <input type="hidden" id="amigo" name="amigo" value="'.$mostrartarea["id_usuario"].'">';}
                            echo '</div>
                            <div class="esp"></div>
                    </div>
                </form>
                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    Copyright © Notizen 2023
                </div>
            </div>
        </div>
    </div>';
    $contador++;}
  ?>