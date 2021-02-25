<?php
include("../Conexiones.php");

class Usuario {

    private $Conexiones;

    function __construct(){
        $this->Conexiones = new Conexiones();
    }

    function getPerfil($id_usuario){
        $query = "SELECT * FROM usuario as u WHERE u.id_usuario = :id_usuario";
        $params = array(array("id_usuario", $id_usuario));
        return json_encode($this->Conexiones->Select($query, $params));
    }

    function updatePerfil($nombre, $apellido, $mail, $telefono){
        $id_usuario = "1";

        $query = "UPDATE usuario SET nombre = ?, apellido = ?, email = ?, telefono = ? WHERE id_usuario = ?";
		$parametros = array($nombre, $apellido, $mail, $telefono, $id_usuario);
		$this->Conexiones->Update($query, $parametros);
		return "actualizado";
    }

    function getClasesStatusUsuario($id_status){
        $id_usuario = "1";

        $query = "
        SELECT 
        c.id_clase, c.clase, c.descripcion, c.breve_descripcion,
        i.id_instructor, i.nombre, i.apellido, i.foto,
        hc.id_horario_clase, hc.fecha, hc.horario_inicio, hc.horario_fin,
        ic.id_instructor_clase
        FROM clase as c
        LEFT JOIN instructor_clase as ic ON ic.id_clase = c.id_clase
        LEFT JOIN instructor as i ON i.id_instructor = ic.id_instructor
        LEFT JOIN horario_clase as hc ON hc.id_clase = c.id_clase
        LEFT JOIN usuario_clase as uc ON uc.id_instructor_clase = ic.id_instructor_clase AND uc.id_horario_clase = hc.id_horario_clase
        LEFT JOIN status_clase as sc ON sc.id_usuario_clase = uc.id_usuario_clase AND sc.id_status_clase = (SELECT id_status_clase FROM status_clase WHERE id_usuario_clase = uc.id_usuario_clase ORDER by id_status_clase DESC LIMIT 1)
        WHERE sc.id_status = :id_status
        AND uc.id_usuario = :id_usuario
        ORDER by hc.horario_inicio ASC
        ";
        $params = array(array("id_status", $id_status), array("id_usuario", $id_usuario));
        return json_encode(array("clases" => $this->Conexiones->Select($query, $params)));
    }
    
    function getCreditosUsuario($id_usuario){
        $query = "SELECT IFNULL(clases_disponibles, 0) as clases_disponibles FROM plan_usuario WHERE id_usuario = :id_usuario  AND status = 1";
        $params = array(array("id_usuario", $id_usuario));
        $c = $this->Conexiones->Select($query, $params);
        return $c[0]["clases_disponibles"];
    }

    function getClase($id_usuario_clase){
    }
}
?>