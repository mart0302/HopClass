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
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mi Panel-Notas</title>
        <link rel="icon" href="img/Icon v2.png">
        <link rel="stylesheet" href="css/principal.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Abril+Fatface|Poppins">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"/>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="js/notificacion.js"></script>

        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link href="https://fonts.googleapis.com/css2?family=Encode+Sans+Semi+Condensed:wght@600&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="css/principal.css">
        <link rel="stylesheet" href="css/notificaciones.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    </head>
    <body>
    <div class="shadow p-3 bg-white">
  <div class="container-fluid pb-3">
    <div class="offcanvas offcanvas-start" id="demo">
        <div class="offcanvas-header">
            <img class ="logoig" src="img/Icon v2.png">
            <h2 class="offcanvas-title">NOTIZEN</h2>
            <button type="button" class="btn-close m-2" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <!--Filtro-->
            <ul id="menu"> <!-- Esto sirve para generar un menú desplegable-->
                <li class="nivel1"><i class='bx bx-home iconom'></i><a href="principal.php">Inicio</a></li>
                <?php require "filtromenu.php"; ?>
                <!-- elementos de menú, un elemento de lista <li> y un enlace <a> -->
                <li class="nivel1"><i class='bx bx-collection iconom'></i><a href="semestredetalles.php">Semestres</a></li>
                <li class="nivel1"><i class='bx bx-task iconom'></i><a href="tareadetalle.php">Tareas</a></li>
		        <li class="nivel1"><i class='bx bx-share-alt iconom'></i><a href="compartida.php">Compartidas</a></li>
                <li class="nivel1"><i class='bx bx-note iconom'></i><a href="notadetalle.php">Notas</a></li>
                <li class="nivel1"><i class='bx bx-spa iconom'></i><a href="estudio.php">Sesión de Estudio</a></li>
		        <li class="nivel1"><i class='bx bx-group iconom'></i><a href="amigos.php">Amigos</a></li>
                <li class="nivel1 mb-5"><i class='bx bx-cog iconom'></i><a href="configuracion.php" >Configuración</a></li>
            </ul>
            <?php require "barrauser.php"; ?>
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

        <div class="container-fluid mt-5" id="ir">
            <div class="row">
                <div class="card col-md-10 mx-auto">
                    <div class="card-header row">
                        <div class="col-6"><img src="img/Icon v2.png" alt="Logo" class="logoig"></div>
                        <div class="col-6 text-end"><a href="principal.php" class="btn pt-2 mt-1" id="btn">Regresar</a></div>                   
                    </div>
                    <div class="card-body row">
                        <form id="form1">
                            <div class="form-group">
                                <label for="fecha">Busca Notas:</label>
                                <select class="form-select" id="fecha">
                                    <option value="13" selected>TODAS</option>
                                    <option value="1">ENERO</option>
                                    <option value="2">FEBRERO</option>
                                    <option value="3">MARZO</option>
                                    <option value="4">ABRIL</option>
                                    <option value="5">MAYO</option>
                                    <option value="6">JUNIO</option>
                                    <option value="7">JULIO</option>
                                    <option value="8">AGOSTO</option>
                                    <option value="9">SEPTIEMBRE</option>
                                    <option value="10">OCTUBRE</option>
                                    <option value="11">NOVIEMBRE</option>
                                    <option value="12">DICIEMBRE</option>
                                </select>    
                            </div>
                            <input type="hidden" id="tipo" value="6">
                            <button type="submit" class="btn mt-1" id="btn">Buscar</button>
                        </form>
                        <div class="text-end pe-3" id="ico"><a href="#creanota" data-bs-toggle="modal" class="btn" id="btn">Nueva Nota</a></div>
                    </div>
                    <div class="card-body row">
                        <div class="card col-lg-6 mx-auto" id="resultados">
                            <?php require "notas.php"; load($conexion, 1);?>
                        </div>
                    </div>
                    <div class="p-2 text-end">
                        <a href="#ir" class="btn m-2" id="btn">Ir Arriba</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="creanota">
            <div class="modal-dialog row">
                <div class="modal-content col-ls-12">
                    <form action="notas.php" method="post">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <div class="col-6"><img src="img/Icon v2.png" alt="Logo" class="logoig"></div>
                            <div class="col-6 text-end"><input type="submit" value="Crear Nota" class="btn" id="btn1"></div>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body row">
                                <div class="col-12">
                                    <label for="nombreNota" class="fw-bold col-8 form-label">Nombre Nota:</label>
                                    <input type="text" name="nombreNota" class="col-4 form-control" maxlength="50" pattern="^(?=.*[a-zA-Z0-9])(?! )[\sa-zA-ZáéíóúÁÉÍÓÚñÑ0-9!@#$%^&*()_+\-=[\]{};':\\|,.<>/?]{1,50}$"
                                    title="Letras mayúsculas, minúsculas, números, carácteres especiales y espacios permitidos" required>
                                </div>
                                <div class="pt-2"></div>
                                <div class="col-12">
                                    <label for="descrNota" class="fw-bold col-8 form-label">Descripción Nota:</label>
                                    <textarea name="descrNota" class="col-4 form-control" style="height:100px;" maxlength="100" pattern="^(?=.*[a-zA-Z0-9])(?! )[\sa-zA-ZáéíóúÁÉÍÓÚñÑ0-9!@#$%^&*()_+\-=[\]{};':\\|,.<>/?]{1,100}$"
                                    required></textarea>
                                </div>
                                <input type="hidden" value="1" name="tipo">
                                <input type="hidden" value="1" name="esI">
                                <div class="pt-3"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Eliminar cuenta-->
    <div class="modal fade" id="eliminar">
        <div class="modal-dialog row">
            <div class="modal-content col-ls-12">
                <form id="formI" action="sesion.php" method="post">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <div class="col-6"><img src="img/Icon v2.png" alt="Logo" class="logoig"></div>
                        <div class="col-6 text-end"><input type="submit" value="Eliminar Cuenta" name="tipo" class="btn" id="btn1"></div>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body row">
                        <div class="esp"></div>
                            <div class="col-12">
                                <label for="pass2" class="fw-bold col-8 form-label">Contraseña:</label>
                                <input type="password" name="pass2" id ="epass" class="col-4 form-control" maxlength="10" require>
                                <a onclick="togglePasswordReg3()"><span class="material-symbols-outlined btn" id="PassIcon3">visibility_off</span></a>
                            </div>
                            <div class="esp"></div>
                    </div>
                </form>
                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    Copyright © Notizen 2023
                </div>
            </div>
        </div>
    </div>
    <script id="cargaNoti">actualizaNoti();</script>
        <script>
            $(document).ready(function() {
                $('#form1').submit(function(event) {
                    // Prevenir el comportamiento predeterminado del formulario
                    event.preventDefault();
                    
                    // Obtener el valor de la fecha del campo de entrada
                    var fecha = $('#fecha').val();
                    var tipo = $('#tipo').val();
                    
                    // Enviar una solicitud de búsqueda al servidor utilizando AJAX
                    $.ajax({
                    url: 'notas.php',
                    method: 'POST',
                    data: { fecha: fecha, tipo: tipo },
                    success: function(result) {
                        // Mostrar los resultados de la búsqueda en la página
                        $('#resultados').html(result);
                    }
                    });
                });
            });
        </script>
    </body>
</html>