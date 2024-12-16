<?php
session_start();
ob_start();
include "../conexiondb.php";

$amigo = isset($_POST["search"]) ? $_POST["search"] : "";
if(isset($_SESSION["id_usuario"]) && !empty($_SESSION["id_usuario"])){
  $usuario = $_SESSION["id_usuario"];
}


//Select para poder buscar usuarios
$busqueda = "SELECT nombre, id_usuario FROM usuario WHERE correo=?";
$param = mysqli_prepare($conexion, $busqueda);
mysqli_stmt_bind_param($param, "s", $amigo);
mysqli_stmt_execute($param);
$resultado = mysqli_stmt_get_result($param);

//No permitir buscar usuarios que nos enviaron solicitud
$restringirsol = "SELECT id, estado FROM amigo WHERE id_usuario = (SELECT id_usuario FROM usuario WHERE correo=?) AND id_amigo ='{$_SESSION['id_usuario']}'";
$parame = mysqli_prepare($conexion, $restringirsol);
mysqli_stmt_bind_param($parame, "s", $amigo);
mysqli_stmt_execute($parame);
$res = mysqli_stmt_get_result($parame);
$resul = mysqli_fetch_assoc($res);

//No permitir buscar usuarios a los usuarios que ya enviamos solicitud
$restringirpen = "SELECT id, estado FROM amigo WHERE id_usuario ={$_SESSION['id_usuario']} AND id_amigo = (SELECT id_usuario FROM usuario WHERE correo=?)";
$param = mysqli_prepare($conexion, $restringirpen);
mysqli_stmt_bind_param($param, "s", $amigo);
mysqli_stmt_execute($param);
$respen = mysqli_stmt_get_result($param);
$resulpen = mysqli_fetch_assoc($respen);

//Condiciones para no mostrar amigos que nos enviaron solicitud
if (isset($resul["id"]) && !empty($resul["id"]) && $resul["estado"] == 0) {
  echo "No has aceptado su solicitud";
} 
//Condición para no mostrar amigos que estan pendientes
elseif (isset($resulpen["id"]) && !empty($resulpen["id"]) && $resulpen["estado"] == 0){
  echo "La solicitud esta pendiente";
} 
//Condición para no mostrar a tus amigos
elseif (isset($resul["id"]) && !empty($resul["id"]) && $resul["estado"] == 1 || isset($resulpen["id"]) && !empty($resulpen["id"]) && $resulpen["estado"] == 1){
  echo "Ya son amigos";
}
//Condición para enviar solicitud cuando no se cumplan los anteriores
else{
  if (mysqli_num_rows($resultado) > 0) {
    while ($resultadobusqueda = mysqli_fetch_assoc($resultado)) {
      if(isset($resultadobusqueda["id_usuario"]) && $resultadobusqueda["id_usuario"] == $usuario) {
        echo "El correo corresponde a esta cuenta";
      }
      else{
        $persona = '<div class="column">
          <div class="card">
            <i class="bx bx-user" style="width:100%; font-size:8vw"></i>
            <div class="container">
              <h2>' . $resultadobusqueda["nombre"] . '</h2>
              <form action="amigos/amigosCRUD.php" method="post" id="agregar" >
              <input type="hidden" value="1" name="tipo">
                <p><button class="button" id="bagregar" name="bagregar" value="'.$resultadobusqueda["id_usuario"].'">Agregar</button></p>
              </form>
            </div>
          </div>
        </div>';
        echo $persona;
      }
    }
  } else {
    echo "No se encontraron resultados";
  }
}

mysqli_stmt_close($param);
mysqli_close($conexion);
?>