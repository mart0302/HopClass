<?php
    $servername= "localhost";
    $username = "root";
    $password = "";
    $nombreDB = "notizen";

    //Crear la conexión con la DB
    $conexion = new mysqli($servername, $username, $password, $nombreDB);

    //Confirmar que fue exitosa la conexion
    if ($conexion->connect_error) {
	    die("Conexión fallida: " . $conexion->connect_error);}
?>