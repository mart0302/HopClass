<?php
    include "conexiondb.php";
    //Primer nivel
    echo '<li><i class="bx bx-book iconom"></i></i><input type="checkbox" name="list" id="nivel1-1"><label for="nivel1-1" class="filtro nivel1">Cursos</label>';
    //Segundo nivel
      echo '<ul class="interior">';
        $usuario = $_SESSION["id_usuario"];
        $x=1;
        $max= "SELECT MAX(id_semestre) AS maxsemestre FROM semestre WHERE id_usuario = $usuario";
        $busquedams = mysqli_query($conexion, $max);
        $maxsemestre = mysqli_fetch_array($busquedams)['maxsemestre']; 
        $sql="SELECT nombre_semestre, id_semestre from semestre WHERE id_usuario = $usuario ORDER BY id_semestre DESC" ;
        $result=mysqli_query($conexion,$sql);
        while($mostrar=mysqli_fetch_array($result)){
          echo '<li><input type="checkbox" name="list" id="nivel2-'.$x.'"><label for="nivel2-'.$x.'" class="nivel2">'.$mostrar['nombre_semestre'].'</label>';
          $x++;
            echo '<ul class="interior lista3" style="margin: 0px">';
              //Obtener el id_semestre correspondiente al semestre actual
              $ssemestre = "SELECT id_semestre FROM semestre WHERE id_usuario = $usuario AND nombre_semestre = '".$mostrar['nombre_semestre']."'";
              $busquedas = mysqli_query($conexion, $ssemestre);
              $semestre = mysqli_fetch_array($busquedas)['id_semestre'];
              
              // utilizar $semestre en la consulta SQL
              $parametrizar = $conexion->prepare("SELECT id_materia, nombre_materia, nombre_profesor FROM materia WHERE id_usuario = ? AND id_semestre = ?");
              $parametrizar->bind_param("ii", $usuario, $semestre);
              $parametrizar->execute();
              $buscarm = $parametrizar->get_result();
              while ($mostrarm = mysqli_fetch_array($buscarm)) {
                  echo '<li><i class="bx bx-folder iconom"></i><label for="nivel3-'.$x.'" class="nivel3" data-bs-toggle="modal" data-bs-target="#modal-'.$x.'" data-bs-backdrop="static">'.$mostrarm["nombre_materia"].'</label><br><br>
                        <div class="modal fade" id="modal-'.$x.'" tabindex="-1" aria-labelledby="modal-'.$x.'-label" role="dialog"  aria-hidden="true"|>
                        <div class="modal-dialog row role="document"">
                        <div class="modal-content col-ls-12">
                        <div class="modal-header">
                          <div class="col-6"><img src="img/Icon v2.png" alt="Logo" class="logoig"></div>
                        </div>';
                        // Modal body
                        echo '<div class="modal-body row">
                                  <div class="col-12 pt-4">
                                    <h5><b>Datos</b></h5><br>
                                    <h5><b>Materia: &nbsp;&nbsp;</b>'.$mostrarm["nombre_materia"].'</h5><br><br>
                                    <h5><b>Profesor: &nbsp;&nbsp;</b>'.$mostrarm["nombre_profesor"].'</h5><br><br>
                                  </div>
                                ';
                        //Seleccionar el ultimo semestre registrado
                        if ($semestre == $maxsemestre) {
                          echo '<div class="col-2"></div>
                          <div class="col-4 text-start">
                            <form id="formK" action="materia.php" method="post" style="margin-bottom: 0px;">
                            <input type="hidden" value="4" name="tipo">
                            <input type="hidden" value="'. $mostrarm["id_materia"] .'" name="id_materiavis">
                            <button type="submit" class="btn material-symbols-outlined" id="btn">edit</button>
                            </form>
                          </div>';
                        }
                        echo '
                          <div class="col-4 text-end">
                            <form id="formJ" action="materia.php" method="post" style="margin-bottom: 0px;">
                               <input type="hidden" name="id_materia" value="'.$mostrarm["id_materia"].'">
                                <input type="hidden" name="tipo" value="2">
                                <button type="submit" class="btn material-symbols-outlined" id="btn">delete_forever</button>
                              </form>
                            </div>
                            <div class="col-2"></div>
                        </div>'; 
                          // Modal footer
                          echo '
                        </div>
                        </div>
                        </div>
                      </li><br>';
                    $x++;
                }
                if ($semestre == $maxsemestre) {
                  echo '<li><i class="bx bx-folder-plus iconom"></i></i><label for="agregar" class="agregarm" data-bs-toggle="modal" data-bs-target="#agregarmateria" data-bs-backdrop="static">Agregar</label><br><br>
                        <div class="modal fade" id="agregarmateria" tabindex="-1" aria-labelledby="amateria" role="dialog"  aria-hidden="true">
                        <div class="modal-dialog row role="document"">
                        <div class="modal-content col-ls-12">
                        <form id="formH" action="materia.php" method="post">
                        <div class="modal-header">
                          <div class="col-6"><img src="img/Icon v2.png" alt="Logo" class="logoig"></div>
                          <div class="col-6 text-end"><input type="submit" value="Crear Materia" class="btn" id="btn1"></div>
                        </div>';
                        // Modal body
                        echo '<div class="modal-body">
                                  <br><br>
                                  <div class="col-12">
                                    <label for="nombremateria" class="fw-bold col-8 form-label">Materia:</label>
                                    <input type="text" name="nombremateria" id ="nombrem-'.$x.'" class="col-4 form-control" maxlength="50" pattern="^(?=.*[a-zA-Z0-9])(?! )[\sa-zA-ZáéíóúÁÉÍÓÚñÑ0-9!@#$%^&*()_+\-=[\]{};:\\|,.<>/?]{1,50}$"
                                    title="Letras mayúsculas, minúsculas, números, carácteres especiales y espacios permitidos.Máximo 50."require><br><br>
                                    <label for="profesorn" class="fw-bold col-8 form-label">Docente:</label>
                                    <input type="text" name="profesorn" id ="profesorn-'.$x.'" class="col-4 form-control" maxlength="100" pattern="^(?=.*[a-zA-Z0-9])(?! )[\sa-zA-ZáéíóúÁÉÍÓÚñÑ0-9!@#$%^&*()_+\-=[\]{};:\\|,.<>/?]{1,50}$"
                                    title="Letras mayúsculas, minúsculas, números, carácteres especiales y espacios permitidos. Máximo 100." require><br><br>
                                    <input type="hidden" value="1" name="tipo">
                                    <input type="hidden" value="'.$maxsemestre.'" name="id_semestre">
                                    <div class="pt-3"></div>
                                    <div class="pt-3"></div>
                                  </div>
                                </div>';
                          // Modal footer
                          echo '
                        </form>
                        </div>
                        </div>
                        </div>';
                }
          echo '</ul></li>';
        }

        //Agregar Semestre
        echo '<li><label for="agregar" class="agregarm" data-bs-toggle="modal" data-bs-target="#agregarsemestre" data-bs-backdrop="static">Agregar</label><br><br>
                        <div class="modal fade" id="agregarsemestre" tabindex="-1" aria-labelledby="asemestre" role="dialog"  aria-hidden="true"|>
                        <div class="modal-dialog row role="document"">
                        <div class="modal-content col-ls-12">
                        <form id="formG" action="semestre.php" method="post">
                        <div class="modal-header">
                          <div class="col-6"><img src="img/Icon v2.png" alt="Logo" class="logoig"></div>
                          <div class="col-6 text-end"><input type="submit" value="Crear Semestre" class="btn" id="btn1"></div>
                        </div>';
                        // Modal body
                        echo '<div class="modal-body">
                                  <br><br>
                                  <div class="col-12">
                                    <label for="nombresemestre" class="fw-bold col-8 form-label">Semestre:</label>
                                    <input type="text" name="semestre" id ="semestre" class="col-4 form-control" maxlength="50" pattern="^(?=.*[a-zA-Z0-9])(?! )[\sa-zA-ZáéíóúÁÉÍÓÚñÑ0-9!@#$%^&*()_+\-=[\]{};:\\|,.<>/?]{1,50}$"
                                    title="Letras mayúsculas, minúsculas, números, carácteres especiales y espacios permitidos.Máximo 50."require><br><br>
                                    <label for="nums" class="fw-bold col-8 form-label">Número:</label>
                                    <input type="number" name="num" id ="num" class="col-4 form-control" min=1 require><br><br>
                                    <input type="hidden" value="1" name="tipo">
                                    <div class="pt-3"></div>
                                    <div class="pt-3"></div>
                                  </div>
                                </div>';
                          // Modal footer
                          echo '
                      </form>
                    </div>
                  </div>
                </div>';
        //Fin agregar Semestre
      echo '</ul>';
?>