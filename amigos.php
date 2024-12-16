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
    <title>Amigos</title>
    <link rel="icon" href="img/Icon v2.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Abril+Fatface|Poppins">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Encode+Sans+Semi+Condensed:wght@600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="css/principal.css">
    <link rel="stylesheet" href="css/estilo2.css">
    <link rel="stylesheet" href="css/amigos.css">
    <link rel="stylesheet" href="css/notificaciones.css">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/js1.js"></script>
    <script src="js/amigos.js"></script>
    <script src="js/buscador.js"></script>
    <script src="js/notificacion.js"></script>
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
<!--Menú para amigos-->
<button class="tablink" onclick="openPage('Amigos', this, 'white', 'black')" id="defaultOpen">Amigos</button>
<button class="tablink" onclick="openPage('Solicitudes', this, 'white', 'black')">Solicitudes</button>
<button class="tablink" onclick="openPage('Pendientes', this, 'white', 'black')">Pendientes</button>
<button class="tablink" onclick="openPage('Buscar', this, 'white', 'black')">Buscar</button>
<!--Muestra amigos agregados-->
<div id="Amigos" class="tabcontent">
  <?php 
    require "amigos/agregados.php";
  ?>
</div>
<!--Muestra las solicitudes-->
<div id="Solicitudes" class="tabcontent">
  <?php 
    require "amigos/solicitudes.php";
  ?>
</div>
<!--Muestra los solicitudes que se han enviado desde la cuenta y no han sido aceptadas-->
<div id="Pendientes" class="tabcontent">
  <?php 
    require "amigos/pendientes.php";
  ?>
</div>
<!--Buscar amigos-->
<div id="Buscar" class="tabcontent buscar">
  <form action="amigos/buscar.php" method="post" id="buscador" required>
    <input type="email" name="search" placeholder="Buscar" required>
    <button class="btn" id="bbuscaramigo">Buscar</button>
  </form><br><br>
  <div id="resultado"></div>
</div>

<!--Script para mostrar primero la sección de amigos-->
<script>
  document.getElementById("defaultOpen").click();
</script>

    <!-- Eliminar-->
    <div class="modal fade" id="eliminar">
        <div class="modal-dialog row">
            <div class="modal-content col-ls-12">
                <form action="sesion.php" method="post">
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
</body>
</html>
</body>
</html>