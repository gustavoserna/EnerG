<?php 
header('Content-Type: application/json');
include("Admin.php");

$op = $_POST["op"];

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

if($op == "SetEstadoPedido")
{
	$Admin = new Admin();
	echo trim($Admin->SetEstadoPedido($_POST["id_pedido"], $_POST["id_estado"]));
}

/*
	Obtiene todos los pedidos excepto finalizados y cancelados.
	Se usa en el subdominio "Repartidor"
*/
if($op == "GetPedidosActivos")
{
	$Admin = new Admin();
	echo trim($Admin->GetPedidosActivosRepartidores());
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

if($op == "GetPedidosListosRecoleccion")
{
	$Admin = new Admin();
	echo trim($Admin->GetPedidosDashboard("2"));
}

if($op == "GetPedidosRecolectados")
{
	$Admin = new Admin();
	echo trim($Admin->GetPedidosDashboard("3"));
}

if($op == "GetPedidosEnCamino")
{
	$Admin = new Admin();
	echo trim($Admin->GetPedidosDashboard("4"));
}

if($op == "GetPedidosFinalizados")
{
	$Admin = new Admin();
	echo trim($Admin->GetPedidosDashboard("5"));
}

if($op == "GetPedidosCancelados")
{
	$Admin = new Admin();
	echo trim($Admin->GetPedidosDashboard("6"));
}

if($op == "GetReportesFiltrados")
{
	$del = $_POST["del"];
	$al = $_POST["al"];
	$Admin = new Admin();
	echo trim($Admin->GetReportesFiltrados($del, $al));
}

if($op == "GetProductos")
{
	$Admin = new Admin();
	echo trim($Admin->GetProductos($_POST["idcat"]));
}

if($op == "GetUnidades")
{
	$Admin = new Admin();
	echo trim($Admin->GetUnidades());
}

if($op == "GetCategorias")
{
	$Admin = new Admin();
	echo trim($Admin->GetCategorias());
}

if($op == "GetSubCategorias")
{
	$Admin = new Admin();
	echo trim($Admin->GetSubCategorias($_POST["id_categoria"]));
}

if($op == "CheckProducto")
{
	$Admin = new Admin();
	echo trim($Admin->CheckProducto($_POST["id_articulo"]));
}

if($op == "GetArticulo")
{
	$Admin = new Admin();
	echo trim($Admin->GetArticulo($_POST["id_articulo"]));
}

if($op == "UpdateArticulo")
{
	$Admin = new Admin();
	echo trim($Admin->UpdateArticulo(
		$_POST["id_articulo"], 
		$_POST["precio"], 
		$_POST["descripcion"],
		$_POST["articulo"],
		$_POST["categoria"],
		$_POST["subcategoria"],
		$_POST["unidad"],
		$_POST["id_unidad_articulo"]
	));
}

if($op == "UpdateImagenProducto")
{
	$Admin = new Admin();
	echo trim($Admin->UpdateImagenProducto(
		$_POST["id_articulo"], 
		$_FILES["file"]
	));
}

if($op == "SetOneSignal")
{
	$Admin = new Admin();
	$userId = $_POST["userId"];
	echo trim($Admin->SetOneSignal($userId));
}

if($op == "GetHeaders")
{
	$Admin = new Admin();
	echo trim($Admin->GetHeaders());
}

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

if($op == "GetUsuarios")
{
	$Admin = new Admin();
	echo trim($Admin->GetUsuarios());
}

if($op == "GetEstablecimientos")
{
	$Admin = new Admin();
	echo trim($Admin->GetEstablecimientos());
}

if($op == "ReasignarPedido")
{
	$id_establecimiento = $_POST["id_establecimiento"];
	$id_pedido = $_POST["id_pedido"];

	$Admin = new Admin();
	echo trim($Admin->ReasignarPedido($id_establecimiento, $id_pedido));
}
?>




