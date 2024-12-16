<?php
// Iniciar sesión para acceder a las variables de sesión
session_start();

// Conexión a la base de datos (ajusta según tu configuración)
include 'conexiondb.php';

// Verificar si la conexión fue exitosa
if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

// Verificar si el usuario está logueado antes de acceder a $_SESSION
if (!isset($_SESSION['id_usuario'])) {
    // Redirigir al login o mostrar un mensaje de error
    echo "No estás logueado.";
    exit; // Detener la ejecución del código si no está logueado
}

// Obtener el id del usuario desde la sesión
$id_usuario = $_SESSION['id_usuario'];

// Consulta para obtener las tareas pendientes
$query_tareas = "SELECT completada, nombre_tarea FROM tarea WHERE completada = '0' AND id_usuario = '$id_usuario'";
$result_tareas = mysqli_query($conexion, $query_tareas);

// Almacenar las tareas pendientes en una variable
$tareasPendientes = [];
if ($result_tareas) {
    $tareasPendientes = mysqli_fetch_all($result_tareas, MYSQLI_ASSOC);
    mysqli_free_result($result_tareas); // Liberar el resultado de la consulta
} else {
    echo "Error en la consulta de tareas: " . mysqli_error($conexion);
}

// Consulta para obtener las notas (nombre y descripción)
$query_notas = "SELECT nombre_nota, descripcion_nota FROM nota WHERE id_usuario = '$id_usuario'";
$result_notas = mysqli_query($conexion, $query_notas);

$notas = [];
if ($result_notas && mysqli_num_rows($result_notas) > 0) {
    // Almacenar las notas en una variable
    while ($nota = mysqli_fetch_assoc($result_notas)) {
        $notas[] = $nota;
    }
    mysqli_free_result($result_notas); // Liberar el resultado de la consulta
} else {
    echo "";
}

// Consulta para obtener las tareas completadas y sus detalles
$query_tareas_completadas = "SELECT  nombre_tarea, observacion FROM tarea WHERE completada = '1' AND id_usuario = '$id_usuario'";
$result_tareas_completadas = mysqli_query($conexion, $query_tareas_completadas);

$historialTareasCompletadas = [];
if ($result_tareas_completadas) {
    $historialTareasCompletadas = mysqli_fetch_all($result_tareas_completadas, MYSQLI_ASSOC);
    mysqli_free_result($result_tareas_completadas); // Liberar el resultado de la consulta
} else {
    echo "Error en la consulta de tareas completadas: " . mysqli_error($conexion);
}

// Consulta para obtener las tareas compartidas y el nombre del usuario compartido
$query_tareas_compartidas = "   SELECT t.nombre_tarea, u.nombre AS usuario_compartido
    FROM tarea AS t
    INNER JOIN compartir AS c ON t.id_compartir = c.id_compartir
    INNER JOIN usuario AS u ON c.id_amigo = u.id_usuario
    WHERE t.id_usuario = '$id_usuario' AND c.id_amigo = u.id_usuario AND t.id_compartir != 0
";

$result_tareas_compartidas = mysqli_query($conexion, $query_tareas_compartidas);
$tareasCompartidas = [];
if ($result_tareas_compartidas) {
    $tareasCompartidas = mysqli_fetch_all($result_tareas_compartidas, MYSQLI_ASSOC);
    mysqli_free_result($result_tareas_compartidas);
} else {
    echo "Error en la consulta de tareas compartidas: " . mysqli_error($conexion);
}

// Consulta para obtener los nombres de los amigos donde el estado es 1
$query_amigos = "SELECT u.nombre
    FROM amigo AS a
    INNER JOIN usuario AS u ON a.id_amigo = u.id_usuario
    WHERE a.id_usuario = '$id_usuario' AND a.estado = '1'
";
$result_amigos = mysqli_query($conexion, $query_amigos);
$contactosRecientes = [];
if ($result_amigos) {
    $contactosRecientes = mysqli_fetch_all($result_amigos, MYSQLI_ASSOC);
    mysqli_free_result($result_amigos);
} else {
    echo "Error en la consulta de amigos: " . mysqli_error($conexion);
}

include "seguridad.php";

// Verificar y mostrar el mensaje del toast si existe
if (isset($_SESSION["toastmesaje"])) {
    echo $_SESSION["toastmesaje"];
    unset($_SESSION["toastmesaje"]); // Eliminar la variable de sesión del mensaje del toast
}

// Cerrar la conexión a la base de datos
mysqli_close($conexion);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Panel</title>
    <link rel="icon" href="img/Icon v2.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"/>
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Abril+Fatface|Poppins">
    <link href="https://fonts.googleapis.com/css2?family=Encode+Sans+Semi+Condensed:wght@600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/principal.css">
    <link rel="stylesheet" href="css/notificaciones.css">
    <link rel="stylesheet" href="css/tarjetas.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <script src="js/js1.js"></script>
    <script src="js/notificacion.js"></script>

    <script>
        Notification.requestPermission().then(function(permission) {
            if (permission !== "granted") {
                // Si el permiso no se ha otorgado, abrir el modal de Bootstrap
                $('#modalNoti').modal('show');
            }
        });
    </script>

</head>
<body>

