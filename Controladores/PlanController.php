<?php
include("../Clases/Plan.php");

$op = $_GET["op"];
$Plan = new Plan();

if($op == "getPlanes"){
    echo $Plan->getPlanes();
}

if($op == "altaPlan"){
    echo $Plan->altaPlan($_GET["id_plan"], $_GET["id_usuario"]);
}
?>