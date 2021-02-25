<?php
include("../Conexiones.php");

class Instructor {

    private $Conexiones;

    function __construct(){
        $this->Conexiones = new Conexiones();
    }

    function getInstructor($id_instructor)
    {
        $query = "SELECT * FROM instructor as i WHERE i.id_instructor = :id_instructor";
        $params = array(array("id_instructor", $id_instructor));
        return json_encode($this->Conexiones->Select($query, $params));
    }

    function getInstructores(){
        $query = "SELECT * FROM instructor ORDER by id_instructor DESC";
        $params = array();
        return json_encode(array("instructores" => $this->Conexiones->Select($query, $params)));
    }

    function getInstructorClase($id_instructor){
        $query = "
        SELECT c.* FROM 
        instructor_clase as ic 
        LEFT JOIN clase as c ON c.id_clase = ic.id_clase
        WHERE ic.id_instructor = :id_instructor";
        $params = array(array("id_instructor", $id_instructor));
        return json_encode(array("instructor_clase" => $this->Conexiones->Select($query, $params)));
    }
}
?>