<?php
session_start();
include("../../Conexiones.php");

class Admin
{
	private $Conexiones;
	private $id_establecimiento;

	function __construct()
	{
		$this->Conexiones = new Conexiones();
		$this->id_establecimiento = 0;
	}

	function IniciarSesion($usuario, $contraseña)
	{
		$query = "
		SELECT 
		usuario,
		nombre
		FROM administrador WHERE usuario = :usuario AND clave = :clave AND status = 1";
		$parametros = array(array("usuario", $usuario), array("clave", $contraseña));
		$c = $this->Conexiones->Select($query, $parametros);

		if(sizeof($c) == 0)
		{
			return "no";
		}
		else
		{
			$_SESSION['loggedin'] = true;
			$_SESSION['perfil'] = json_encode($c[0]);
			$_SESSION['usuario'] = $c[0]["usuario"];
		    $_SESSION['start'] = time();
		    $_SESSION['fin'] = $_SESSION['start'] + (180 * 60);
			return "si";
		}
	}

	function GetHeaders()
	{
		$query = "
	    SELECT 
	    IFNULL(FORMAT(SUM(v.total), 2), 0) as venta_total,
	    COUNT(DISTINCT v.id_venta) as altas_totales,
	    IFNULL(FORMAT((SUM(v.total) / COUNT(DISTINCT v.id_venta)), 2), 0) as promedio_venta
	    FROM venta as v
	    WHERE DATE(v.fecha_alta) = CURDATE()
	    ";
	    $parametros = array();
	    $c = $this->Conexiones->Select($query, $parametros);

	    return json_encode($c[0]);
	}

	function GetEstablecimientos()
	{
		$query =  "SELECT * FROM establecimiento WHERE status = 1 ORDER BY establecimiento ASC";
		$parametros = array();
		return json_encode(array("establecimientos" => $this->Conexiones->Select($query, $parametros)));
	}

	//------------------------------------------------ GRAFICAS --------------------------------------------------------------------
	function GetGraficaVDiarias()
	{
		$query = 
		"
		SELECT
		c.db_date,
		DAYOFWEEK(c.db_date) as dia,
		CAST(IFNULL(SUM(v.total), 0) as double) as venta_total
		FROM calendario as c
		LEFT JOIN venta as v ON (DATE(c.db_date) = DATE(v.fecha_alta))
		WHERE DATE(c.db_date) BETWEEN DATE(CURDATE() - 6) 
		AND DATE(CURDATE())
		GROUP BY DATE(c.db_date)
		ORDER BY c.db_date ASC
		";
		$parametros = array();
		$c = array("dias" => $this->Conexiones->Select($query, $parametros));

		$max = 0;
		for($i = 0; $i < sizeof($c["dias"]); $i++)
		{
			$max = $c["dias"][$i]["venta_total"] > $max ? $c["dias"][$i]["venta_total"] : $max;
		}
		$max += 500;
		//inc today sales
		$venta_hoy = $c["dias"][sizeof($c["dias"]) - 1]["venta_total"];
		$venta_ayer = $c["dias"][sizeof($c["dias"]) - 2]["venta_total"] == 0 ? 1 : $c["dias"][sizeof($c["dias"]) - 2]["venta_total"];
		$porc = ($venta_hoy - $venta_ayer) / ($venta_ayer) * (100);
		$inc = number_format(floor($porc));

		$c["otros"] = array("valor_maximo" => $max, "porc_incremento" => $inc);

		return $c;
	}

