<?php
    ob_start();
    include "conexiondb.php";
    include "toast.php";

    if (isset($_POST['tipo'])) {
        $tipo = $_POST['tipo'];
    }
    else {
        $tipo = "4";
    }

    switch ($tipo) {
        case '1':
            crearSemestre($conexion);
            break;
        case '2':
            eliminarSemestre($conexion);
            break;
        case '3':
            modSemestre($conexion);
            break; 
        case '4':
            loadSemestres($conexion);
            break;
        case '5':
            modificarSemestre($conexion);
            break;   
        default:
            header('location: principal.php');
            break;
    }


    function crearSemestre($conexion) {
        session_start();
        $semestre = $_POST['semestre'];
        $num = $_POST['num'];

        $usuario = $_SESSION["id_usuario"];

        $sql = "INSERT INTO semestre(id_usuario, nombre_semestre, numero_semestre) VALUES
                ('$usuario', '$semestre', '$num')";
                
        if(($result = $conexion->query($sql)) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conexion->error;
        }
        
        $toastmensaje = toast(TRUE, "Semestre Creado Correctamente");
        $_SESSION["toastmesaje"] = $toastmensaje;
        header('location: principal.php');
    }

    function loadSemestres($conexion) {
        $usuario = $_SESSION["id_usuario"];
        
        $sql = "SELECT id_semestre, nombre_semestre, numero_semestre FROM semestre
        WHERE id_usuario = $usuario
        ORDER BY numero_semestre ASC;";
                
        if(($result = $conexion->query($sql)) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conexion->error;
        }

        $result = mysqli_query($conexion, $sql);
        
        while ($allsemestres = mysqli_fetch_assoc($result)) {
            echo '
            <div class="col-md-6">
                <div class="container pb-3">
                    <div class="card">
                        <div class="row p-2">
                            <div class="col-6 text-center">
                                ' . $allsemestres["nombre_semestre"] . '
                            </div>
                            <div class="col-6 text-center">
                                Semestre ' . $allsemestres["numero_semestre"] . '
                            </div>
                        </div>
                        <div class="row p-2 mt-3">
                            <div class="col-6 text-start">
                                <form action="semestre.php" method="post" style="margin-bottom: 0px;">
                                    <input type="hidden" value="2" name="tipo">
                                    <input type="hidden" value="'. $allsemestres["id_semestre"] .'" name="id_semestre">
                                    <button type="submit" class="btn material-symbols-outlined" id="btn">delete_forever</button>
                                </form>
                            </div>
                            <div class="col-6 text-end">
                                <form action="semestre.php" method="post" style="margin-bottom: 0px;">
                                    <input type="hidden" value="3" name="tipo">
                                    <input type="hidden" value="'. $allsemestres["id_semestre"] .'" name="id_semestre">
                                    <button type="submit" class="btn material-symbols-outlined" id="btn">edit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        }
    }

    function eliminarSemestre($conexion) {
        session_start();
        $semestre = $_POST['id_semestre'];

        $sql = "SELECT materia.id_materia FROM materia 
        INNER JOIN semestre ON materia.id_semestre = semestre.id_semestre 
        WHERE semestre.id_semestre = '$semestre';";

        if(($result = $conexion->query($sql)) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conexion->error;
        }

        $result = $conexion->query($sql);

        while(($allmaterias = mysqli_fetch_assoc($result)) !== NULL) {
            $materia = $allmaterias["id_materia"];
            $sql1 = "DELETE FROM materia WHERE id_materia = '$materia';";
                    
            if(($result1 = $conexion->query($sql1)) === FALSE) {
                echo "Error: " . $sql1 . "<br>" . $conexion->error;
            }
        }

        $sql2 = "DELETE FROM semestre
        WHERE id_semestre = $semestre;";
                
        if(($result2 = $conexion->query($sql2)) === FALSE) {
            echo "Error: " . $sql2 . "<br>" . $conexion->error;
        }        

        $toastmensaje = toast(TRUE, "Semestre Eliminado Correctamente");
        $_SESSION["toastmesaje"] = $toastmensaje;
        header('location: semestredetalles.php');
    }

    function modSemestre($conexion) {
        $semestre = $_POST['id_semestre'];

        $sql = "SELECT nombre_semestre, numero_semestre FROM semestre
        WHERE id_semestre = '$semestre';";
                
        if(($result = $conexion->query($sql)) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conexion->error;
        }

        $result = mysqli_query($conexion, $sql);
        $allsem = mysqli_fetch_assoc($result);

        $name = $allsem["nombre_semestre"];
        $des = $allsem["numero_semestre"];
        
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
                        <form action="semestre.php" method="post">
                            <div class="card-header row">
                                <div class="col-6"><img src="img/Icon v2.png" alt="Logo" class="logoig"></div>
                                <div class="col-6 pt-3 text-end"><input type="submit" value="Modificar Semestre" class="btn" id="btn"></div>                   
                            </div>
                            <div class="card-body row">
                                <div class="col-md-6 mx-auto">
                                <div class="container">
                                    <label for="nuevoNombreSemestre" class="fw-bold form-label">Nombre Semestre:</label>
                                    <input type="text" name="nombre_Semestre" value="'. $name .'" class="form-control" maxlength="50" pattern="^(?=.*[a-zA-Z0-9])(?! )[\sa-zA-ZáéíóúÁÉÍÓÚñÑ0-9!@#$%^&*()_+\-=[\]{};:\\|,.<>/?]{1,50}$"
                                    title="Letras mayúsculas, minúsculas, números, carácteres especiales y espacios permitidos.Máximo 50." require>
                                    <div class="pt-4"></div>
                                    <label for="numSemestre" class="fw-bold form-label">Número Semestre:</label>
                                    <input type="number" name="numero_Semestre" value="'. $des .'" class="form-control" min=1 require>
                                    <input type="hidden" value="5" name="tipo">
                                    <input type="hidden" value="'. $semestre .'" name="id_semestre">
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

    function modificarSemestre($conexion) {
        session_start();
        $semestre = $_POST['id_semestre'];
        $nombre = $_POST['nombre_Semestre'];
        $num = $_POST['numero_Semestre'];

        $sql = "UPDATE semestre 
        SET nombre_semestre = '$nombre', numero_semestre = '$num'
        WHERE id_semestre = '$semestre';";

        if(($result = $conexion->query($sql)) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conexion->error;
        }

        $toastmensaje = toast(TRUE, "Semestre Modificado Correctamente");
        $_SESSION["toastmesaje"] = $toastmensaje;
        
        header('location: semestredetalles.php');
    }
?>