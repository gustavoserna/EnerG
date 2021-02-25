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

if($op == "GetPedido")
{
	$Admin = new Admin();
	echo trim($Admin->GetPedido($_POST["id_pedido"]));
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

if($op == "GetPedidosEnCurso")
{
	$Admin = new Admin();
	echo trim($Admin->GetPedidosDashboard("1"));
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

if($op == "AltaProducto")
{
	$producto = $_POST["producto"];
	$categoria = $_POST["categoria"];
	$subcategoria = $_POST["subcategoria"];
	$precio = $_POST["precio"];
	$unidad = $_POST["unidad"];
	$imagen = $_FILES["file"];
	//error_log($producto.",".$categoria.",".$subcategoria.",".$precio.",".$unidad.",".$imagen);

	$Admin = new Admin();
	echo trim($Admin->AltaProducto($producto, $categoria, $subcategoria, $precio, $unidad, $imagen));
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