	function GetGraficaPDiarios()
	{
		$query = 
		"
		SELECT
		c.db_date,
		DAYOFWEEK(c.db_date) as dia,
		CAST(IFNULL(COUNT(DISTINCT v.id_venta), 0) as int) as altas_totales
		FROM calendario as c
		LEFT JOIN venta as v ON (DATE(c.db_date) = DATE(v.fecha_alta))
		WHERE DATE(c.db_date) BETWEEN DATE(CURDATE() - 6) AND DATE(CURDATE())
		GROUP BY DATE(c.db_date)
		ORDER BY c.db_date ASC
		";
		$parametros = array();
		$c = array("dias" => $this->Conexiones->Select($query, $parametros));

		$max = 0;
		for($i = 0; $i < sizeof($c["dias"]); $i++)
		{
			$max = $c["dias"][$i]["altas_totales"] > $max ? $c["dias"][$i]["altas_totales"] : $max;
		}
		$max += 10;

		//inc today orders
		$pedidos_hoy = $c["dias"][sizeof($c["dias"]) - 1]["altas_totales"];
		$pedidos_ayer = $c["dias"][sizeof($c["dias"]) - 2]["altas_totales"] == 0 ? 1 : $c["dias"][sizeof($c["dias"]) - 2]["altas_totales"];
		$porc = ($pedidos_hoy - $pedidos_ayer) / ($pedidos_ayer) * (100);
		$inc = number_format(floor($porc));

		$c["otros"] = array("valor_maximo" => $max, "porc_incremento" => $inc);

		return $c;
	}

	function GetDiaAscii($i)
	{
		switch ($i) {
			case 1:
				return 68;
				break;
			case 2:
				return 76;
				break;
			case 3:
				return 77;
				break;
			case 4:
				return 77;
				break;
			case 5:
				return 74;
				break;
			case 6:
				return 86;
				break;
			case 7:
				return 83;
				break;
		}
	}
	//------------------------------------------------ GRAFICAS ----------------------------------------------------------------------

	//------------------------------------------------ REPORTES ----------------------------------------------------------------------
	function GetSuscripciones($status)
	{
		$query = 
		"
		SELECT
		p.plan, 
		CONCAT(u.nombre, ' ' ,u.apellido) as cliente,
		pu.id_plan_usuario as id_suscripcion,
		pu.clases_disponibles,
		pu.fecha_alta
		FROM plan_usuario as pu
		LEFT JOIN plan as p ON p.id_plan = pu.id_plan
		LEFT JOIN usuario as u ON u.id_usuario = pu.id_usuario
		WHERE pu.status = :status
		";
		$params = array(array("status", $status));
		$c = $this->Conexiones->Select($query, $params);

		for($i = 0; $i < sizeof($c); $i++)
		{
			$id_suscripcion = $c[$i]["id_suscripcion"];
			$c[$i]["otros"] = 
			'<button type="button" rel="tooltip" title="Información de la suscripción" class="btn btn-success btn-link btn-sm" onclick="showVentanaSuscripcion(\''.$id_suscripcion.'\')"><i class="material-icons">launch</i></button>';
		}

		return json_encode(array("suscripciones" => $c));
	}

	function GetReportesFiltrados($del, $al)
	{
		$del = date("Y-m-d", strtotime($del));
		$al = date("Y-m-d", strtotime($al));

		$op = "5";
		$query = 
		"
		SELECT 
		v.id_venta,
		v.costo_envio,
		v.costo_servicio,
		TRUNCATE(SUM(vd.precio_proveedor * vd.cantidad), 2) as pagar_caja,
		TRUNCATE(SUM(vd.precio_articulo * vd.cantidad), 2) as subtotal,
		v.descuento,
		v.total,
		IF(v.id_metodo_pago > 0, 'Tarjeta', 'Efectivo') as metodo_pago,
		v.fecha_venta,
		u.nombre,
		(
			SELECT le.estado FROM estado_pedido as ep 
			LEFT JOIN lista_estados as le ON le.id_estado = ep.id_estado
			WHERE ep.id_venta = v.id_venta
			ORDER by ep.id_estado_pedido DESC LIMIT 1
		) as estado
		FROM venta as v
		LEFT JOIN venta_detalle as vd ON vd.id_venta = v.id_venta
		LEFT JOIN usuario as u ON u.id_usuario = v.id_usuario
		INNER JOIN configuracion as conf
		WHERE
		(
			SELECT le.id_estado FROM estado_pedido as ep 
			LEFT JOIN lista_estados as le ON le.id_estado = ep.id_estado
			WHERE ep.id_venta = v.id_venta
			ORDER by ep.id_estado_pedido DESC LIMIT 1
		) = :op
		AND DATE(v.fecha_venta) BETWEEN DATE(:del) AND DATE(:al)
		GROUP BY v.id_venta
		ORDER by v.id_venta DESC
		";
		$parametros = array(
			array("op", $op),
			array("del", $del),
			array("al", $al)
		);
		$c = $this->Conexiones->Select($query, $parametros);

		error_log("respuesta: ".json_encode($c));

		for($i = 0; $i < sizeof($c); $i++)
		{
			$id_pedido = $c[$i]["id_venta"];
			$c[$i]["otros"] = 
			'<button type="button" rel="tooltip" title="Información del pedido" class="btn btn-success btn-link btn-sm" onclick="showVentanaPedido(\''.$id_pedido.'\')"><i class="material-icons">launch</i></button>';
		}
		//$c["venta_total"] = number_format($venta_total, 2);
		//error_log(json_encode($c));
		return '{ "pedidos": '.json_encode($c)."}";
	}
	//------------------------------------------------ REPORTES ----------------------------------------------------------------------

