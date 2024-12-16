<?php
    ob_start();
    include "conexiondb.php";
    include "toast.php";

    if (isset($_POST['tipo'])) {
        $tipo = $_POST['tipo'];
    }
    else {
        $tipo = 0;
    }

    switch ($tipo) {
        case '1':
            ingresarMateria($conexion);
            break;
        case '2':
            eliminarMateria($conexion);
            break;
        case '3':
            modificarMateria($conexion);
            break; 
        case '4':
            loadNota($conexion);
            break;
        case '0':
            break;   
        default:
            header('location: principal.php');
            break;
    }

    function ingresarMateria($conexion) {
        session_start();
        $materia = $_POST['nombremateria'];
        $profesor = $_POST['profesorn'];
        $idSemestre = $_POST['id_semestre'];

        $usuario = $_SESSION["id_usuario"];

        $sql = "INSERT INTO materia(id_usuario, id_semestre, nombre_materia, nombre_profesor) VALUES
                ('$usuario', '$idSemestre', '$materia', '$profesor')";
                
        if(($result = $conexion->query($sql)) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conexion->error;
        }
        
        $toastmensaje = toast(TRUE, "Materia Creada Correctamente");
        $_SESSION["toastmesaje"] = $toastmensaje;
        header('location: principal.php');
    }
    

    function eliminarMateria($conexion) {
        session_start();
        $idMateria = $_POST['id_materia'];

        $sql = "DELETE FROM materia
        WHERE id_materia = '$idMateria';";
                
        if(($result = $conexion->query($sql)) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conexion->error;
        }
        
        $toastmensaje = toast(TRUE, "Materia Eliminada Correctamente");
        $_SESSION["toastmesaje"] = $toastmensaje;
        header('location: principal.php');
    }

    function loadNota($conexion) {
        $idMateria = $_POST['id_materiavis'];

        $sql = "SELECT nombre_materia, nombre_profesor FROM materia
        WHERE id_materia = '$idMateria';";
                
        if(($result = $conexion->query($sql)) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conexion->error;
        }

        $result = mysqli_query($conexion, $sql);
        $buscarmateria = mysqli_fetch_assoc($result);

        $materia = $buscarmateria["nombre_materia"];
        $profesor = $buscarmateria["nombre_profesor"];
        
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
                    <div class="card col-md-6 mx-auto">
                        <form action="materia.php" method="post">
                            <div class="card-header row">
                                <div class="col-6"><img src="img/Icon v2.png" alt="Logo" class="logoig"></div>
                                <div class="col-6 pt-2 text-end"><input type="submit" value="Modificar Materia" class="btn" id="btn1"></div>                   
                            </div>
                            <div class="card-body row">
                                <div class="col-md-6 mx-auto">
                                    <label for="nuevoNombreMateria" class="fw-bold form-label">Materia:</label>
                                    <input type="text" name="nombre_materia" value="'. $materia .'" class="form-control" maxlength="50" pattern="^(?=.*[a-zA-Z0-9])(?! )[\sa-zA-ZáéíóúÁÉÍÓÚñÑ0-9!@#$%^&*()_+\-=[\]{}?]{1,50}$"
                                    title="Letras mayúsculas, minúsculas, números, carácteres especiales y espacios permitidos" required>
                                    <div class="pt-4"></div>
                                    <label for="nuevoNombreProfesor" class="fw-bold form-label">Profesor:</label>
                                    <input type="text" name="nombre_profesor" value="'. $profesor .'" class="form-control" maxlength="100" pattern="^(?=.*[a-zA-Z0-9])(?! )[\sa-zA-ZáéíóúÁÉÍÓÚñÑ0-9!@#$%^&*()_+\-=[\]{}?]{1,50}$"
                                    title="Letras mayúsculas, minúsculas, números, carácteres especiales y espacios permitidos" required>
                                    <input type="hidden" value="3" name="tipo">
                                    <input type="hidden" value="'. $idMateria .'" name="id_materia">
                                    <div class="pt-3"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </body>
        </html>';
    }

    function modificarMateria($conexion) {
        session_start();
        $idMateria = $_POST['id_materia'];
        $newNombre = $_POST['nombre_materia'];
        $newProfesor = $_POST['nombre_profesor'];

        $sql = "UPDATE materia 
        SET nombre_materia = '$newNombre', nombre_profesor = '$newProfesor'
        WHERE id_materia = '$idMateria';";
                
        if(($result = $conexion->query($sql)) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conexion->error;
        }
        
        $toastmensaje = toast(TRUE, "Materia Modificada Correctamente");
        $_SESSION["toastmesaje"] = $toastmensaje;
        header('location: principal.php');
    }

    function loadSelMateria($conexion) {
        $usuario = $_SESSION["id_usuario"];

        $sql = "SELECT MAX(id_semestre) AS id_semestre FROM semestre
        WHERE id_usuario = '$usuario';";
                
        if(($result = $conexion->query($sql)) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conexion->error;
        }

        $result = mysqli_query($conexion, $sql);
        $sem = mysqli_fetch_assoc($result);

        $id_sem = $sem["id_semestre"];
        
        $sql = "SELECT id_materia, nombre_materia, nombre_profesor FROM materia
        WHERE id_semestre = '$id_sem';";
                
        if(($result = $conexion->query($sql)) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conexion->error;
        }

        $result = mysqli_query($conexion, $sql);

        echo '<option value="" disabled selected>Seleccione una Opción</option>';

        echo '<optgroup label="Materias:">';
        while ($materia = mysqli_fetch_assoc($result)) {
           echo '<option value="'. $materia["id_materia"] .'">'. $materia["nombre_materia"] .' - '. $materia["nombre_profesor"] .'</option>';
        }
        echo '</optgroup>';
        echo '<optgroup label="Estado:">
            <option value="1">Completadas</option>
            <option value="2">No Completadas</option>
            </optgroup>';
    }
?>