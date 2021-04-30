<?php
include("../Clases/Clase.php");

$op = $_POST["op"];
$Clase = new Clase();

if($op == "getClasesInstHor"){
    echo $Clase->getClasesInstHor();
}

if($op == "agendarClase"){
    echo $Clase->agendarClase($_POST["id_instructor_clase"], $_POST["id_horario_clase"], $_POST["id_clase"]);
}

if($op == "cancelarClase"){
    echo $Clase->cancelarClase($_POST["id_usuario_clase"]);
}

if($op == "getGaleria"){
    echo $Clase->getGaleria();
}
?>