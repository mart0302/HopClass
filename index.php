<?php
    session_start();
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
    <title>HopClass</title>
    <link rel="icon" href="img/hop.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Abril+Fatface|Poppins">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    
    <link rel="stylesheet" type="text/css" href="https://fonts.google.com/specimen/Montserrat">
    <link rel="stylesheet" href="css/estilo1.css">
    <link rel="stylesheet" href="css/estilo2.css">

    <script src="js/js1.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-sm p-3 navbar-custom">
  <div class="container-fluid">
    <a href="#"><span class="fluent--animal-rabbit-24-filled"></span></a><div class="logo"><b> HOPCLASS</b></div>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
      <ul class="nav navbar-nav nav-justified">
                <li class="nav-item n1">
                    <a href="#sesion" data-bs-toggle="modal" class="nav-link"><b>Iniciar Sesión</a>
                </li>
                <li class="nav-item">
                    <a href="#registro" data-bs-toggle="modal" class="nav-link">Registrarse</b></a>
                </li>
      </ul>
    </div>
  </div>
</nav>

    <div class="esp"></div>

    <div class="container">
  <div class="row">
    <div class="col-12">
      <h3 class="text-center texto" id="notizen"><b>¿Qué es HopClass?</b></h3>
    </div>
    <hr style="color: #01D8DD">
    <div class="funciones">
      <div class="texto row pt-5">

        <!-- Primera fila: tres bloques -->
        <div class="col-md-4 mb-4">
          <div class="card">
            <div class="card-header">
            <span class="fluent-mdl2--calendar"></span>
              <h4 class="pt-3">Organiza tus tareas</h3>
            </div>
            <p class="t1">Guarda tus tareas y visualízalas en un calendario clasificadas por materias.</p>
          </div>
        </div>

        <div class="col-md-4 mb-4">
          <div class="card">
            <div class="card-header">
            <span class="lsicon--time-two-outline"></span>
              <h4 class="pt-3">Administra tus tiempos</h3>
            </div>
            <p class="t1">Configura notificaciones para tus tareas y gestiona mejor tu tiempo.</p>
          </div>
        </div>

        <div class="col-md-4 mb-4">
          <div class="card">
            <div class="card-header">
            <span class="icons8--student"></span>
              <h4 class="pt-3">Crea un hábito de estudio</h3>
            </div>
            <p class="t1">Utiliza la <i>Técnica Pomodoro</i> para mantener el enfoque en tus tareas.</p>
          </div>
        </div>

        <!-- Segunda fila: dos bloques centrados -->
        <div class="col-md-6 mb-4">
          <div class="card">
            <div class="card-header">
            <span class="mdi--partnership-outline"></span>
              <h4 class="pt-3">Estudia con un amigo</h3>
            </div>
            <p class="t1">Con la sesión compartida podrás compartir tareas y enlazar tu cuenta con un amigo para estudiar o hacer tareas al mismo tiempo.</p>
          </div>
        </div>

        <div class="col-md-6 mb-4">
          <div class="card">
            <div class="card-header">
            <span class="lucide--notebook-pen"></span>
              <h4 class="pt-3">Guarda cosas importantes</h3>
            </div>
            <p class="t1">Con ayuda de las notas podrás guardar información importante y visualizarla en una lista.</p>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
<br>
<br>

  
<!-- Footer -->
<footer class="text-center text-lg-start bg-light text-muted">
  <!-- Section: Links  -->
  <section class="d-flex p-2 border-bottom">
    <div class="container text-center text-md-start mt-5">
      <!-- Grid row -->
      <div class="row mt-3">
        <!-- Grid column -->
        <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
          <!-- Content -->
          <h6 class="text-uppercase fw-bold mb-4">
            <i class="fas fa-gem me-3"></i>HopClass
          </h6>
          <p>
            ¿Qué estás esperando para unirte a HopClass?<br>
            Organiza tus tareas de forma fácil y gratis 
          </p>
        </div>
        <!-- Grid column -->

        <!-- Grid column -->
        <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
          <!-- Links -->
          <h6 class="text-uppercase fw-bold mb-4">
            Enlaces
          </h6>
          <p>
            <a href="#" class="text-reset">Inicio</a>
          </p>
          <p>
            <a href="#notizen" class="text-reset">¿Qué es HopClass?</a>
          </p>
          <p>
            <a href="#registro" data-bs-toggle="modal" class="text-reset">Registro</a>
          </p>
          <p>
            <a href="#sesion" data-bs-toggle="modal"  class="text-reset">Inicio de Sesión</a>
          </p>
        </div>
        <!-- Grid column -->

        <!-- Grid column -->
        <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
          <!-- Links 
          <h6 class="text-uppercase fw-bold mb-4">Contacto</h6>
          <p><i class="fas fa-phone me-3"></i>enrique.moralesz@alumno.buap.mx</p>
          <p><i class="fas fa-phone me-3"></i>luis.castron@alumnos.buap.mx</p>
          <p><i class="fas fa-phone me-3"></i>sergio.rojasg@alumno.buap.mx</p>
          <p><i class="fas fa-phone me-3"></i>melissa.valladaresh@alumno.buap.mx</p>
        </div>-->
        <!-- Grid column -->
      </div>
      <!-- Grid row -->
    </div>
  </section>
  <!-- Section: Links  -->

  <!-- Copyright -->
  <div class="text-center p-4" style="background-color: #27bec2;">
          <div class="container">
                <p class="mb-0" style="color: white;">&copy; 2024 HOPCLASS. Todos los derechos reservados.</p>
          </div>
  </div>
  <!-- Copyright -->
