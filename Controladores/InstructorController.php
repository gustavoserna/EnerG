<?php
include("../Clases/Instructor.php");

$op = $_GET["op"];
$Instructor = new Instructor();

if($op == "getInstructores")
{
    echo $Instructor->getInstructores();
}

if($op == "getInstructorClase")
{
    echo $Instructor->getInstructorClase($_GET["id_instructor"]);
}

if($op == "getInstructor")
{
    echo $Instructor->getInstructor($_GET["id_instructor"]);
}
?>