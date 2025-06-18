<?php

$host = getenv('DB_HOST') ?: 'db';          // en Docker Compose el servicio se llama "db"
$user = getenv('MYSQL_USER');
$pass = getenv('MYSQL_PASSWORD');
$db   = getenv('MYSQL_DATABASE');

$conexion = new mysqli($host, $user, $pass, $db);

mysqli_query($conexion, 'SET NAMES "utf8"');

//Si tenemos un posible error en la conexión lo mostramos
if (mysqli_connect_errno()) {
	printf("Falló conexión a la base de datos: %s\n", mysqli_connect_error());
	exit();
}

if (!function_exists('ejecutarConsulta')) {
	function ejecutarConsulta($sql)
	{
		global $conexion;
		$query = $conexion->query($sql);
		return $query;
	}

	function ejecutarConsultaLimit($sql)
	{
		global $conexion;

		// Aumentar el límite para la sesión actual (si no lo has hecho ya)
		$conexion->query("SET SESSION group_concat_max_len = 1000000");

		$query = $conexion->query($sql);
		return $query;
	}


	function ejecutarConsultaSimpleFila($sql)
	{
		global $conexion;
		$query = $conexion->query($sql);
		$row = $query->fetch_assoc();
		return $row;
	}

	function ejecutarConsulta_retornarID($sql)
	{
		global $conexion;
		$query = $conexion->query($sql);
		return $conexion->insert_id;
	}

	function limpiarCadena($str)
	{
		global $conexion;
		$str = mysqli_real_escape_string($conexion, trim($str));
		return htmlspecialchars($str);
	}

	function ejecutarConsultaComoJSON($sql)
	{
		global $conexion;
		$query = $conexion->query($sql);

		// Comprueba si la consulta fue exitosa
		if ($query) {
			$resultados = array();
			while ($fila = $query->fetch_assoc()) {
				$resultados[] = $fila;
			}
			// Convierte el array de resultados en formato JSON
			$jsonResultados = json_encode($resultados);
			return $jsonResultados;
		} else {
			// Manejar el caso en que la consulta falla
			return false;
		}
	}
}