</footer>
<!-- Footer -->

<!-- Inicio Sesión -->
<div class="modal fade" id="sesion">
    <div class="modal-dialog">
        <div class="modal-content row p-3">
            <form action="sesion.php" method="post">
                <!-- Modal Header 
                <div class="modal-header pb-2">
                    <div class="col-8"><img src="img/Icon v2.png" alt="Logo" class="logoig"></div>
                </div>-->
                
                <!-- Modal body -->
                <div class="modal-body row">
                        <div class="col-12">
                            <label for="correo" class="fw-bold col-8 form-label">Correo:</label>
                            <input type="text" name="usuario" class="col-4 form-control " maxlength="50" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                            title="example@example.dominio" required>
                        </div>
                        <div class="pt-3"></div>
                        <div class="col-12">
                            <label for="correo" class="fw-bold col-8 form-label">Contraseña:</label>
                            <div class="input-group">
                              <input type="password" name="pass" id="pass" class="form-control" maxlength="10" required>
                              <a onclick="togglePasswordReg2()"><span class="material-symbols-outlined btn" id="PassIcon2">visibility_off</span></a>
                          </div>
                        </div>
                        <div class="pt-3"></div>
                    <!-- Botón Iniciar Sesión -->
                    <div class="col-12 d-flex justify-content-center">
                        <input type="submit" value="Iniciar Sesión" name="tipo" class="btn btn-primary" id="btn1">
                    </div>
              </form>

                <!-- Olvidé mi contraseña -->
                <div class="col-12 text-end pt-2">
                    <form action="sesion.php" method="post">
                      <center>
                        <button type="submit" class="btn text-decoration-underline" id="btn1">Olvidé mi contraseña</button>
                        <input type="hidden" value="1" name="tipo">
                      </center>
                    </form>
                </div>
            </div>
            
            <!-- Modal footer -->
            <div class="modal-footer justify-content-center pt-2">
                Copyright © Notizen 2023
            </div>
        </div>
    </div>
</div>

<!-- Registro-->
    <div class="modal fade" id="registro">
      <div class="modal-dialog">
          <div class="modal-content row">
              <form action="sesion.php" method="post">
                  <!-- Modal Header 
                  <div class="modal-header">
                      <div class="col-8"><img src="img/Icon v2.png" alt="Logo" class="logoig"></div>
                  </div>-->
                  <!-- Modal body -->
                  <div class="modal-body row">
                    <div class="pt-3"></div>
                    <div class="col-12">
                        <label for="nombre" class="fw-bold col-8 form-label">Nombre:</label>      
                        <input type="text" name="Nombre" class="col-4 form-control" maxlength="50" pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$"
                        title="Uno o más caracteres alfabéticos (mayúsculas o minúsculas)" required>
                    </div>
                    <div class="pt-3"></div>
                    <div class="col-12">
                        <label for="paterno" class="fw-bold col-8 form-label">Apellido Paterno:</label>      
                        <input type="text" name="Apellido_paterno" class="col-4 form-control" maxlength="50" pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$"
                        title="Uno o más caracteres alfabéticos (mayúsculas o minúsculas)" required>
                    </div>
                    <div class="pt-3"></div>
                    <div class="col-12">
                        <label for="materno" class="fw-bold col-8 form-label">Apellido Materno:</label>      
                        <input type="text" name="Apellido_materno" class="col-4 form-control" maxlength="50" pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$"
                        title="Uno o más caracteres alfabéticos (mayúsculas o minúsculas)" required>
                    </div>
                    <div class="pt-3"></div>
                    <div class="col-12">
                      <label for="correo" class="fw-bold col-8 form-label">Correo:</label>      
                      <input type="text" name="usuario" class="col-4 form-control" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                      title="example@example.dominio" required>
                    </div>
                    <div class="pt-3"></div>
                    <div class="col-12">
                        <label for="contraseña" class="fw-bold col-8 form-label">Contraseña:</label>
                        <div class="input-group">
                        <input type="password" name="pass" id="passw" class="col-4 form-control" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()-_=+{};:,<.>]).{6,10}$" maxlength="10"
                        title="- Al menos una letra minúscula. - Al menos una letra mayúscula. - Al menos un dígito. - Una longitud de máximo 10 y mínimo 6 caracteres" required>
                        <a onclick="togglePasswordReg1()"><span class="material-symbols-outlined btn" id="PassIcon1">visibility_off</span></a>
                        </div>
                    </div>
                    <div class="pt-3"></div>
                    <div class="col-12">
                        <label for="confirmar" class="fw-bold col-8 form-label">Confirmar Contraseña:</label>
                        <div class="input-group">      
                        <input type="password" name="confpass" id="confpassw" class="col-4 form-control" maxlength="10" required>
                        <a onclick="togglePasswordReg2()"><span class="material-symbols-outlined btn" id="PassIcon2">visibility_off</span></a>
                        </div>
                    </div>
                    <div class="pt-3"></div> 
                    <div class="col-12 d-flex justify-content-center">
                      <input type="submit" value="Crear Cuenta" name="tipo" class="btn btn-primary" id="btn1">
                    </div>
                  </div>
              </form>
              <!-- Modal footer -->
              <div class="modal-footer justify-content-center">
                  Copyright © Notizen 2023
              </div>
          </div>
      </div>
  </div>

</body>
</html>