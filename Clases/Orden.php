<?php
include("Sesion.php");
require_once("../Conekta/lib/Conekta.php");
\Conekta\Conekta::setApiKey("key_zEpPw1URmk9zL9Su2bD34Q");
\Conekta\Conekta::setApiVersion("2.0.0");

class Orden extends Sesion
{
	private $Conexiones;
	private $id_usuario;

	function __construct()
	{
		parent::__construct();
		$this->Conexiones = new Conexiones();
		$this->id_usuario = $this->getUser()["id_usuario"];
	}

	function AltaPlan($id_plan, $id_tarjeta) {
		$procError = "";
		$paramError = "";
		$handlerError = "";

		try {
			//get datos de conekta
			$query = "
			SELECT 
			u.customer_conekta,
			(SELECT id_tarjeta_conekta FROM tarjeta WHERE id_tarjeta = :id_tarjeta) as id_tarjeta_conekta
			FROM usuario as u
			WHERE u.id_usuario = :id_usuario";
			$parametros = array(array("id_usuario", $this->id_usuario), array("id_tarjeta", $id_tarjeta));
			$c = $this->Conexiones->Select($query, $parametros);
			$customer_conekta = $c[0]["customer_conekta"];
			$id_tarjeta_conekta = $c[0]["id_tarjeta_conekta"];

			//obtener datos del plan
			$query = "SELECT * FROM plan WHERE id_plan = :id_plan";
			$parametros = array(array("id_plan", $id_plan));
			$plan = $this->Conexiones->Select($query, $parametros);

			//arreglo de articulos conekta
			$arr_conekta = array();
			array_push($arr_conekta, array('name' => $plan[0]["plan"], 'unit_price' => $plan[0]["precio"] * 100, 'quantity' => 1));

			//hacer cargo
			\Conekta\Order::create
			(
				[
					'currency' => 'MXN',
					'customer_info' => 
					[
						'customer_id' => $customer_conekta
					],
					'line_items' => $arr_conekta,
					'charges' => 
					[
						[
							'payment_method' => 
							[
								'type' => 'card',
								'payment_source_id' => $id_tarjeta_conekta
							]
						]
					]
				]
			);

			//insertar plan al usuario
			$query = "INSERT INTO plan_usuario(id_plan, id_usuario, clases_disponibles) VALUES(?,?,?)";
        	$params = array($id_plan, $this->id_usuario, $plan[0]["total_clases"]);
        	return $this->Conexiones->Insert($query, $params);

		} 
		catch (\Conekta\ProccessingError $error)
		{
			$procError = $error->getCode();
		} 
		catch (\Conekta\ParameterValidationError $error)
		{
			$paramError = $error->getCode();
		} 
		catch (\Conekta\Handler $error)
		{
			$handlerError = $error->getCode();
		}

		return array("procError" => $procError, "paramError" => $paramError, "handlerError" => $handlerError);
	}

	function RegistrarTarjeta($token) {

		$query = "SELECT customer_conekta, nombre, apellido, telefono, email FROM usuario WHERE id_usuario = :id_usuario";
		$parametros = array(array("id_usuario", $this->id_usuario));
		$c = $this->Conexiones->Select($query, $parametros);
		$customer_conekta = $c[0]["customer_conekta"];
		//sse busca si el cliente ya esta registrado en conekta
		if($customer_conekta == "" || $customer_conekta == null) //no esta registrado en conekta
		{
			$nombre = $c[0]["nombre"];
			$apellido = $c[0]["apellido"];
			$telefono = $c[0]["telefono"];
			$correo = $c[0]["email"];
			try 
			{
				//generar al cliente en conekta
				$customer = \Conekta\Customer::create
				(
					[
						"name" => $nombre." ".$apellido,
						"email" => $correo,
						"phone" => "+52".$telefono,
						"payment_sources" => 
						[
							[
								"type" => "card",
								"token_id" => $token
							]
						]
					]
				);

				//meter el id de cliente de conekta al usuario
				$customer_id = $customer["id"];
				$q = "UPDATE usuario SET customer_conekta = ? WHERE id_usuario = ?";
				$parametros = array($customer_id, $this->id_usuario);
				$this->Conexiones->Update($q, $parametros);

				//guardar metodo de pago a la base de datos
				$this->InsertarTarjeta($customer["payment_sources"][0]);
			} 
			catch (\Conekta\ProccessingError $error)
			{
				echo $error->getMesage();
			} 
			catch (\Conekta\ParameterValidationError $error)
			{
				echo $error->getMessage();
			} 
			catch (\Conekta\Handler $error)
			{
				echo $error->getMessage();
			}
		}
		//si esta registrado, solo se le crea una tarjeta nueva
		else 
		{
			try 
			{
				$customer = \Conekta\Customer::find($customer_conekta); // se busca el cliente en conekta
				$source = $customer->createPaymentSource //se le agrega el metodo de pago a conekta
				(
					[
						'token_id' => $token,
						'type'     => 'card'
					]
				);

				//guardar metodo de pago a la base de datos
				$this->InsertarTarjeta($source);
			} 
			catch (\Conekta\ProccessingError $error)
			{
				echo $error->getMesage();
			} 
			catch (\Conekta\ParameterValidationError $error)
			{
				echo $error->getMessage();
			}
			catch (\Conekta\Handler $error)
			{
		    	echo $error->getMessage();
			}
		}

		return $token;

	}

	function InsertarTarjeta($source) {

		$Con = new Conexiones();
		$id_tarjeta = $source["id"];
		$last4 = $source["last4"];
		$exp_month = $source["exp_month"];
		$exp_year = $source["exp_year"];
		$brand = $source["brand"];
		$name = $source["name"];
		if(strtolower($brand) == "visa")
			$brand = 1;
		else if(strtolower($brand) == "mastercard")
			$brand = 2;
		else 
			$brand = 3;
		$query = "INSERT INTO tarjeta(id_tarjeta_conekta, id_usuario, tarjeta, mes_vencimiento, ano_vencimiento, id_marca_tarjeta, nombre) 
					VALUES(?,?,?,?,?,?,?)";
		$parametros = array($id_tarjeta, $this->id_usuario, $last4, $exp_month, $exp_year, $brand, $name);
		$Con->Insert($query, $parametros);

	}
}
?>










