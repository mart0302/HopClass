<?php
    ob_start();
    include "conexiondb.php";
    include "toast.php";

    if (isset($_POST['tipo'])) {
        $tipo = $_POST['tipo'];
    }
    else {
        $tipo = "2"; //Lectura de notas
    }

    switch ($tipo) {
        case '1':
            crearNota($conexion);
            break;
        case '2':
            //load($conexion, 0); Ya no cargará la vista automaticamente, ahora se llama a la función desde el HTML para que cargue la vista desde principal.php y otra desde notadetalle.php
            break;
        case '3':
            eliminaNota($conexion);
            break;
        case '4':
            modificarNota($conexion);
            break;
        case '5':
            loadNota($conexion);
            break;
        case '6':
            buscarNota($conexion);
            break;    
        default:
            header('location: principal.php');
            break;
    }

    function crearNota($conexion) {
        session_start();
        $titulo = $_POST['nombreNota'];
        $des = $_POST['descrNota'];

        $usuario = $_SESSION["id_usuario"];

        $sql = "INSERT INTO nota(id_usuario, nombre_nota, descripcion_nota, fecha_nota) VALUES
                ('$usuario', '$titulo', '$des', now())";
                
        if(($result = $conexion->query($sql)) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conexion->error;
        }
        
        $toastmensaje = toast(TRUE, "Nota Creada Correctamente");
        $_SESSION["toastmesaje"] = $toastmensaje;

        if(isset($_POST['esI']) == 1) {
            header('location: notadetalle.php');
            exit();
        }

        header('location: principal.php');
    }
    
    function load($conexion, $esI){
        $usuario = $_SESSION["id_usuario"];
        
        if($esI == 1) { 
            $a = $esI;
            $sql = "SELECT id_nota, nombre_nota, descripcion_nota FROM nota
            WHERE id_usuario = $usuario
            ORDER BY fecha_nota DESC;";
        }
        else {
            $a = 0;
            $sql = "SELECT id_nota, nombre_nota, descripcion_nota FROM nota
            WHERE id_usuario = $usuario
            ORDER BY fecha_nota DESC
            LIMIT 5;";
        }

        
                
        if(($result = $conexion->query($sql)) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conexion->error;
        }

        $result = mysqli_query($conexion, $sql);
        
        while ($allnotas = mysqli_fetch_assoc($result)) {
            echo '
                <div class="card-body">    
                    <div class="mb-1">' . $allnotas["nombre_nota"] . '</div>
                    <div>' . $allnotas["descripcion_nota"] . '</div>
                </div>
                <div class="card-footer bg-light text-muted">
                    <div class="row">
                        <div class="col-6">
                            <form action="notas.php" method="post" style="margin-bottom: 0px;">
                                <input type="hidden" value="3" name="tipo">
                                <input type="hidden" value="'. $a .'" name="esI">
                                <input type="hidden" value="'. $allnotas["id_nota"] .'" name="id_nota">
                                <button type="submit" class="btn material-symbols-outlined" id="btn">delete_forever</button>
                            </form>    
                        </div>
                        <div class="col-6 text-end">
                            <form action="notas.php" method="post" style="margin-bottom: 0px;">
                                <input type="hidden" value="5" name="tipo">
                                <input type="hidden" value="'. $a .'" name="esI">
                                <input type="hidden" value="'. $allnotas["id_nota"] .'" name="id_nota">
                                <button type="submit" class="btn material-symbols-outlined" id="btn">edit</button>
                            </form>
                        </div>
                    </div>
                </div>
            <hr>';
        }
    }

    function eliminaNota($conexion) {
        session_start();
        $idNota = $_POST['id_nota'];

        $sql = "DELETE FROM nota
        WHERE id_nota = '$idNota';";
                
        if(($result = $conexion->query($sql)) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conexion->error;
        }
        
        $toastmensaje = toast(TRUE, "Nota Eliminada Correctamente");
        $_SESSION["toastmesaje"] = $toastmensaje;

        if($_POST['esI'] == 1) {
            header('location: notadetalle.php');
            exit();
        }
        
        header('location: principal.php');
    }

    function loadNota($conexion) {
        $idNota = $_POST['id_nota'];

        if($_POST['esI'] == 1) { 
            $a = $_POST['esI'];
        }
        else {
            $a = 0;
        }

        $sql = "SELECT nombre_nota, descripcion_nota FROM nota
        WHERE id_nota = '$idNota';";
                
        if(($result = $conexion->query($sql)) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conexion->error;
        }

        $result = mysqli_query($conexion, $sql);
        $allnotas = mysqli_fetch_assoc($result);

        $name = $allnotas["nombre_nota"];
        $des = $allnotas["descripcion_nota"];
        
        echo '
        <html>
        <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mi Panel</title>
        <link rel="icon" href="img/Icon v2.png">
        <link rel="stylesheet" href="css/principal.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Abril+Fatface|Poppins">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        </head>
        <body>
            <div class="container-fluid mt-5">
                <div class="row">
                    <div class="card col-md-8 mx-auto">
                        <form action="notas.php" method="post">
                            <div class="card-header row">
                                <div class="col-6"><img src="img/Icon v2.png" alt="Logo" class="logoig"></div>
                                <div class="col-6 pt-2 text-end"><input type="submit" value="Modificar Nota" class="btn" id="btn1"></div>                   
                            </div>
                            <div class="card-body row">
                                <div class="col-md-6 mx-auto">
                                <div class="container">
                                    <label for="nuevoNombreNota" class="fw-bold form-label">Nombre Nota:</label>
                                    <input type="text" name="nombre_nota" value="'. $name .'" class="form-control" maxlength="50" pattern="^(?=.*[a-zA-Z0-9])[\sa-zA-ZáéíóúÁÉÍÓÚñÑ0-9!@#$%^&*_+-={}?]{1,50}$"
                                    title="Letras mayúsculas, minúsculas, números, carácteres especiales y espacios permitidos" required>
                                    <div class="pt-4"></div>
                                    <label for="nuevaDescrNota" class="fw-bold form-label">Descripción Nota:</label>
                                    <textarea name="descripcion_nota" class="form-control" style="height:150px;" maxlength="100" pattern="^(?=.*[a-zA-Z0-9])[\sa-zA-ZáéíóúÁÉÍÓÚñÑ0-9!@#$%^&*_+-={}?]{1,100}$"
                                    required>'. $des .'</textarea>
                                    <input type="hidden" value="4" name="tipo">
                                    <input type="hidden" value="'. $a .'" name="esI">
                                    <input type="hidden" value="'. $idNota .'" name="id_nota">
                                    <div class="pt-3"></div>
                                </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </body>
        </html>';
    }

    function modificarNota($conexion) {
        session_start();
        $idNota = $_POST['id_nota'];
        $newNombre = $_POST['nombre_nota'];
        $newDes = $_POST['descripcion_nota'];

        $sql = "UPDATE nota 
        SET nombre_nota = '$newNombre', descripcion_nota = '$newDes', fecha_nota = now()
        WHERE id_nota = '$idNota';";
                
        if(($result = $conexion->query($sql)) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conexion->error;
        }
        
        $toastmensaje = toast(TRUE, "Nota Modificada Correctamente");
        $_SESSION["toastmesaje"] = $toastmensaje;

        if($_POST['esI'] == 1) {
            header('location: notadetalle.php');
            exit();
        }
        
        header('location: principal.php');
    }

    function buscarNota($conexion) {
        session_start();
        $usuario = $_SESSION["id_usuario"];

        // Obtener la fecha enviada por la solicitud AJAX
        $fecha = $_POST['fecha'];

        if($fecha > 12) {
            load($conexion, 1);
            exit();
        }
            // Hacer una consulta SQL para buscar los registros que coincidan con la fecha especificada
            $sql = "SELECT id_nota, nombre_nota, descripcion_nota, DATE_FORMAT(fecha_nota, '%d-%m-%Y') FROM nota
            WHERE MONTH(fecha_nota) = $fecha AND id_usuario = $usuario
            ORDER BY fecha_nota DESC";
            $resultado = mysqli_query($conexion, $sql);

        // Mostrar los resultados de la búsqueda
        while ($fila = mysqli_fetch_assoc($resultado)) {
            echo '
            <div class="card-body row mx-auto"> 
                <div class="col-6 text-center">
                    ' . $fila["nombre_nota"] . '
                </div>
                <div class="col-6 text-center">
                    Fecha: ' . $fila["DATE_FORMAT(fecha_nota, '%d-%m-%Y')"] . '
                </div>
                <div class="col-12 mt-3 text-center">
                    ' . $fila["descripcion_nota"] . '
                </div>
            </div>
                <div class="card-footer bg-light text-muted">
                    <div class="row">
                            <div class="col-6">
                                <form action="notas.php" method="post" style="margin-bottom: 0px;">
                                    <input type="hidden" value="3" name="tipo">
                                    <input type="hidden" value="1" name="esI">
                                    <input type="hidden" value="'. $fila["id_nota"] .'" name="id_nota">
                                    <button type="submit" class="btn material-symbols-outlined" id="btn">delete_forever</button>
                                </form>    
                            </div>
                            <div class="col-6 text-end">
                                <form action="notas.php" method="post" style="margin-bottom: 0px;">
                                    <input type="hidden" value="5" name="tipo">
                                    <input type="hidden" value="1" name="esI">
                                    <input type="hidden" value="'. $fila["id_nota"] .'" name="id_nota">
                                    <button type="submit" class="btn material-symbols-outlined" id="btn">edit</button>
                                </form>
                            </div>
                    </div>
                </div><hr>';
        }
    }
?>