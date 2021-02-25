<?php
include("../Conexiones.php");

class Sesion {

    private $Conexiones;

    function __construct(){
        $this->Conexiones = new Conexiones();
    }

    function login($usuario, $clave){
        $query = "SELECT * FROM usuario WHERE usuario = :usuario AND clave = :clave";
        $params = array(array("usuario", $usuario), array("clave", $clave));
        $c = $this->Conexiones->Select($query, $params);
        $res = sizeof($c);

        if($res == 1){
            $_SESSION['loggedin'] = true;
		    $_SESSION['perfil'] = json_encode($c[0]);
		    $_SESSION['start'] = time();
		    $_SESSION['fin'] = $_SESSION['start'] + (1800 * 600);
        }

        return $res;
    }

    function registro($nombre, $apellido, $mail, $telefono, $usuario, $clave){
        $query = "SELECT usuario, email, telefono FROM usuario WHERE usuario = :usuario OR email = :email OR telefono = :telefono";
        $params = array(array("usuario", $usuario), array("email", $mail), array("telefono", $telefono));
        $c = $this->Conexiones->Select($query, $params);
        if(sizeof($c) >= 1){
            return "existe";
        } else {
            $query = "INSERT INTO usuario(usuario, clave, nombre, apellido, email, telefono) VALUES(?,?,?,?,?,?)";
            $params = array($usuario, $clave, $nombre, $apellido, $mail, $telefono);
            $this->Conexiones->Insert($query, $params);
            return "listo";
        }
    }
}
?>