<?php
    session_start();
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
        case 'Iniciar Sesión':
            inicioSesion($conexion);
            break;
        case 'Crear Cuenta':
            registro($conexion);
            break;
        case 'Cerrar Sesión':
            cerrarSesion($conexion);
            break;
        case 'Eliminar Cuenta':
            eliminarCuenta($conexion);
            break;
        case '1':
            recupera();
            break;
        case '2':
            envio($conexion);
            break;
        case '0':
            break;
        default:
            echo "ERROR";
            break;
    }

    function inicioSesion($conexion) {
        $user = isset($_POST["usuario"]) ? $_POST["usuario"]: "";
        $pass = isset($_POST["pass"]) ? $_POST["pass"]: "";

        $correo = $conexion->prepare("SELECT id_usuario, contrasenia FROM usuario WHERE correo = BINARY ?;");
        $correo->bind_param("s", $user);
        $correo->execute();
        $result1 = $correo->get_result();
        
        $passen = mysqli_fetch_assoc($result1);
        
        if(password_verify($pass, $passen['contrasenia'])) {
            $_SESSION["usuario"] = $user;
            $_SESSION["id_usuario"] = $passen['id_usuario'];
            $toastmensaje = toast(TRUE, "Inicio de Sesión Correcto");
            $_SESSION["toastmesaje"] = $toastmensaje;
            header('location: principal.php');
            
        }
        else {
            $toastmensaje = toast(FALSE, "Correo o Contraseña Incorrectos");
            $_SESSION['toastmesaje'] = $toastmensaje;
            header('location: index.php');
            
        }
    }

    function registro($conexion) {
        $nombre = isset($_POST["Nombre"]) ? $_POST["Nombre"]: "";
        $apep = isset($_POST["Apellido_paterno"]) ? $_POST["Apellido_paterno"]: "";
        $apem = isset($_POST["Apellido_materno"]) ? $_POST["Apellido_materno"]: "";
        $user = isset($_POST["usuario"]) ? $_POST["usuario"]: "";
        $pass = isset($_POST["pass"]) ? $_POST["pass"]: "";
        $confpass = isset($_POST["confpass"]) ? $_POST["confpass"]: "";

        $sel = $conexion->prepare("SELECT correo FROM usuario WHERE correo = BINARY ?");
        $sel->bind_param("s", $user);
        $sel->execute();
        $result = $sel->get_result();

        if ($pass == $confpass) {
            if (($data = mysqli_fetch_assoc($result)) == FALSE ) {
                $passen = password_hash($pass, PASSWORD_DEFAULT); //Utilizando algoritmo bcrypt de encriptación recomendado por php (PASSWORD_DEFAULT)
                $sql = "INSERT INTO usuario(nombre, apellido_paterno, apellido_materno, correo, contrasenia) VALUES
                ('$nombre', '$apep', '$apem', '$user', '$passen')";
                
                if(($result = $conexion->query($sql)) === FALSE) {
                    echo "Error: " . $sql . "<br>" . $conexion->error;
                }
                $toastmensaje = toast(TRUE, "Cuenta Creada Correctamente");
                $_SESSION["toastmesaje"] = $toastmensaje;
                header('Location: index.php');
            }
            else {
                $toastmensaje = toast(FALSE, "Correo Ya Registrado");
                $_SESSION["toastmesaje"] = $toastmensaje;
                header('Location: index.php');
            }
        }
        else {
            $toastmensaje = toast(FALSE, "Contraseña No Coincidiente");
            $_SESSION["toastmesaje"] = $toastmensaje;
            header('Location: index.php');
        }
    }
    
    function eliminarCuenta($conexion) {
        // Obtener la contraseña actual del usuario
        $contraseña_actual = $_POST['pass2'];
        
        // Verificar la contraseña actual
        $usuario = $_SESSION['usuario'];

        $query = $conexion->prepare("SELECT id_usuario, contrasenia FROM usuario WHERE correo = ?;");
        $query->bind_param("s", $usuario);
        $query->execute();
        $resultado = $query->get_result();
        
        $registro = mysqli_fetch_assoc($resultado);

        $contraseña_almacenada = $registro['contrasenia'];

        if (password_verify($contraseña_actual, $contraseña_almacenada) != TRUE) {
            // Si la contraseña ingresada no coincide con la almacenada, mostrar un mensaje de error
            $toastmensaje = toast(TRUE, "La Contraseña Ingresada No Es Correcta");
            $_SESSION["toastmesaje"] = $toastmensaje;
            header('Location: principal.php');
            exit();
        }
        
        //Eliminamos datos de todas las tablas
        $user = $registro['id_usuario'];

        $query = "DELETE FROM nota WHERE id_usuario = '$user'";
        mysqli_query($conexion, $query);
        $query = "DELETE FROM materia WHERE id_usuario = '$user'";
        mysqli_query($conexion, $query);
        $query = "DELETE FROM semestre WHERE id_usuario = '$user'";
        mysqli_query($conexion, $query);

        // Eliminar la cuenta
        $query = "DELETE FROM usuario WHERE correo = '$usuario'";
        mysqli_query($conexion, $query);
        
        // Cerrar la sesión del usuario
        session_destroy();
        
        // Redirigir a la página de inicio de sesión
        header('Location: index.php');
        exit();
    }

    function cerrarSesion($conexion) {
        session_start(); // Iniciar la sesión
        session_destroy(); // Destruir todos los datos de la sesión	
        header("Location: index.php"); // Redirigir al usuario al inicio de sesión
        exit(); // Salir del script
    }

    function recupera() {
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
                        <form action="sesion.php" method="post">
                            <div class="card-header row">
                                <div class="col-6"><img src="img/Icon v2.png" alt="Logo" class="logoig"></div>
                                <div class="col-6 pt-2 text-end"><input type="submit" value="Recuperar Contraseña" class="btn" id="btn1"></div>                   
                            </div>
                            <div class="card-body row">
                                <div class="col-md-6 mx-auto">
                                <div class="container">
                                    <label for="correo" class="fw-bold col-8 form-label">Correo Registrado:</label>
                                    <input type="text" name="usuario" class="col-4 form-control" maxlength="50" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                                    title="example@example.dominio" required>
                                    <input type="hidden" value="2" name="tipo">
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

    function envio($conexion) {
        $user = $_POST["usuario"];

        $sel = $conexion->prepare("SELECT correo, contrasenia, id_usuario, nombre FROM usuario WHERE correo = BINARY ?;");
        $sel->bind_param("s", $user);
        $sel->execute();
        $result = $sel->get_result();
        
        if(mysqli_num_rows($result) == 0) {
            header('Location: index.php');
        }
        else {
            $data = mysqli_fetch_assoc($result);
            $texto = $data["id_usuario"];
            $n = substr($texto, 0, 2);
            $r = random_int(0, 25);
            $y = random_int(0, 25);
            $t = 'AB%CDE&MN_OPQRSLx/.TUVWX-Z';
            $a = $t[$r] . $t[$y];

            $new = bin2hex(random_bytes(1)) . $a . bin2hex(random_bytes(2)) . $n;
            
            $para = $data["correo"];
            $asunto = "Recuperación de Cuenta";
            $mensaje = ' <!DOCTYPE html>
            <html>
              <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
              </head>
              <body style="font-family: Arial, sans-serif;">
                <header style="background-color: #F5F5F5; padding: 10px;">
                  <h2>NOTIZEN</h2>
                  <h1>¡Has solicitado recuperar tu contraseña!</h1>
                </header>
                <main style="padding: 20px;">
                  <p>Hola '. $data["nombre"] .',</p>
                  <p>Como respuesta a tu solicitud, tu contraseña ha sido restablecida.</p>
                  <p>Tu nueva contraseña es: '. $new .' </p>
                  <p>Te recomendamos cambiarla tan pronto como ingreses a NOTIZEN.</p>
                </main>
                <footer style="background-color: #F5F5F5; padding: 10px;">
                  <p>Si tienes alguna pregunta o comentario, por favor contáctanos en:</p>
                  <p>Notizen<br>
                     Correo electrónico: notizen.network@outlook.com</p>
                </footer>
              </body>
            </html>
            ';

            correo($para, $asunto, $mensaje);

            $passen = password_hash($new, PASSWORD_DEFAULT); //Utilizando algoritmo bcrypt de encriptación recomendado por php (PASSWORD_DEFAULT)
            $sql = "UPDATE usuario
            SET contrasenia = '$passen'
            WHERE id_usuario = '$texto'";

            if(($result = $conexion->query($sql)) === FALSE) {
                echo "Error: " . $sql . "<br>" . $conexion->error;
            } 

            $toastmensaje = toast(TRUE, "Revisa la bandeja de entrada de tu correo electrónico");
            $_SESSION["toastmesaje"] = $toastmensaje;

            header('Location: index.php');     
        }
    }

    function correo($para, $asunto, $mensaje) {
        require_once "Mail.php";
        // Configurar detalles de la cuenta de Gmail
        $de = 'notizen.network@outlook.com';
        $clave = 'c5.Pfe0411';

        // Configurar el servidor SMTP de Gmail
        $smtpHost = 'smtp.office365.com';
        $smtpPuerto = 587;

        // Crear el encabezado del correo electrónico
        $cabeceras = array(
            'From' => $de,
            'To' => $para,
            'Subject' => $asunto,
            "MIME-Version" => "1.0",
            "Content-Type" => "text/html; charset=utf-8"
        );

        // Autenticar con la cuenta de Gmail
        $smtpUsuario = $de;
        $smtpClave = $clave;

        // Configurar el envío de correo electrónico a través de SMTP
        $smtp = Mail::factory('smtp', array(
            'host' => $smtpHost,
            'port' => $smtpPuerto,
            'auth' => true,
            'username' => $smtpUsuario,
            'password' => $smtpClave
        ));

        // Enviar el correo electrónico
        $mail = $smtp->send($para, $cabeceras, $mensaje);

        if (PEAR::isError($mail)) {
            echo 'Error al enviar el correo electrónico: ' . $mail->getMessage();
        } else {
            echo 'Correo electrónico enviado exitosamente.';
        }

    }
?>