	//------------------------------------------------ PEDIDO ----------------------------------------------------------------------
	function GetPedidosActivosRepartidores()
	{
		$query = "
		SELECT 
		v.id_venta,
		v.id_metodo_pago,
		(v.subtotal / config.porc_ganancia) as total_pagar,
		(v.total) as total_cobrar,
		v.fecha_venta,
		d_ent.direccion as direccion_entrega,
		d_ent.latitud,
		d_ent.longitud,
		(
			SELECT le.estado FROM estado_pedido as e 
			LEFT JOIN lista_estados as le ON le.id_estado = e.id_estado
			WHERE e.id_venta = v.id_venta ORDER by e.id_estado_pedido DESC LIMIT 1	
		) as pedido_estado,
		(
			SELECT veh.vehiculo FROM vehiculo as veh
			LEFT JOIN vehiculo_repartidor as vr ON vr.id_vehiculo = veh.id_vehiculo
			WHERE vr.id_vehiculo_repartidor = v.id_vehiculo_repartidor
		) as vehiculo,
		CONCAT(r.nombre, ' ', r.apellidos) as nombre_repartidor,
		r.id_repartidor,
		(
			SELECT u.nombre FROM usuario as u WHERE u.id_usuario = v.id_usuario
		) as cliente,
		(
			SELECT u.telefono FROM usuario as u WHERE u.id_usuario = v.id_usuario
		) as telefono,
		(
			SELECT 
			CONCAT('- ', GROUP_CONCAT(DISTINCT e.establecimiento SEPARATOR '\r\n- ')) 
			FROM venta_detalle as vd 
			LEFT JOIN establecimiento as e ON e.id_establecimiento = vd.id_establecimiento
			WHERE vd.id_venta = v.id_venta 
			GROUP by vd.id_venta
		) as proveedores,
		config.costo_envio
		FROM venta as v
		LEFT JOIN direccion_usuario as d_ent ON d_ent.id_direccion = v.id_direccion_entrega
		LEFT JOIN repartidor as r on r.id_repartidor = v.id_repartidor
		INNER JOIN configuracion as config
		WHERE 
		(
			SELECT le.id_estado FROM estado_pedido as ep 
			LEFT JOIN lista_estados as le ON le.id_estado = ep.id_estado
			WHERE ep.id_venta = v.id_venta
			ORDER by ep.id_estado_pedido DESC LIMIT 1
		) < 5
		ORDER by v.id_venta DESC
		";
		$parametros = array();
		$consulta = $this->Conexiones->Select($query, $parametros);

		//agregar articulos
		for($i = 0; $i < sizeof($consulta); $i++)
		{
			$Con = new Conexiones();
			$query = "
			SELECT 
			a.articulo,
			TRUNCATE((vd.precio_articulo), 2) as precio,
			vd.cantidad,
			u.unidad,
			(SELECT imagen FROM imagen_articulo WHERE id_articulo = vd.id_articulo ORDER by id_imagen DESC LIMIT 1) as imagen_principal
			FROM venta_detalle as vd
			LEFT JOIN articulo as a ON a.id_articulo = vd.id_articulo
			LEFT JOIN unidad_articulo as ua ON ua.id_unidad_articulo = vd.id_unidad_articulo
			LEFT JOIN unidad as u ON u.id_unidad = ua.id_unidad_
			WHERE vd.id_venta = :id_pedido
			";
			$parametros = array(array("id_pedido", $consulta[$i]["id_venta"]));
			$articulos = $this->Conexiones->Select($query, $parametros);
			$consulta[$i]["articulos"] = $articulos;
		}

		return json_encode(array("pedidos" => $consulta));
	}

