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
        <title>Mi Panel-Tareas</title>
        <link rel="icon" href="img/hop.png">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link href="https://fonts.googleapis.com/css2?family=Encode+Sans+Semi+Condensed:wght@600&display=swap" rel="stylesheet">
        
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Abril+Fatface|Poppins">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"/>

        <link rel="stylesheet" href="css/principal.css">
        <link rel="stylesheet" href="css/notificaciones.css">
        
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="js/notificacion.js"></script>
        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous' defer></script>
	    <!--Compartir tarea-->
        <script src="js/compartir.js"></script>
        <script src="js/scriptNotificaciones.js"></script>
    </head>
    <body>
    <div class="p-3 navbar-custom">
    <div class="container-fluid pb-3">
        <div class="offcanvas offcanvas-start" id="demo">
            <div class="offcanvas-header">
                <span class="fluent--animal-rabbit-24-filled"></span><div class="logo"><b> HOPCLASS</b></div>
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
            <div class="col-12 d-flex align-items-center ms-1">
                <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#demo" id="btn"> 
                    <i class="fas fa-bars"></i>
                </button>
            
                <span class="fluent--animal-rabbit-24-filled ms-3"></span>
                <div class="logo ms-2"><b>HOPCLASS</b></div>
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
                        <div class="col-6"><h3>Mis tareas</h3></div>  
                        <div class="col-6 text-end"><a href="principal.php" class="btn pt-2 mt-1" id="btn">Regresar</a></div>                   
                    </div>
                    <div class="card-body row">
                        <form id="form1">
                            <div class="form-group">
                                <label for="materia">Busca por:</label>
                                <select class="form-select" id="materia">
                                    <?php require "materia.php"; loadSelMateria($conexion);?> <!-- Funcion que nos carga las materias en el filtro -->
                                </select>    
                            </div>
                            <input type="hidden" id="tipo" value="4">
                            <button type="submit" class="btn mt-1" id="btn">Buscar</button>
                        </form>
                        <div class="text-end pe-3" id="ico"><a href="#creatarea" data-bs-toggle="modal" class="btn" id="btn">Nueva Tarea</a></div>
                    </div>
                    <div class="card-body row">
                        <div class="card col-lg-6" id="resultados">
                            <?php require "tarea.php"; loadTarea($conexion); ?>
                        </div>
                        <div class="card col-lg-5 mx-auto" id="resultadosSubtarea">
                            <div class="card-header bg-white">
                                <div class="row" >
                                    <div class="col-12 text-start">
                                        Subtareas
                                    </div>                                  
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-2 text-end formend">
                        <a href="#ir" class="btn m-2" id="btn">Ir Arriba</a>
                    </div>
                </div>
        </div>
    </div>

        <div class="modal fade" id="creatarea">
            <div class="modal-dialog row">
                <div class="modal-content col-ls-12">
                    <form id="formB" action="tarea.php" method="post" onsubmit="return validarFormulario2()">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <div class="col-6"><img src="img/Icon v2.png" alt="Logo" class="logoig"></div>
                            <div class="col-6 text-end"><input type="submit" value="Crear Tarea" class="btn" id="btn1"></div>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body row">
                            <div class="col-12">   
                                <label for="id_materia" class="fw-bold col-8 form-label">Para la materia:</label>
                                <select class="form-select" name="id_materia" id="id_materia">
                                    <?php require_once "materia.php"; loadSelMateria($conexion);?>
                                </select>
                            </div>
                            <div class="col-12 pt-2">
                                <label for="nombre_tarea" class="fw-bold col-8 form-label">Nombre Tarea:</label>
                                <input type="text" name="nombre_tarea" class="col-4 form-control" maxlength="50" pattern="^(?=.*[a-zA-Z0-9])(?! )[\sa-zA-ZáéíóúÁÉÍÓÚñÑ0-9!@#$%^&*()_+\-=[\]{};':\\|,.<>/?]{1,50}$"
                                title="Letras mayúsculas, minúsculas, números, carácteres especiales y espacios permitidos" required>
                            </div>
                            <div class="col-12 pt-2">
                                <label for="observacion" class="fw-bold col-8 form-label">Descripción Tarea:</label>
                                <textarea name="observacion" class="col-4 form-control" style="height:100px;" maxlength="50" pattern="^(?=.*[a-zA-Z0-9])(?! )[\sa-zA-ZáéíóúÁÉÍÓÚñÑ0-9!@#$%^&*()_+\-=[\]{};':\\|,.<>/?]{1,100}$" required></textarea>
                            </div>
                            <div class="col-12 pt-2">
                                <!-- AQUI PUSE LOS BOTONES DE PRIORIDAD -->
                                <label for="prioridad" class="fw-bold col-8 form-label">Prioridad:</label>
                                    <select class="form-select" name="prioridad" id="prioridad">
                                        <option value="1">Baja</option>
                                        <option value="2">Media</option>
                                        <option value="3">Alta</option>
                                    </select>
                            </div>
                            <!-- Poner fecha -->
                            <div class="col-12 pt-3">
                                <label for="fechaTarea" class="fw-bold col-12 form-label">Inicio:</label>
                                <input type="date" class="form-control" id="fechaTarea" name="fechaTarea"
                                    min=" <?php echo date('Y-m-d'); ?>" title="Ingrese fecha de inicio en el formato indicado" required>
                                <input class="form-control" type="time" name="horaTarea" id="horaTarea" title="Ingrese hora de inicio en formato de 24 horas" required>
                            </div>
                                <!-- BOTON PARA CREAR NOTIFICACIONES -->
                            <div class="col-12">
                            <div id="notificacionesDiv" class="col-12 pt-1" style="display: none" >
                                    
                            </div>
                            <button id="btnNotificacion" type="button" onclick="crearNot(0, 'notificacionesDiv')" class="btn mt-3 mb-2">Crear Nueva Notificación</button>
                            </div>
                            <div class="col-12 pt-2">
                                <label for="compartir" class="fw-bold col-8 form-label">Compartir tarea:</label>
                                    <select type="text" class="form-select" name="compartir" id="compartir">
                                        <option value="0">No</option>
                                        <option value="1">Si</option>
                                    </select>
                                <label for="amigos" class="fw-bold col-8 form-label pt-2">Amigo:</label>
                                    <select type="text" name="amigos" id="amigos" class="form-select" aria-label="Disabled select example" disabled>
                                        <?php require "compartido/amigos.php"; ?>
                                    </select>
                            </div>  
                            <input type="hidden" value="1" name="tipo">
                            <div class="pt-3"></div>
                        </div>                        
                    </form>
                </div>
            </div>
        </div>

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


        <div class="modal fade" id="alertas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <div class="col-6"><img src="img/Icon v2.png" alt="Logo" class="logoig"></div>
                    <div class="col-6 text-end"><button class="btn-close me-2" data-bs-dismiss="modal" aria-label="Close" id="btn1" ></button></div>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">Debes seleccionar una Opción Válida</div>
                    </div>
                </div>
            </div>
        </div>

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
                        <div class="modal-body row" id="obtenOie">
                            
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
            </div>
        </div>
    </div>
    <script id="cargaNoti">actualizaNoti();</script>

        <script>
            $(document).ready(function() {
                $('#form1').submit(function(event) { //Envia el formulario para encontrar tareas por materia
                    // Prevenir el comportamiento predeterminado del formulario
                    event.preventDefault();
                    
                    // Obtener el valor de la fecha del campo de entrada
                    var materia = $('#materia').val();
                    var tipo = $('#tipo').val();
                    
                    if (!validarFormulario()) {
                        event.preventDefault(); // Evita el envío del formulario
                        return;
                    }

                    // Enviar una solicitud de búsqueda al servidor utilizando AJAX
                    $.ajax({
                    url: 'tarea.php',
                    method: 'POST',
                    data: { materia: materia, tipo: tipo },
                    success: function(result) {
                        // Mostrar los resultados de la búsqueda en la página
                        $('#resultados').html(result);
                        $('#resultadosSubtarea').html('<div class="card-header bg-white"><div class="row" ><div class="col-12 text-start">Subtareas</div></div></div>');
                    }
                    });
                });
                
                $(document).on('submit', 'form:not(#form1, #formB, #formC, #formD, #formE, #formF, #formG, #formH, #formI, #formJ, #formK, #formL, #formM, #completo, #completo1, .subcom, .subcomdos, .subcomtres, #Aceptar)',function(event) { //Aquí envía los formularios de los cards de cada tarea para mostrar subtareas
                    if ($(this).attr('id') !== 'form1') {
                    event.preventDefault(); }
                    
                    //Borra lo que tenga impreso anteriormente
                    $('#resultadosSubtarea').html('');

                    // Obtener el valor de la fecha del campo de entrada
                    var tarea = $(this).find('input[name="tarea"]').val();
                    var tipo = $('#tipo').val();

                    // Enviar una solicitud de búsqueda al servidor utilizando AJAX
                    $.ajax({
                        url: 'subtarea.php',
                        method: 'POST',
                        data: { tarea: tarea, tipo: tipo },
                        success: function(result) {
                            // Mostrar los resultados de la búsqueda en la página
                            $('#resultadosSubtarea').html(result);
                            window.location.href = '#resultadosSubtarea';
                            $('#obtenOie').load('subtarea.php', function() {
                                obtenID();
                            });
                        }
                    });
                });
            });

            function obtenID() { //Aquí se obtienen los ID de tareas y se crea el formulario para crear subtareas (MODAL)
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

            function validarFormulario() {
                var Seleccionada = document.getElementById("materia").value;
                if (Seleccionada === "") {
                    $('#alertas').modal('show'); // Inserta la alerta en el elemento con el ID "alertas"
                    return false; // evita el envío del formulario
                }
                return true; // permite el envío del formulario
            }

            function validarFormulario2() {
                var Seleccionada = document.getElementById("id_materia").value;
                if (Seleccionada === "") {
                    $('#alertas').modal('show'); // Inserta la alerta en el elemento con el ID "alertas"
                    return false; // evita el envío del formulario
                }
                else if(Seleccionada == 1 || Seleccionada == 2)
                {
                    $('#alertas').modal('show'); // Inserta la alerta en el elemento con el ID "alertas"
                    return false; // evita el envío del formulario
                }
                return true; // permite el envío del formulario
            }
        </script>
        <!-- Footer -->
        <footer class="footer text-center py-3 mt-5">
            <div class="container">
                <p class="mb-0">&copy; 2024 HOPCLASS. Todos los derechos reservados.</p>
            </div>
        </footer>

    </body>
</html>