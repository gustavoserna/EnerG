<?php
include("Sesion.php");

class Clase extends Sesion {

    private $Conexiones;
    private $id_usuario;

    function __construct() {
        parent::__construct();
		$this->Conexiones = new Conexiones();
		$this->id_usuario = $this->getUser()["id_usuario"];
    }

    /*function getClasesInstHor(){
        $query = "
        SELECT 
        ic.id_instructor_clase,
        c.id_clase, c.clase, c.descripcion, c.minimo, c.maximo, c.breve_descripcion,
        i.id_instructor, i.nombre, i.apellido, i.foto,
        hc.id_horario_clase, hc.horario_inicio, hc.horario_fin, hc.dia    
        FROM horario_clase as hc
        LEFT JOIN clase as c ON c.id_clase = hc.id_clase
        LEFT JOIN instructor as i ON i.id_instructor = hc.id_instructor AND i.status = 1
        LEFT JOIN instructor_clase as ic ON ic.id_instructor = i.id_instructor
        WHERE c.status = 1
        ";
        $params = array();
        return json_encode(array("clases" => $this->Conexiones->Select($query, $params)));
    }*/

    function getClasesInstHor() {
        $query = "
        SELECT 
        d.disciplina,
        ic.id_instructor_clase,
        c.id_clase, c.clase, c.descripcion, c.minimo, c.maximo, c.breve_descripcion,
        i.id_instructor, i.nombre, i.apellido, i.foto,
        hc.id_horario_clase, hc.horario_inicio, hc.horario_fin, hc.dia    
        FROM horario_clase as hc
        LEFT JOIN clase as c ON c.id_clase = hc.id_clase
        LEFT JOIN instructor as i ON i.id_instructor = hc.id_instructor AND i.status = 1
        LEFT JOIN instructor_clase as ic ON ic.id_instructor = i.id_instructor
        LEFT JOIN disciplina as d ON d.id_disciplina = c.id_disciplina
        WHERE c.status = 1 AND hc.status = 1
        GROUP by hc.id_horario_clase
        ";
        $params = array();
        return json_encode(array("clases" => $this->Conexiones->Select($query, $params)));
    }

    function agendarClase($id_instructor_clase, $id_horario_clase, $id_clase) {
        //verificar si hay cupo
        $query = "
        SELECT COUNT(*) as reservaciones,
        (SELECT maximo FROM horario_clase WHERE id_horario_clase = :id_horario_clase) as maximo
        FROM usuario_clase WHERE id_horario_clase = :id_horario_clase
        ";
        $params = array(array("id_horario_clase1", $id_horario_clase), array("id_horario_clase", $id_horario_clase));
        $c = $this->Conexiones->Select($query, $params);

        if($c[0]["reservaciones"] >= $c[0]["maximo"]) {
            return "sin_cupo";
        } else {
            $query = "SELECT id_plan_usuario, clases_disponibles FROM plan_usuario WHERE id_usuario = :id_usuario AND status = 1";
            $params = array(array("id_usuario", $this->id_usuario));
            $c = $this->Conexiones->Select($query, $params);
            $clases_disponibles = 0;
            $id_plan_usuario = 0;

            if(sizeof($c) > 0) {
                $clases_disponibles = $c[0]["clases_disponibles"];
                $id_plan_usuario = $c[0]["id_plan_usuario"];
            }

            if($clases_disponibles > 0) {
                $this->Conexiones->AgendarClaseProc(array($this->id_usuario, $id_instructor_clase, $id_horario_clase, $clases_disponibles, $id_clase, $id_plan_usuario));
                return "Clase agendada exitosamente";
            } else {
                return "Ya no tienes clases disponibles.";
            }
        }
    }

    function cancelarClase($id_usuario_clase){
        return $this->Conexiones->CancelarClaseProc(array($id_usuario_clase, $this->id_usuario));
    }

    function getGaleria(){
        $query = "SELECT * FROM galeria WHERE status = 1";
        $params = array();
        return json_encode(array("galeria" => $this->Conexiones->Select($query, $params)));
    }
}
?>