	function GetSuscripcion($id_suscripcion)
	{
		$query = 
		"
		SELECT
		p.plan, 
		CONCAT(u.nombre, ' ' ,u.apellido) as cliente,
		u.telefono,
		pu.id_plan_usuario as id_suscripcion,
		pu.clases_disponibles,
		pu.fecha_alta,
		v.subtotal,
		v.total
		FROM plan_usuario as pu
		LEFT JOIN venta as v ON v.id_plan_usuario = pu.id_plan_usuario
		LEFT JOIN plan as p ON p.id_plan = pu.id_plan
		LEFT JOIN usuario as u ON u.id_usuario = pu.id_usuario
		WHERE pu.id_plan_usuario = :id_suscripcion
		";
		$parametros = array(array("id_suscripcion", $id_suscripcion));
		$consulta = $this->Conexiones->Select($query, $parametros);

		//agregar clases tomadas
		$query = "
		SELECT 
		c.clase,
		CONCAT(hc.fecha, ' ', hc.horario_inicio, ' - ', hc.horario_fin) as horario_clase,
		CONCAT(i.nombre, ' ', i.apellido) as instructor
		FROM usuario_clase as uc 
		LEFT JOIN clase as c ON c.id_clase = uc.id_clase
		LEFT JOIN horario_clase as hc ON hc.id_clase = uc.id_clase
		LEFT JOIN instructor_clase as ic ON ic.id_clase = uc.id_clase
		LEFT JOIN instructor as i ON i.id_instructor = ic.id_instructor
		WHERE uc.id_plan_usuario = :id_suscripcion
		";
		$parametros = array(array("id_suscripcion", $id_suscripcion));
		$clases = $this->Conexiones->Select($query, $parametros);

		$consulta[0]["clases"] = $clases;

		return json_encode(array("suscripcion" => $consulta[0]));
	}

	function SetEstadoPedido($id_pedido, $id_estado)
	{
		$query = "INSERT INTO estado_pedido(id_venta, id_estado) VALUES(?,?)";
		$parametros = array($id_pedido, $id_estado);
		$c = $this->Conexiones->Insert($query, $parametros);
		return json_encode("actualizado");
	}

	function ReasignarPedido($id_establecimiento, $id_pedido)
	{
		$parametros = array($id_establecimiento, $id_pedido);
		return json_encode($this->Conexiones->ReasignarPedidoProc($parametros));
	}
	//------------------------------------------------ PEDIDO --------------------------