<!--Se crea un separador para el menu y las notificaciones-->
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
</div>
 </div>
    <!-- Panel de Tarjetas -->
    <div class="container-fluid">
    <div class="row">

       <!-- Tarjeta de Tareas Pendientes -->
       <div class="col-12 col-md-6 col-lg-4 mb-3">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">Tareas Pendientes</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre de la Tarea</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if (isset($tareasPendientes) && count($tareasPendientes) > 0) {
                            $counter = 1;
                            $limit = 3; // Limitar a 3 tareas
                            foreach ($tareasPendientes as $tarea) {
                                if ($counter <= $limit) {
                                    echo '<tr>';
                                    echo '<td>' . $counter++ . '</td>';
                                    echo '<td>' . $tarea['nombre_tarea'] . '</td>';
                                    echo '</tr>';
                                }
                            }

                            // Si hay más de 3 tareas, mostrar el botón "Ver más"
                            if (count($tareasPendientes) > $limit) {
                                echo '<tr><td colspan="2" class="text-center"><a href="tareadetalle.php" class="btn btn-primary">Ver más</a></td></tr>';
                            }
                        } else {
                            echo '<tr><td colspan="2">No hay tareas pendientes.</td></tr>';
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

  <!-- Tareas Compartidas -->
<div class="col-12 col-md-6 col-lg-4 mb-3">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-white">Tareas Compartidas</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre de la Tarea</th>
                        <th>Compartido</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if (isset($tareasCompartidas) && count($tareasCompartidas) > 0) {
                            $counter = 1;
                            $limit = 3; // Limitar a 3 tareas
                            foreach ($tareasCompartidas as $tarea) {
                                if ($counter <= $limit) {
                                    echo '<tr>';
                                    echo '<td>' . $counter++ . '</td>';
                                    echo '<td>' . $tarea['nombre_tarea'] . '</td>';
                                    echo '<td>' . $tarea['usuario_compartido'] . '</td>';
                                    echo '</tr>';
                                }
                            }

                            // Si hay más de 3 tareas compartidas, mostrar el botón "Ver más"
                            if (count($tareasCompartidas) > $limit) {
                                echo '<tr><td colspan="3" class="text-center"><a href="compartida.php.php" class="btn btn-warning">Ver más</a></td></tr>';
                            }
                        } else {
                            echo '<tr><td colspan="3">No hay tareas compartidas.</td></tr>';
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Notas -->
<div class="col-12 col-md-6 col-lg-4 mb-3">
    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">Notas</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre de la Nota</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if (isset($notas) && count($notas) > 0) {
                            $counter = 1;
                            $limit = 3; // Limitar a 3 notas
                            foreach ($notas as $nota) {
                                if ($counter <= $limit) {
                                    echo '<tr>';
                                    echo '<td>' . $counter++ . '</td>';
                                    echo '<td>' . $nota['nombre_nota'] . '</td>';
                                    echo '<td>' . substr($nota['descripcion_nota'], 0, 100) . '...</td>';
                                    echo '</tr>';
                                }
                            }

                            // Si hay más de 3 notas, mostrar el botón "Ver más"
                            if (count($notas) > $limit) {
                                echo '<tr><td colspan="3" class="text-center"><a href="notadetalle.php" class="btn btn-secondary">Ver más</a></td></tr>';
                            }
                        } else {
                            echo '<tr><td colspan="3">No hay notas disponibles.</td></tr>';
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

  <!-- Historial de Tareas Completadas -->
<div class="col-12 col-md-6">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">Historial de Tareas Completadas</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre de la Tarea</th>
                        <th>Detalles</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if (isset($historialTareasCompletadas) && count($historialTareasCompletadas) > 0) {
                            $counter = 1;
                            $limit = 3; // Limitar a 3 tareas completadas
                            foreach ($historialTareasCompletadas as $tarea) {
                                if ($counter <= $limit) {
                                    echo '<tr>';
                                    echo '<td>' . $counter++ . '</td>';
                                    echo '<td>' . $tarea['nombre_tarea'] . '</td>';
                                    echo '<td>' . $tarea['observacion'] . '</td>';
                                    echo '</tr>';
                                }
                            }

                            // Si hay más de 3 tareas, mostrar el botón "Ver más"
                            if (count($historialTareasCompletadas) > $limit) {
                                echo '<tr><td colspan="3" class="text-center"><a href="tareadetalle.php" class="btn btn-secondary">Ver más</a></td></tr>';
                            }
                        } else {
                            echo '<tr><td colspan="3">No hay tareas completadas en el historial.</td></tr>';
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Contactos Recientes -->
<div class="col-12 col-md-6">
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">Amigos</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre del Contacto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if (isset($contactosRecientes) && count($contactosRecientes) > 0) {
                            $counter = 1;
                            $limit = 3; // Limitar a 3 contactos recientes
                            foreach ($contactosRecientes as $contacto) {
                                if ($counter <= $limit) {
                                    echo '<tr>';
                                    echo '<td>' . $counter++ . '</td>';
                                    echo '<td>' . $contacto['nombre'] . '</td>';
                                    echo '</tr>';
                                }
                            }

                            // Si hay más de 3 contactos, mostrar el botón "Ver más"
                            if (count($contactosRecientes) > $limit) {
                                echo '<tr><td colspan="2" class="text-center"><a href="amigos.php" class="btn btn-secondary">Ver más</a></td></tr>';
                            }
                        } else {
                            echo '<tr><td colspan="2">No hay contactos recientes.</td></tr>';
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div> 
    <div class="modal fade" id="eliminar">
        <div class="modal-dialog row">
            <div class="modal-content col-ls-12">
                <form id="formI" action="sesion.php" method="post">
                     Modal Header 
                    <div class="modal-header">
                        <div class="col-6"><img src="img/Icon v2.png" alt="Logo" class="logoig"></div>
                        <div class="col-6 text-end"><input type="submit" value="Eliminar Cuenta" name="tipo" class="btn" id="btn1"></div>
                    </div>
                    Modal body 
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
                    Copyright © HopClass 2024
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalNoti" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
    <script id="cargaNoti">actualizaNoti();</script>
</body>
</html>