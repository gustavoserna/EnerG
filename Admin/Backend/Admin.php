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
	//------------------------------------------------ GRAFICAS --------------------------------------------------------------------
	function GetGraficaVDiarias()
	{
		$query = 
		"
		SELECT
		c.db_date,
		DAYOFWEEK(c.db_date) as dia,
		IFNULL(SUM(v.total), 0) as venta_total
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
		IFNULL(COUNT(DISTINCT v.id_venta), 0) as altas_totales
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

	function GetReservacionesHorario($id_horario_clase) 
	{
		$query = 
		"
		SELECT 
		CONCAT(u.nombre, ' ', u.apellido) as nombre,
		u.telefono
		FROM horario_clase as hc
		LEFT JOIN usuario_clase as uc ON uc.id_horario_clase = hc.id_horario_clase
		LEFT JOIN usuario as u ON u.id_usuario = uc.id_usuario
		WHERE hc.id_horario_clase = :id_horario_clase
		";
		$params = array(array("id_horario_clase", $id_horario_clase));
		$c = $this->Conexiones->Select($query, $params);

		return json_encode(array("usuarios" => $c));
	}

	function GetHorariosClases()
	{
		$query = 
		"
		SELECT
		c.id_clase, c.clase,
		CONCAT(i.nombre, ' ', i.apellido) as instructor,
		hc.id_horario_clase, hc.dia, CONCAT(hc.horario_inicio, ' - ', hc.horario_fin) as horario, hc.fecha
		FROM clase as c
		LEFT JOIN horario_clase as hc ON hc.id_clase = c.id_clase
		LEFT JOIN instructor as i ON i.id_instructor = hc.id_instructor
		WHERE hc.status = 1 AND c.status = 1 AND i.status = 1 
		ORDER BY hc.fecha ASC
		";
		$params = array();
		$c = $this->Conexiones->Select($query, $params);

		for($i = 0; $i < sizeof($c); $i++)
		{
			$id_clase = $c[$i]["id_clase"];
			$id_horario_clase = $c[$i]["id_horario_clase"];
			$c[$i]["otros"] = 
			'<button type="button" rel="tooltip" title="Modificar horario" class="btn btn-success btn-link btn-sm" onclick="showVentanaHorario(\''.$id_clase.'\')"><i class="material-icons">launch</i></button>';
			$c[$i]["reservaciones"] = 
			'<button type="button" rel="tooltip" title="Reservaciones" class="btn btn-success btn-link btn-sm" onclick="verReservaciones(\''.$id_horario_clase.'\')"><i class="material-icons">launch</i></button>';
		}

		return json_encode(array("horarios" => $c));
	}

	function GetHorariosClase($id_clase)
	{
		$query = 
		"
		SELECT
		c.id_clase, c.clase,
		CONCAT(i.nombre, ' ', i.apellido) as instructor,
		hc.id_horario_clase, hc.dia, CONCAT(hc.horario_inicio, ' - ', hc.horario_fin) as horario, hc.fecha
		FROM clase as c
		LEFT JOIN horario_clase as hc ON hc.id_clase = c.id_clase
		LEFT JOIN instructor as i ON i.id_instructor = hc.id_instructor
		WHERE c.id_clase = :id_clase AND hc.status = 1 AND c.status = 1 AND i.status = 1 
		ORDER BY hc.fecha DESC
		";
		$params = array(array("id_clase", $id_clase));
		$c = $this->Conexiones->Select($query, $params);

		for($i = 0; $i < sizeof($c); $i++)
		{
			$id_horario_clase = $c[$i]["id_horario_clase"];
			$c[$i]["otros"] = 
			'<button type="button" rel="tooltip" title="Quitar" class="btn btn-success btn-link btn-sm" onclick="quitarHorario(\''.$id_horario_clase.'\')"><i class="material-icons">delete</i></button>';
		}

		return json_encode(array("horarios" => $c));
	}

	function QuitarHorarioClase($id_horario_clase)
	{
		$query = "UPDATE horario_clase SET status = 0 WHERE id_horario_clase = ?";
		$params = array($id_horario_clase);
		return $this->Conexiones->Update($query, $params);
	}
	//------------------------------------------------ REPORTES ----------------------------------------------------------------------

	//------------------------------------------------ PEDIDO ----------------------------------------------------------------------
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
	//------------------------------------------------ PEDIDO --------------------------

	//------------------------------------------------ PRODUCTO --------------------------

	function AltaClase($clase, $descripcion_breve, $descripcion, $minimo, $maximo)
	{
		$query = "INSERT INTO clase(clase, descripcion, breve_descripcion, minimo, maximo) VALUES(?,?,?,?,?)";
		$params = array($clase, $descripcion, $descripcion_breve, $minimo, $maximo);
		return $this->Conexiones->Insert($query, $params);
	}

	function AgregarHorario($id_clase, $id_instructor, $fecha) 
	{
		$fecha_y_hora = explode(" ", $fecha);
		$fecha = $fecha_y_hora[0];
		$hora_inicio = $fecha_y_hora[1];
		$pm_am = $fecha_y_hora[2];

		$diaSemana = date("l", strtotime($fecha));
		switch ($diaSemana) {
			case "Monday":
				$diaSemana = "Lunes";
				break;
			case "Tuesday":
				$diaSemana = "Martes";
				break;
			case "Wednesday":
				$diaSemana = "Miercoles";
				break;
			case "Thursday":
				$diaSemana = "Jueves";
				break;
			case "Friday":
				$diaSemana = "Viernes";
				break;
			case "Saturday":
				$diaSemana = "Sabado";
				break;
			case "Sunday":
				$diaSemana = "Domingo";
				break;
		}

		$horatime = strtotime($hora_inicio) + 60*60;
		$hora_fin = date("H:i", $horatime)." ".$pm_am;
		$hora_inicio = $hora_inicio." ".$pm_am;

		$Con = new Conexiones();
		$query = "SELECT maximo FROM clase WHERE id_clase = :id_clase";
		$params = array(array("id_clase", $id_clase));
		$c = $Con->Select($query, $params);
		$maximo = $c[0]["maximo"];

		$query = "INSERT INTO horario_clase(id_instructor, id_clase, dia, fecha, horario_inicio, horario_fin, maximo) VALUES(?,?,?,?,?,?,?)";
		$parametros = array($id_instructor, $id_clase, $diaSemana, $fecha, $hora_inicio, $hora_fin, $maximo);
		$this->Conexiones->Insert($query, $parametros);

		$Con = new Conexiones();
		$query = "INSERT INTO instructor_clase(id_instructor, id_clase) VALUES(?, ?)";
		$parametros = array($id_instructor, $id_clase);
		$Con->Insert($query, $parametros);
	}
	//------------------------------------------------ PRODUCTO --------------------------

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

	function GetListaClases()
	{
		$query = "SELECT id_clase, clase, breve_descripcion FROM clase ORDER by clase DESC";
		$params = array();
		$c = $this->Conexiones->Select($query, $params);

		for($i = 0; $i < sizeof($c); $i++)
		{
			$id_clase = $c[$i]["id_clase"];
			$c[$i]["otros"] = 
			'<button type="button" rel="tooltip" title="Información de la clase" class="btn btn-success btn-link btn-sm" onclick="showVentanaClase(\''.$id_clase.'\')"><i class="material-icons">launch</i></button>';
		}

		return json_encode(array("clases" => $c));
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
		i.foto,
		i.descripcion
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

	function ActualizarInstructor($nombre, $usuario, $clave, $telefono, $correo, $descripcion)
	{
		$nombre_completo = explode(' ', $nombre);
		$nombre = $nombre_completo[0];
		$apellido = $nombre_completo[1];

		$query = "UPDATE instructor SET clave = ?, nombre = ?, apellido = ?, descripcion = ?, telefono = ?, correo = ? WHERE usuario = ?";
		$params = array($clave, $nombre, $apellido, $descripcion, $telefono, $correo, $usuario);
		$this->Conexiones->Update($query, $params);
	}
	//------------------------------------------------ USUARIOS --------------------------
}











