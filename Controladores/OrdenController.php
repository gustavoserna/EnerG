<?php
include("../Clases/Orden.php");

$op = $_POST["op"];
$Orden = new Orden();

if($op == "AltaPlan") {
    echo trim($Orden->AltaPlan($_POST["id_plan"], $_POST["id_tarjeta"]));
}

if($op == "RegistrarTarjeta") {
	$token = $_POST["token"];
	echo trim($Orden->RegistrarTarjeta($token));
}
?>