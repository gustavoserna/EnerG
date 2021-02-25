<?php
include("../Clases/Usuario.php");

$op = $_GET["op"];
$Usuario = new Usuario();

if($op == "getPerfil")
{
    echo $Usuario->getPerfil($_GET["id_usuario"]);
}

if($op == "updatePerfil")
{
    $nombre = $_GET["nombre"];
    $apellido = $_GET["apellido"];
    $mail = $_GET["mail"];
    $telefono = $_GET["telefono"];
    echo $Usuario->updatePerfil($nombre, $apellido, $mail, $telefono);
}

if($op == "getClasesStatusUsuario")
{
    echo $Usuario->getClasesStatusUsuario($_GET["id_status"]);
}

if($op == "getCreditosUsuario")
{
    echo $Usuario->getCreditosUsuario($_GET["id_usuario"]);
}

if($op == "getClase"){
    echo $Usuario-getClase($_GET["id_usuario_clase"]);
}
?>