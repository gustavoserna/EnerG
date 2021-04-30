<?php
include("../Clases/Plan.php");

$op = $_GET["op"];
$Plan = new Plan();

if($op == "getPlanes"){
    echo $Plan->getPlanes();
}
?>