	//------------------------------------------------ PRODUCTO --------------------------
	function GetProductos($id_cat)
	{
		$where_clause = "WHERE a.id_categoria = :id_cat";

		if($id_cat == "15")
		{
			$where_clause = "WHERE a.id_categoria != :id_cat";
		}

		$query = "
		SELECT
		a.id_articulo,
		a.articulo,
		CONCAT
		(
			'<img height=\'70px\' src=\'https://app.vapa-ya.com/Imagenes/' ,(SELECT imagen FROM imagen_articulo WHERE id_articulo = a.id_articulo ORDER by id_imagen DESC LIMIT 1), '\'/>'
		) as imagen_principal,
		IFNULL(ea.precio_establecimiento, 'No seleccionado') as precio, 
		TRUNCATE(IFNULL((
			SELECT 
			(MIN(precio_establecimiento) * config.porc_ganancia) 
			FROM establecimiento_articulo
			WHERE id_articulo = a.id_articulo
		), 0), 2) as precio_promedio,
		(
			CONCAT
			(
				'<div class=\'form-check\'><label class=\'form-check-label\'><input class=\'form-check-input\' type=\'checkbox\'
						',
							IF(IFNULL((
							ea.status
							), 0) = 0, 'unchecked', 'checked')
						,' onclick=\'checkProducto(',a.id_articulo,')\'/>
						<span class=\'form-check-sign\'><span class=\'check\'></span></span></label>
						<button type=\'button\' rel=\'tooltip\' title=\'Información del pedido\' class=\'btn btn-success btn-link btn-sm\' onclick=\'showVentanaProducto(',a.id_articulo,')\'><i class=\'material-icons\'>create</i></button>
						</div>
						'
			)
		) as checked
		FROM articulo as a
		LEFT JOIN establecimiento_articulo as ea ON ea.id_articulo = a.id_articulo AND ea.id_establecimiento = :id_establecimiento2
		INNER JOIN configuracion as config
        $where_clause
		";
		$parametros = array(array("id_cat", $id_cat), array("id_establecimiento2", $this->id_establecimiento));
		$c = $this->Conexiones->Select($query, $parametros);

		//error_log("prods: ".json_encode($c));
		return json_encode(array("productos" => $c));
	}

	function GetCategorias()
	{
		$query = "SELECT * FROM categoria";
		$parametros = array();
		$c = $this->Conexiones->Select($query, $parametros);
		$c = array("categorias" => $c);

		array_push($c["categorias"], array("categoria" => "Todos", "id_categoria" => 15));

		//error_log(json_encode($c));
		return json_encode($c);
	}

	function GetUnidades()
	{
		$query = "SELECT * FROM unidad";
		$parametros = array();
		$c = $this->Conexiones->Select($query, $parametros);

		return json_encode(array("unidades" => $c));
	}

	function GetSubCategorias($id_categoria)
	{
		$query = "SELECT * FROM subcategoria WHERE id_categoria = :id_categoria";
		$parametros = array(array("id_categoria", $id_categoria));
		$c = $this->Conexiones->Select($query, $parametros);

		return json_encode(array("subcategorias" => $c));
	}

	function CheckProducto($id_articulo)
	{
		$parametros = array($this->id_establecimiento, $id_articulo);
		$this->Conexiones->CheckProductoProc($parametros);
	}

	function GetArticulo($id_articulo)
	{
		$query = 
		"
		SELECT
		a.id_articulo,
		a.articulo,
		a.precio,
		a.id_categoria,
		a.id_subcategoria,
		a.descripcion,
		u.id_unidad_,
		u.id_unidad_articulo,
		(SELECT imagen FROM imagen_articulo WHERE id_articulo = a.id_articulo ORDER by id_imagen DESC LIMIT 1) as imagen_principal,
		IFNULL((
			SELECT precio_establecimiento
			FROM establecimiento_articulo
			WHERE id_articulo = a.id_articulo
			AND id_establecimiento = :id_establecimiento
		), 0) as tu_precio
		FROM articulo as a 
		LEFT JOIN unidad_articulo as u ON u.id_articulo = a.id_articulo
		WHERE a.id_articulo = :id_articulo
		";
		$parametros = array(array("id_articulo", $id_articulo), array("id_establecimiento", $this->id_establecimiento));
		$c = $this->Conexiones->Select($query, $parametros);
		return json_encode($c[0]);
	}

	function UpdateArticulo($id_articulo, $precio, $descripcion, $articulo, $categoria, $subcategoria, $unidad, $id_unidad_articulo)
	{
		//update articulo
		$query = "
		UPDATE articulo 
		SET descripcion = ?,
		articulo = ?,
		id_categoria = ?,
		id_subcategoria = ? 
		WHERE id_articulo = ?
		";
		$parametros = array($descripcion, $articulo, $categoria, $subcategoria, $id_articulo);
		$this->Conexiones->Update($query, $parametros);

		//update establecimiento articulo
		$Con = new Conexiones();
		$query = "
		UPDATE establecimiento_articulo 
		SET precio_establecimiento = ?
		WHERE id_establecimiento = ? AND id_articulo = ?
		";
		$parametros = array($precio, $this->id_establecimiento, $id_articulo);
		$Con->Update($query, $parametros);

		//update unidad articulo
		$Con = new Conexiones();
		$query = "UPDATE unidad_articulo SET id_unidad_ = ? WHERE id_unidad_articulo = ? AND id_articulo = ?
		";
		$parametros = array($unidad, $id_unidad_articulo, $id_articulo);
		return $Con->Update($query, $parametros);
	}

