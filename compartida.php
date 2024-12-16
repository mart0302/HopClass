<?php 
    include "seguridad.php";
    
    if (isset($_SESSION["toastmesaje"])) {
      // Mostrar el mensaje del toast
      echo $_SESSION["toastmesaje"];
      // Eliminar la variable de sesión del mensaje del toast
      unset($_SESSION["toastmesaje"]);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Compartir tarea</title>
  <link rel="icon" href="img/Icon v2.png">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Abril+Fatface|Poppins">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"/>

  <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <link rel="stylesheet" href="css/principal.css">
  <link rel="stylesheet" href="css/estilo2.css">
  <link rel="stylesheet" href="css/amigos.css">
  <link rel="stylesheet" href="css/notificaciones.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="js/amigos.js"></script>
  <script src="js/notificacion.js"></script>
  <script src="js/scriptNotificaciones.js"></script>
</head>
<body>
<div class="shadow p-3 bg-white">
  <div class="container-fluid">
    <!--Menú Lateral-->
    <div class="offcanvas offcanvas-start" id="demo">
        <div class="offcanvas-header">
            <img class ="logoig" src="img/Icon v2.png">
            <h2 class="offcanvas-title">NOTIZEN</h2>
            <button type="button" class="btn-close m-2" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <!--Filtro-->
            <ul id="menu">
                <li class="nivel1"><i class='bx bx-home iconom'></i><a href="principal.php">Inicio</a></li>
                <?php include "filtromenu.php"; ?>
                <li class="nivel1"><i class='bx bx-collection iconom iconom2'></i><a href="semestredetalles.php">Semestres</a></li>
                <li class="nivel1"><i class='bx bx-task iconom iconom2'></i><a href="tareadetalle.php">Tareas</a></li>
                <li class="nivel1"><i class='bx bx-share-alt iconom iconom2'></i><a href="compartida.php">Compartidas</a></li>
                <li class="nivel1"><i class='bx bx-note iconom iconom2'></i><a href="notadetalle.php">Notas</a></li>
                <li class="nivel1"><i class='bx bx-spa iconom iconom2'></i><a href="estudio.php">Sesión de Estudio</a></li>
                <li class="nivel1"><i class='bx bx-group iconom  iconom2'></i><a href="amigos.php" >Amigos</a></li>
                <li class="nivel1 mb-5"><i class='bx bx-cog iconom'></i><a href="configuracion.php" >Configuración</a></li>
            </ul>
            <?php include "barrauser.php"; ?>
        </div>
    </div>
    <!--Linea que separa al menu y la notificacion-->
      <div class="container-fluid mt-3">
        <div class="row align-items-center">
            <!--Botón para menú horizontal-->
            <div class="col-11">
                <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#demo" id="btn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <!--Notificaciones-->
            <div class="col-1">
                <div class="ml-auto">
                    <div class="dropdown">
                        <?php require "amigos/notificaciones.php";?>
                    </div>
                </div>
            </div>
        </div>
      </div>
    <!--Fin linea-->
  </div>
  </div>

<!--Menú para tareas compartidas-->
<button class="tablink tarea" onclick="openPage('Solicitudes', this, 'white', 'black')" id="defaultOpen">Solicitudes</button>
<button class="tablink tarea" onclick="openPage('Compartidas', this, 'white', 'black')" id="subtareas">Compartidas</button>
<button class="tablink tarea" onclick="openPage('Pendientes', this, 'white', 'black')">Pendientes</button>
<!--Solicitudes de tareas-->
<div id="Solicitudes" class="tabcontent">
  <div class="row">
      <div class="card col-md-10 mx-auto">
        <div class="card-body row">
          <div class="card col-lg-6" id="resultados">
            <?php require "compartido/solicitud.php";?>
          </div>
          <div class="card col-lg-5 mx-auto" id="resultadosSubtarea2">
            <div class="card-header bg-white">
              <div class="row" >
                <div class="col-12 text-start">Subtareas</div>                                  
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
<!--Tareas compartidas-->
<div id="Compartidas" class="tabcontent">
    <div class="row">
      <div class="card col-md-10 mx-auto">
        <div class="card-body row">
          <div class="card col-lg-6" id="resultados">
            <?php require "compartido/aceptada.php";?>
          </div>
          <div class="card col-lg-5 mx-auto" id="resultadosSubtarea">
            <div class="card-header bg-white">
              <div class="row" >
                <div class="col-12 text-start">Subtareas</div>                                  
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
<!--Pendientes-->
<div id="Pendientes" class="tabcontent">
<div class="row">
      <div class="card col-md-10 mx-auto">
        <div class="card-body row">
          <div class="card col-lg-6" id="resultados">
            <?php require "compartido/pendientes.php";?>
          </div>
          <div class="card col-lg-5 mx-auto" id="resultadosSubtarea3">
            <div class="card-header bg-white">
              <div class="row" >
                <div class="col-12 text-start">Subtareas</div>                                  
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
</html>
<!--Script para mostrar primero la sección de solicitudes de tarea-->
<script>
  document.getElementById("defaultOpen").click();
</script>

  <!--Modal para crear las notificaciones-->
  <div class="modal fade" id="vernotifica">
            <div class="modal-dialog row">
                <div class="modal-content col-ls-12">
                    <form action="notificaciones.php" method="post">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <div class="col-6"><img src="img/Icon v2.png" alt="Logo" class="logoig"></div>
                            <div class="col-6 text-end">Notificaciones</div>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body row" id="modalNotificaciones">
                            
                        </div>                        
                    </form>
                </div>
            </div>
        </div>

  <!--Modal para crear subtarea-->
  <div class="modal fade" id="creasubtarea" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog row">
                <div class="modal-content col-ls-12">
                    <form id="formC" action="subtarea.php" method="post">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <div class="col-6"><img src="img/Icon v2.png" alt="Logo" class="logoig"></div>
                            <div class="col-6 text-end"><input type="submit" value="Crear Subtarea" class="btn" id="btn"></div>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body row" id="obtenOie"></div>
                    </form>
                </div>
            </div>
        </div>
    <script id="cargaNoti">actualizaNoti();</script>
</body>
</html>
<script>
  $(document).ready(function() {
  // Borra lo que tenga impreso anteriormente
  $('#resultadosSubtarea').html('');
  $('#resultadosSubtarea2').html('');
  $('#resultadosSubtarea3').html('');


  $(document).on('submit', 'form:not(#form1, #formB, #formC, #formD, #formE, #formF, #formG, #formH, #formI, #formJ, #formK, #formL, #formM, #completo, #completo1, #EliminarI, #EditarI, #Aceptar)', function(event) {
    if ($(this).attr('id') !== 'EditarI' || $(this).attr('id') !== 'EliminarI') {
      event.preventDefault();
    }

    // Obtener el valor de la fecha del campo de entrada
    var tarea = $(this).find('input[name="idTarea"]').val();
    var classFormulario = $(this).attr('class');

    // Determinar el valor del atributo tipo basado en el id del formulario
    var tipo;
    var link;
    var ubi = '#resultadosSubtarea';
    if (classFormulario === 'subcom') {
      tipo = '4';
      link = 'subtarea.php';
    } else if (classFormulario === 'subcomdos') {
      tipo = '5';
      link = 'subcompartida.php';
    }
    else if (classFormulario === 'subcomtres'){
      tipo = '5';
      link = 'subcompartida.php';
      ubi = '#resultadosSubtarea2';
    }
    else if (classFormulario === 'subcomcuatro'){
      tipo = '4';
      link = 'subtarea.php';
      ubi = '#resultadosSubtarea3';
    }
    if (!tipo) {
      tipo = $('#tiposub').val();
    }

    // Enviar una solicitud de búsqueda al servidor utilizando AJAX
    $.ajax({
      url: link,
      method: 'POST',
      data: { tarea: tarea, tipo: tipo },
      success: function(result) {
        // Mostrar los resultados de la búsqueda en la página
        $(ubi).html(result);
        window.location.href = ubi;
        $('#obtenOie').load(link, function() {
          obtenID2();
        });
      }
    });
  });
});

            function obtenID2() { //Aquí se obtienen los ID de tareas y se crea el formulario para crear subtareas (MODAL)
                $.ajax({
                    url: 'subtarea.php',
                    method: 'POST',
                    data: { tipo: 6 },
                    success: function(result) {
                        // Parsear la respuesta JSON para obtener la variable de sesión actualizada
                        var updatedSession = JSON.parse(result);
                        var id_tarea = updatedSession.id_tarea;
                        var compartir = updatedSession.compartir;
                        
                        // Actualizar el valor en el DOM
                        $('#obtenOie').html('');
                        
                        // Crea el contenido HTML del formulario
                        var formHTML = `
                            <div class="col-12">
                                <label for="nombreSubtarea" class="fw-bold col-8 form-label">Nombre Subtarea:</label>
                                <input type="text" name="nombreSubtarea" class="col-4 form-control" maxlength="50" required>
                            </div>
                            <div class="pt-2"></div>
                            <div class="col-12">
                                <label for="descrSubtarea" class="fw-bold col-8 form-label">Descripción Nota:</label>
                                <textarea name="descrSubtarea" class="col-4 form-control" style="height:100px;" maxlength="100" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ!@#\$%\^&\*\(\)_\+\-\=\[\]\{\};:\|,\.\<\>\/\?]{1,100}" required></textarea>
                            </div>
                            <input type="hidden" value="1" name="tipo">
                            <input type="hidden" value="${compartir}" name="esN">
                            <input type="hidden" value="${id_tarea}" name="id_tar">
                            <div class="pt-3"></div>`;
                        
                        // Inserta el formulario en el elemento con el ID "obtenOie"
                        document.querySelector("#obtenOie").innerHTML = formHTML;
                    }
                });
            }

</script>