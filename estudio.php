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
    <title>Sesión de Estudio</title>
    <link rel="icon" href="img/hop.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Abril+Fatface|Poppins">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>


    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <link rel="stylesheet" href="css/sesion.css">
    <link rel="stylesheet" href="css/youtube.css">
    <script src="js/jsestudio.js" defer></script>
    <script>
        Notification.requestPermission().then(function(permission) {
            if (permission !== "granted") {
            // Si el permiso no se ha otorgado, abrir el modal de Bootstrap
            $('#notificacionN').modal('show');
            }
        });
    </script>
</head>
<body>
    <div id="inicial" style="display: none;">
        <div class="container d-flex align-items-center justify-content-center" style="height: 90vh;">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="bx bx-spa iconom"><h2>Sesión De Estudio</h2></div>
                </div>
            </div>
        </div>
    </div>

    <div class="" id="principal" style="display: none;">
        <nav class="navbar p-3">
            <div class="container-fluid">
                <ul class="nav navbar-nav ms-auto">
                    <li class="nav-item align-content-end">
                        <a href="principal.php" class="nav-link lt button">Salir</a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="container">
            <div class="row">
                <div id="display" class="h2 col-12 text-center">00:00</div>

                <div class="col-6 text-end">
                    <a class="btn bx bx-stop-circle iconom" onclick="stopall()"></a>
                </div>
                <div class="col-6 text-start">
                    <a id="play-pause" class="btn bx bx-play-circle iconom" onclick="playPause()"></a>
                </div>
                
                <div id="contenedor" class="col-lg-6 mt-5 mb-2">
                    <!-- Aquí carga las notificaciones -->
                </div>

                <form class="mb-3" id="Buscador" action="#" method="post">
                    <input class="form-control" type="text" id="q" placeholder="Busca algo en YouTube... c:">
                    <br>
                    <button class="btn" id="btn2" type="submit" name="button">Buscar</button>
                </form>

                <div class="col-lg-6 mb-2">
                        <main class="row">
                            <div class="YouTube-container">
                                <div class="video-player">
                                    <iframe src="https://www.youtube.com/embed/4fezP875xOQ" allowfullscreen="allowfullscreen"></iframe>
                                    <h2 id="tite">Música para Estudiar</h2>
                                    <div class="desplegar" id="desplegar">
                                        <span id="senal">▽</span>
                                    </div>
                                    <div class="description" id="description">
                                        <p id="te">Esta es música que te recomendamos para estudiar.<br>Recuerda que también puedes buscar y escuchar tu música favorita.</p>
                                        <div class="video-list mt-3" id="video-list"></div>
                                    </div>
                                </div>
                                
                            </div>
                        </main>
                    </div>
                </div>

        </div>
    </div>

    <div class="modal fade" id="notificacionN" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-6"><img src="img/Icon v2.png" alt="Logo" class="logoig"></div>
                    <div class="col-6 text-end"><button class="btn" id="btn1" onclick="Notification.requestPermission();">Activa Notificaciones</button></div>
                </div>
                <div class="modal-body">
                    <div class="text-center">Por favor, otorgue permiso para recibir notificaciones</div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="buscaA" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-6"><img src="img/Icon v2.png" alt="Logo" class="logoig"></div>
                </div>
                <div class="modal-body">
                    <div class="text-center">¡Escribe algo para buscar!</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Mostrar el elemento inicial con efecto de fade in
            $("#inicial").fadeIn(1000);
            
            // Ocultar el elemento después de 2 segundos (2000 ms) con efecto de fade out
            setTimeout(function() {
                $("#inicial").fadeOut(1000, function() {
                    // Una vez que el elemento se oculta, mostrar la interfaz principal con efecto de fade in
                    $("#principal").fadeIn(1000);
                });
            }, 2000);
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>   
    <script src="js/youtube.js" charset="utf-8"></script>
</body>
</html>