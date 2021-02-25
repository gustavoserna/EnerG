<?php
include("../Conexiones.php");

class Clase {

    private $Conexiones;

    function __construct(){
        $this->Conexiones = new Conexiones();
    }

    function getClasesInstHor(){
        $query = "
        SELECT 
        c.id_clase, c.clase, c.descripcion, c.minimo, c.maximo, c.breve_descripcion,
        i.id_instructor, i.nombre, i.apellido, i.foto,
        hc.id_horario_clase, hc.horario_inicio, hc.horario_fin, hc.dia        
        FROM horario_clase as hc
        LEFT JOIN clase as c ON c.id_clase = hc.id_clase
        LEFT JOIN instructor as i ON i.id_instructor = hc.id_instructor AND i.status = 1
        WHERE c.status = 1
        ";
        $params = array();
        return json_encode(array("clases" => $this->Conexiones->Select($query, $params)));
    }

    function agendarClase($clase, $id_usuario)
    {
        $json = json_decode($clase, true);
        $id_instructor_clase = $json["id_instructor_clase"];
        $id_horario_clase = $json["id_horario_clase"];

        return $this->Conexiones->AgendarClaseProc(array($id_usuario, $id_instructor_clase, $id_horario_clase));
    }

    function cancelarClase($id_usuario_clase){
        return $this->Conexiones->CancelarClaseProc(array($id_usuario_clase));
    }

    function getGaleria(){
        $query = "SELECT * FROM galeria WHERE status = 1";
        $params = array();
        return json_encode(array("galeria" => $this->Conexiones->Select($query, $params)));
    }
}
?>