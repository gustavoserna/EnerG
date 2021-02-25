<?php
include("../Clases/Clase.php");

$op = $_GET["op"];
$Clase = new Clase();

if($op == "getClasesInstHor"){
    echo $Clase->getClasesInstHor();
}

if($op == "agendarClase"){
    echo $Clase->agendarClase($_GET["clase"], $_GET["id_usuario"]);
}

if($op == "cancelarClase"){
    echo $Clase->cancelarClase($_GET["id_usuario_clase"]);
}

if($op == "getGaleria"){
    echo $Clase->getGaleria();
}
?>