	function UpdateImagenProducto($id_art, $imagen)
	{
		error_log("imagen ".$imagen);
		$upload_path = '../../app/Imagenes/';
        //--------------------------------- imagen grande -------------------------------------
        $img_name = $id_art."_img.png";
        //file path to upload in the server 
        $file_path = $upload_path . $img_name;  
        $file = $imagen["tmp_name"];
        $size = getimagesize($file);
        $width0 = $size[0];
        $height0 = $size[1];
        $ratio = $width0/$height0;
        
        if($width0 > 300){
            $width = 300;
            $height = 300/$ratio;
        }else{
            $width = 300;
            $height = 300/$ratio;
        }
        $src = imagecreatefromstring(file_get_contents($file));
		$dst = imagecreatetruecolor($width, $height);
		$fondo = imagecolorallocate($dst, 255, 255, 255);
		imagefill($dst, 0, 0, $fondo);
        imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$size[0],$size[1]);
        imagedestroy($src);
        $file_path = imagepng($dst,$file_path); // adjust format as needed
        //move_uploaded_file($imagen["tmp_name"], $file_path);
        //--------------------------------- imagen grande -------------------------------------

        //--------------------------------- imagen chica -------------------------------------
        $img_name = $id_art."_mini_img.png";
        //file path to upload in the server 
        $file_path = $upload_path . $img_name;  
        $file = $imagen["tmp_name"];
        $size = getimagesize($file);
        $width0 = $size[0];
        $height0 = $size[1];
        $ratio = $width0/$height0;
        
