<?php
class Conexiones
{
	private $dbh;

	function __construct()
	{
		$dsn = "mysql:host=localhost;dbname=energ;charset=utf8mb4";
		$options = [
			PDO::ATTR_EMULATE_PREPARES   => true, // turn off emulation mode for "real" prepared statements
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
		];
		$this->dbh = new PDO($dsn, "root", "", $options);
	}

	function Select($q, $parametros)
	{
		try
		{
		    /**
		   	* Before executing our SQL statement, we need to prepare it by 'binding' parameters.
		    * We will bind our validated user input (in this case, it's the value of $id) to our
		    * SQL statement before sending it to the database server.
		    *
		    * This fixes the SQL injection vulnerability.
		    */
		    //$q = "SELECT * FROM productos WHERE idproducto = :id";
		    
		    // Prepare the SQL query
		    $sth = $this->dbh->prepare($q);
		    // Bind parameters to statement variables
		    for($i = 0; $i < sizeof($parametros); $i++)
		    {
		    	$sth->bindParam((string)":".$parametros[$i][0], $parametros[$i][1]);
		    }

		    // Execute statement
		    $sth->execute();

		    // Set fetch mode to FETCH_ASSOC to return an array indexed by column name
		    $sth->setFetchMode(PDO::FETCH_ASSOC);

		    // Fetch result
		    $result = $sth->fetchAll();

		    return $result;
		    
		    //Close the connection to the database
		    $this->dbh = null;
		}
		catch(PDOException $e)
		{
			return $e;
			/**
		    * You can log PDO exceptions to PHP's system logger, using the Operating System's
		    * system logging mechanism
		    *
		    * For more logging options visit http://php.net/manual/en/function.error-log.php
		    */
		    error_log('PDOException - ' . $e->getMessage(), 0);
		    /**
		    * Stop executing, return an 'Internal Server Error' HTTP status code (500),
		    * and display an error
		    */
		    http_response_code(500);
		    die($e->getMessage());
	    }
	}

	function Insert($q, $parametros)
	{
		try
		{   
		    // Prepare the SQL query
		    $sth = $this->dbh->prepare($q);
		    // Bind parameters to statement variables

		    // Execute statement
		    $sth->execute($parametros);
		    
		    //Close the connection to the database
		    $this->dbh = null;
		}
		catch(PDOException $e)
		{
		    error_log('PDOException - ' . $e->getMessage(), 0);
		    http_response_code(500);
		    die('Error establishing connection with database: '.$e->getMessage());
		    return $e->getMessage();
	    }
	}

	function Update($q, $parametros)
	{
		try
		{   
		    // Prepare the SQL query
		    $sth = $this->dbh->prepare($q);
		    // Bind parameters to statement variables

		    // Execute statement
		    $sth->execute($parametros);
		    
		    //Close the connection to the database
		    $this->dbh = null;
		}
		catch(PDOException $e)
		{
			echo ($e->getMessage());
		    error_log('PDOException - ' . $e->getMessage(), 0);
		    http_response_code(500);
		    die('Error establishing connection with database');
		    return $e->getMessage();
	    }
	}

	function Delete($q, $parametros)
	{
		try
		{   
		    // Prepare the SQL query
		    $sth = $this->dbh->prepare($q);
		    // Bind parameters to statement variables

		    // Execute statement
		    $sth->execute($parametros);
		    
		    //Close the connection to the database
		    $this->dbh = null;
		}
		catch(PDOException $e)
		{
		    error_log('PDOException - ' . $e->getMessage(), 0);
		    http_response_code(500);
		    die('Error establishing connection with database');
		    return $e->getMessage();
	    }
	}
	
	function AgendarClaseProc($params)
	{
		try
		{
			// calling stored procedure command
			//parametro que sale agregar @ enves de :
	        $sql = 'CALL AgendarClase
	        (
				:id_usuario,
				:id_instructor_clase,
				:id_horario_clase
			)';

	        // prepare for execution of the stored procedure
	        $stmt = $this->dbh->prepare($sql);

	        // pass value to the command
			$stmt->bindParam(':id_usuario', $params[0], PDO::PARAM_STR);
			$stmt->bindParam(':id_instructor_clase', $params[1], PDO::PARAM_STR);
			$stmt->bindParam(':id_horario_clase', $params[2], PDO::PARAM_STR);

	        // execute the stored procedure
	        $stmt->execute();

	        $stmt->closeCursor();

	        // execute the second query to get customer's level
	        /*$row = $this->dbh->query("SELECT @comprobacion AS compracion")->fetch(PDO::FETCH_ASSOC);

	        $json = json_encode($row);
	        return $row["compracion"];*/
	    }
	    catch (PDOException $e)
	    {
	    	die("Error occurred:" . $e->getMessage());
	    	return $e->getMessage();
	    }
	}

	function CancelarClaseProc($params)
	{
		try
		{
			// calling stored procedure command
			//parametro que sale agregar @ enves de :
	        $sql = 'CALL CancelarClase
	        (
				:id_usuario_clase,
				@respuesta
			)';

	        // prepare for execution of the stored procedure
	        $stmt = $this->dbh->prepare($sql);

	        // pass value to the command
			$stmt->bindParam(':id_usuario_clase', $params[0], PDO::PARAM_STR);
	        // execute the stored procedure
	        $stmt->execute();

	        $stmt->closeCursor();

	        // execute the second query to get customer's level
	        $row = $this->dbh->query("SELECT @respuesta AS respuesta")->fetch(PDO::FETCH_ASSOC);

	        return $row["respuesta"];
	    }
	    catch (PDOException $e)
	    {
	    	die("Error occurred:" . $e->getMessage());
	    	return $e->getMessage();
	    }
	}

	function AltaInstructor($params)
	{
		try
		{
			// calling stored procedure command
			//parametro que sale agregar @ enves de :
	        $sql = 'CALL AltaInstructor
	        (
	        	:usuario,
	        	:clave,
	        	:nombre,
	        	:apellido,
	        	:descripcion,
	        	:telefono,
				:correo,
	        	@id_ins
			)';

	        // prepare for execution of the stored procedure
	        $stmt = $this->dbh->prepare($sql);

	        // pass value to the command
	        $stmt->bindParam(':usuario', $params[0], PDO::PARAM_STR);
	        $stmt->bindParam(':clave', $params[1], PDO::PARAM_STR);
	        $stmt->bindParam(':nombre', $params[2], PDO::PARAM_STR);
	        $stmt->bindParam(':apellido', $params[3], PDO::PARAM_STR);
	        $stmt->bindParam(':descripcion', $params[4], PDO::PARAM_STR);
			$stmt->bindParam(':telefono', $params[5], PDO::PARAM_STR);
			$stmt->bindParam(':correo', $params[6], PDO::PARAM_STR);

	        // execute the stored procedure
	        $stmt->execute();

	        $stmt->closeCursor();

	        // execute the second query to get customer's level
	        $row = $this->dbh->query("SELECT @id_ins AS id_ins")->fetch(PDO::FETCH_ASSOC);

	        $json = json_encode($row);
	        return $row["id_ins"];
	    }
	    catch (PDOException $e)
	    {
	    	die("Error occurred:" . $e->getMessage());
	    	return $e->getMessage();
	    }
	}
    
}
?>