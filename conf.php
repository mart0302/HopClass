<?php
    ob_start();
    include "conexiondb.php";
    include_once "toast.php";

    if (isset($_POST['tipo'])) {
        $tipo = $_POST['tipo'];
    }
    else {
        $tipo = 0;
    }

    switch ($tipo) {
        case '1':
            update($conexion);
            break;
        case '0':
            break;   
        default:
            header('location: principal.php');
            break;
    }

    function loadConfig($conexion) {
        $id_usuario = $_SESSION["id_usuario"];

        $sql = $conexion->prepare("SELECT id_usuario, nombre, apellido_paterno, apellido_materno, correo, contrasenia FROM usuario
        WHERE id_usuario = ?;");
        $sql->bind_param("i", $id_usuario);
        $sql->execute();
        $result = $sql->get_result();

        $sem = mysqli_fetch_assoc($result);

        echo '
        <form action="conf.php" method="post">
            <div class="col-12 mb-5 text-center">
                <label for="nombre" class="fw-bold form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" value="'. $sem["nombre"] .'">
            </div>
            <div class="col-12 mb-5 text-center">
                <label for="paterno" class="fw-bold form-label">Apellido Paterno</label>
                <input type="text" class="form-control" name="paterno" value="'. $sem["apellido_paterno"] .'">
            </div>
            <div class="col-12 mb-5 text-center">    
                <label for="materno" class="fw-bold form-label">Apellido Materno</label>
                <input type="text" class="form-control" name="materno" value="'. $sem["apellido_materno"] .'">
            </div>
            <div class="col-12 mb-5 text-center">  
                <label for="email" class="fw-bold form-label">Correo Electrónico</label>
                <input type="text" class="form-control" value="'. $sem["correo"] .'" name="email" disabled>
            </div>  
            <div class="col-12 mb-5 text-center">
                <label for="pass" class="fw-bold form-label">Contraseña Anterior</label>
                <input type="password" name="pass" id="passw" class="form-control" maxlength="10">
                <a onclick="togglePasswordReg1()"><span class="material-symbols-outlined btn" id="PassIcon1">visibility_off</span></a>
            </div>
            <div class="col-12 mb-5 text-center">
                <label for="confpass" class="fw-bold form-label">Nueva Contraseña</label>      
                <input type="password" name="confpass" id="confpassw" class="form-control" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()-_=+{};:,<.>]).{6,10}$" maxlength="10"
                title="- Al menos una letra minúscula. - Al menos una letra mayúscula. - Al menos un dígito. - Al menos un carácter especial. - Una longitud de 10 caracteres">
                <a onclick="togglePasswordReg2()"><span class="material-symbols-outlined btn" id="PassIcon2">visibility_off</span></a>
            </div>
            <div class="col-12 mb-5 text-end">
                <input type="hidden" value="1" name="tipo">
                <button type="submit" class="btn" id="btn1">Actualizar Datos</button>  
            </div>  
        </form>';
    }

    function update($conexion) {
        session_start();
        $name = $_POST["nombre"];
        $a1 = $_POST["paterno"];
        $a2 = $_POST["materno"];
        $id_usuario = $_SESSION["id_usuario"];
        $pass = $_POST["pass"];
        $newpass = $_POST["confpass"];
        
        $correo = $conexion->prepare("SELECT contrasenia FROM usuario WHERE id_usuario = ?;");
        $correo->bind_param("i", $id_usuario);
        $correo->execute();
        $result1 = $correo->get_result();

        $passen = mysqli_fetch_assoc($result1);

        if (password_verify($pass, $passen['contrasenia'])) {
            $encri = password_hash($newpass, PASSWORD_DEFAULT);

            $correo = "UPDATE usuario
            SET nombre = '$name', apellido_paterno = '$a1', apellido_materno = '$a2', contrasenia = '$encri'
            WHERE id_usuario = '$id_usuario'";

            if(($result1 = $conexion->query($correo)) === FALSE) {
                echo "Error: " . $correo . "<br>" . $conexion->error;
            }

            $toastmensaje = toast(TRUE, "Datos modificados<br>Contraseña actualizada");
            $_SESSION["toastmesaje"] = $toastmensaje;
            header('location: configuracion.php');
        }
        else {
            $correo = "UPDATE usuario
            SET nombre = '$name', apellido_paterno = '$a1', apellido_materno = '$a2'
            WHERE id_usuario = '$id_usuario'";

            if(($result1 = $conexion->query($correo)) === FALSE) {
                echo "Error: " . $correo . "<br>" . $conexion->error;
            }

            $toastmensaje = toast(TRUE, "Datos modificados<br>Contraseña no actualizada");
            $_SESSION["toastmesaje"] = $toastmensaje;
            header('location: configuracion.php');   
        }  
    }
?>