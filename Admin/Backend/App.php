<?php 
header('Content-Type: application/json');
include("Admin.php");

$op = $_POST["op"];

//si
if($op == "GetReservacionesHorario") {
	$Admin = new Admin();
	echo trim($Admin->GetReservacionesHorario($_POST["id_horario_clase"]));  
}

if($op == "IniciarSesion")
{
	$Admin = new Admin();
	$usuario = $_POST["usuario"];
	$contraseña = $_POST["contraseña"];
	
	echo trim($Admin->IniciarSesion($usuario, $contraseña));
}

//si
if($op == "GetSuscripcion")
{
	$Admin = new Admin();
	echo trim($Admin->GetSuscripcion($_POST["id_suscripcion"]));
}

//si
if($op == "GetInstructores")
{
	$Admin = new Admin();
	echo trim($Admin->GetInstructores());
}

//si
if($op == "GetListaClases")
{
	$Admin = new Admin();
	echo trim($Admin->GetListaClases());
}

//si
if($op == "GetInstructor")
{
	$Admin = new Admin();
	echo trim($Admin->GetInstructor($_POST["id_instructor"]));
}

//si
if($op == "AltaInstructor")
{
	$nombre = $_POST["nombre"];
	$apellido = $_POST["apellido"];
	$usuario = $_POST["usuario"];
	$clave = $_POST["clave"];
	$telefono = $_POST["telefono"];
	$correo = $_POST["correo"];
	$descripcion = $_POST["descripcion"];
	$foto = $_FILES["file"];

	$Admin = new Admin();
	echo trim($Admin->AltaInstructor($nombre, $apellido, $usuario, $clave, $telefono, $correo, $descripcion, $foto));
}

//si
if($op == "ActualizarInstructor")
{
	$nombre = $_POST["nombre"];
	$usuario = $_POST["usuario"];
	$clave = $_POST["clave"];
	$telefono = $_POST["telefono"];
	$correo = $_POST["correo"];
	$descripcion = $_POST["desc"];

	$Admin = new Admin();
	echo trim($Admin->ActualizarInstructor($nombre, $usuario, $clave, $telefono, $correo, $descripcion));
}

//si
if($op == "GetHorariosClases")
{
	$Admin = new Admin();
	echo trim($Admin->GetHorariosClases());
}

//si
if($op == "GetHorariosClase")
{
	$Admin = new Admin();
	echo trim($Admin->GetHorariosClase($_POST["id_clase"]));
}

//si
if($op == "QuitarHorarioClase")
{
	$Admin = new Admin();
	echo trim($Admin->QuitarHorarioClase($_POST["id_horario_clase"]));
}

if($op == "GetSuscripcionesActivas")
{
	$Admin = new Admin();
	echo trim($Admin->GetSuscripciones("1"));
}

if($op == "GetSuscripcionesFinalizadas")
{
	$Admin = new Admin();
	echo trim($Admin->GetSuscripciones("0"));
}

if($op == "GetHeaders")
{
	$Admin = new Admin();
	echo trim($Admin->GetHeaders());
}

//si
if($op == "AltaClase")
{
	$clase = $_POST["clase"];
	$descripcion_breve = $_POST["desc-breve"];
	$descripcion = $_POST["descripcion"];
	$minimo = $_POST["minimo"];
	$maximo = $_POST["maximo"];

	$Admin = new Admin();
	echo trim($Admin->AltaClase($clase, $descripcion_breve, $descripcion, $minimo, $maximo));
}

//si
if($op == "AgregarHorario")
{
	$id_clase = $_POST["clase"];
	$id_instructor = $_POST["instructor"];
	$fecha = $_POST["fecha"];

	$Admin = new Admin();
	echo trim($Admin->AgregarHorario($id_clase, $id_instructor, $fecha));
}

if($op == "GetUsuarios")
{
	$Admin = new Admin();
	echo trim($Admin->GetUsuarios());
}
?>