        if($width0 > 150){
            $width = 150;
            $height = 150/$ratio;
        }else{
            $width = 150;
            $height = 150/$ratio;
        }
        $src = imagecreatefromstring(file_get_contents($file));
		$dst = imagecreatetruecolor($width, $height);
		$fondo = imagecolorallocate($dst, 255, 255, 255);
		imagefill($dst, 0, 0, $fondo);
        imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$size[0],$size[1]);
        imagedestroy($src);
        $file_path = imagepng($dst,$file_path); // adjust format as needed
        //move_uploaded_file($imagen["tmp_name"], $file_path);
        //--------------------------------- imagen chica -------------------------------------
	}

	function AltaClase($clase, $descripcion_breve, $descripcion, $minimo, $maximo)
	{
		$query = "INSERT INTO clase(clase, descripcion, breve_descripcion, minimo, maximo) VALUES(?,?,?,?,?)";
		$params = array($clase, $descripcion, $descripcion_breve, $minimo, $maximo);
		return $this->Conexiones->Insert($query, $params);
	}
	//------------------------------------------------ PRODUCTO --------------------------

	//------------------------------------------------ ONESIGNAL --------------------------
	function SetOneSignal($userId)
	{
		$query = "SELECT id_establecimiento FROM onesignal_establecimiento WHERE id_establecimiento = :id_establecimiento AND onesignal_token = :userId";
		$parametros = array(array("id_establecimiento", $this->id_establecimiento), array("userId", $userId));
		$c = $this->Conexiones->Select($query, $parametros);
		if(sizeof($c) == 0)
		{
			$query = "INSERT INTO onesignal_establecimiento(id_establecimiento, onesignal_token) VALUES(?,?)";
			$parametros = array($this->id_establecimiento, $userId);
			$this->Conexiones->Insert($query, $parametros);
		}
	}
	//------------------------------------------------ ONESIGNAL --------------------------

	//------------------------------------------------ USUARIOS --------------------------
	function GetUsuarios()
	{
		$query = "
		SELECT 
		id_usuario,
		CONCAT(nombre, ' ', apellido) as nombre,
		telefono,
		fecha_registro
		FROM usuario ORDER by id_usuario ASC";
		$parametros = array();
		$c = $this->Conexiones->Select($query, $parametros);

		return json_encode(array("usuarios" => $c));
	}

	function GetInstructores()
	{
		$query = "SELECT id_instructor, foto, CONCAT(nombre, ' ', apellido) as nombre, telefono FROM instructor ORDER by id_instructor DESC";
		$params = array();
		$c = $this->Conexiones->Select($query, $params);

		for($i = 0; $i < sizeof($c); $i++)
		{
			$id_instructor = $c[$i]["id_instructor"];
			$foto = $c[$i]["foto"];
			$c[$i]["foto"] = "<img src='../../Plataforma/img/instructores/$foto' width='70px' style='border-radius:10px;' />";
			$c[$i]["otros"] = 
			'<button type="button" rel="tooltip" title="Información del instructor" class="btn btn-success btn-link btn-sm" onclick="showVentanaInstructor(\''.$id_instructor.'\')"><i class="material-icons">launch</i></button>';
		}

		return json_encode(array("instructores" => $c));
	}

	function GetInstructor($id_instructor)
	{
		$query = 
		"
		SELECT
		i.usuario,
		i.clave,
		CONCAT(i.nombre, ' ', i.apellido) as instructor,
		i.telefono,
		i.correo,
		i.foto
		FROM instructor as i
		WHERE i.id_instructor = :id_instructor
		";
		$parametros = array(array("id_instructor", $id_instructor));
		$consulta = $this->Conexiones->Select($query, $parametros);

		//agregar clases
		$query = "
		SELECT 
		c.clase,
		CONCAT(hc.fecha, ' ', hc.horario_inicio, ' - ', hc.horario_fin) as horario_clase,
		CONCAT(u.nombre, ' ', u.apellido) as alumno,
		CONCAT(i.nombre, ' ', i.apellido) as instructor
		FROM instructor_clase as ic
		LEFT JOIN clase as c ON c.id_clase = ic.id_clase
		LEFT JOIN horario_clase as hc ON hc.id_clase = ic.id_clase
		LEFT JOIN instructor as i ON i.id_instructor = ic.id_instructor
		LEFT JOIN usuario_clase as uc ON uc.id_instructor_clase = ic.id_instructor_clase
		LEFT JOIN usuario as u ON u.id_usuario = uc.id_usuario
		WHERE ic.id_instructor = :id_instructor
		";
		$parametros = array(array("id_instructor", $id_instructor));
		$clases = $this->Conexiones->Select($query, $parametros);

		$consulta[0]["clases"] = $clases;

		return json_encode(array("instructor" => $consulta[0]));
	}

	function AltaInstructor($nombre, $apellido, $usuario, $clave, $telefono, $correo, $descripcion, $imagen)
	{
		$parametros = array($usuario, $clave, $nombre, $apellido, $descripcion, $telefono, $correo);
		$c = $this->Conexiones->AltaInstructor($parametros);
		$id_ins = $c;

		$upload_path = '../../Plataforma/img/instructores/';
        //--------------------------------- imagen -------------------------------------
        $img_name = $id_ins."_img.png";
        //file path to upload in the server 
        $file_path = $upload_path . $img_name;  
        $file = $imagen["tmp_name"];
        $size = getimagesize($file);
        $width0 = $size[0];
        $height0 = $size[1];
        $ratio = $width0/$height0;
        
        if($width0 > 720){
            $width = 720;
            $height = 720/$ratio;
        }else{
            $width = 720;
            $height = 720/$ratio;
        }
        $src = imagecreatefromstring(file_get_contents($file));
		$dst = imagecreatetruecolor($width, $height);
		$fondo = imagecolorallocate($dst, 255, 255, 255);
		imagefill($dst, 0, 0, $fondo);
        imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$size[0],$size[1]);
        imagedestroy($src);
        $file_path = imagepng($dst,$file_path); // adjust format as needed
        //move_uploaded_file($imagen["tmp_name"], $file_path);
        //--------------------------------- imagen -------------------------------------
	}
	//------------------------------------------------ USUARIOS --------------------------
}











