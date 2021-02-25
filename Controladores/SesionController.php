<?php
include("../Clases/Sesion.php");

$op = $_POST["op"];
$Sesion = new Sesion();

if($op == "login"){
    echo $Sesion->login($_POST["usuario"], $_POST["clave"]);
}

if($op == "registro"){
    echo $Sesion->registro(
        $_POST["nombre"],
        $_POST["apellido"],
        $_POST["mail"],
        $_POST["telefono"],
        $_POST["usuario"], 
        $_POST["clave"]
    );
}
?>