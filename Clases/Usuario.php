<?php
include("Sesion.php");

class Usuario extends Sesion {

    private $Conexiones;
    private $id_usuario;

    function __construct(){
        parent::__construct();
        $this->Conexiones = new Conexiones();
        $this->id_usuario = $this->getUser()["id_usuario"];
    }

    function getPerfil(){
        $query = "SELECT * FROM usuario as u WHERE u.id_usuario = :id_usuario";
        $params = array(array("id_usuario", $this->id_usuario));
        return json_encode($this->Conexiones->Select($query, $params));
    }

    function updatePerfil($nombre, $apellido, $mail, $telefono){
        $query = "UPDATE usuario SET nombre = ?, apellido = ?, email = ?, telefono = ? WHERE id_usuario = ?";
		$parametros = array($nombre, $apellido, $mail, $telefono, $this->id_usuario);
		$this->Conexiones->Update($query, $parametros);
		return "actualizado";
    }

    function getClasesStatusUsuario($id_status){
        $query = "
        SELECT 
        uc.id_usuario_clase,
        c.id_clase, c.clase, c.descripcion, c.breve_descripcion,
        ic.id_instructor_clase,
        i.id_instructor, i.nombre, i.apellido, i.foto,
        hc.id_horario_clase, hc.fecha, hc.horario_inicio, hc.horario_fin
        FROM usuario_clase as uc
        LEFT JOIN clase as c ON c.id_clase = uc.id_clase
        LEFT JOIN instructor_clase as ic ON ic.id_instructor_clase = uc.id_instructor_clase
        LEFT JOIN instructor as i ON i.id_instructor = ic.id_instructor
        LEFT JOIN horario_clase as hc ON hc.id_horario_clase = uc.id_horario_clase
        LEFT JOIN status_clase as sc ON sc.id_usuario_clase = uc.id_usuario_clase AND sc.id_status_clase = (SELECT id_status_clase FROM status_clase WHERE id_usuario_clase = uc.id_usuario_clase ORDER by id_status_clase DESC LIMIT 1)
        WHERE uc.id_usuario = :id_usuario AND sc.id_status = :id_status
        ORDER by hc.horario_inicio ASC
        ";
        $params = array(array("id_status", $id_status), array("id_usuario", $this->id_usuario));
        return json_encode(array("clases" => $this->Conexiones->Select($query, $params)));
    }
    
    function getCreditosUsuario(){
        if($this->id_usuario != "") {
            $query = "SELECT IFNULL(clases_disponibles, 0) as clases_disponibles FROM plan_usuario WHERE id_usuario = :id_usuario  AND status = 1";
            $params = array(array("id_usuario", $this->id_usuario));
            $c = $this->Conexiones->Select($query, $params);
            if(sizeof($c) > 0) {
                return $c[0]["clases_disponibles"]." clases disponibles";
            } else {
                return "0 clases disponibles";
            }
        } else {
            return "";
        }
    }

    function getClase($id_usuario_clase){
    }

    function getMetodosPago() {
        $query = "SELECT id_tarjeta, CONCAT('**** ', tarjeta) as tarjeta FROM tarjeta WHERE id_usuario = :id_usuario ORDER by id_tarjeta DESC";
        $parametros = array(array("id_usuario", $this->id_usuario));
        $c = $this->Conexiones->Select($query, $parametros);

        return json_encode(array("tarjetas" => $c));
    }

    function cerrarSesion() {
        session_destroy();
    }
}
?>