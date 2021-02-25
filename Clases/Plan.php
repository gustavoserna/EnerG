<?php
include("../Conexiones.php");

class Plan {

    private $Conexiones;

    function __construct(){
        $this->Conexiones = new Conexiones();
    }

    function getPlanes(){
        $query = "SELECT * FROM plan WHERE status = 1 ORDER by total_clases ASC";
        $params = array();
        return json_encode(array("planes" => $this->Conexiones->Select($query, $params)));
    }

    function altaPlan($id_plan, $id_usuario){
        $query = "INSERT INTO plan_usuario(id_plan, id_usuario) VALUES(?,?)";
        $params = array($id_plan, $id_usuario);
        return $this->Conexiones->Insert($query, $params);
    }
}
?>