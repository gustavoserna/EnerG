<?php
include("../Clases/Usuario.php");

$op = $_POST["op"];
$Usuario = new Usuario();

if($op == "cerrarSesion") {
    echo $Usuario->cerrarSesion();
}

if($op == "getPerfil")
{
    echo $Usuario->getPerfil();
}

if($op == "updatePerfil")
{
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $mail = $_POST["mail"];
    $telefono = $_POST["telefono"];
    echo $Usuario->updatePerfil($nombre, $apellido, $mail, $telefono);
}

if($op == "getClasesStatusUsuario")
{
    echo $Usuario->getClasesStatusUsuario($_POST["id_status"]);
}

if($op == "getCreditosUsuario")
{
    echo trim($Usuario->getCreditosUsuario());
}

if($op == "getClase"){
    echo $Usuario->getClase($_POST["id_usuario_clase"]);
}

if($op == "getMetodosPago"){
    echo trim($Usuario->GetMetodosPago());
}
?>