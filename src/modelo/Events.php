<?php
//Incluímos inicialmente la conexión a la base de datos
require "../env/Conexion.php";

class Events
{
	//Implementamos nuestro constructor
	public function __construct()
	{
	}

	public function obtenerEventos()
	{
		$sql = "SELECT * from eventos WHERE status=1";
		return ejecutarConsulta($sql);
	}

	public function guardar($title, $description, $start_datetime, $end_datetime, $color, $dpto, $notifica_antes, $fecha_notifica, $idusuario, $fechas_guarda, $durante_tiempo, $otra_tiempo_notifica)
	{
		$res = true;

		if ($durante_tiempo)
			foreach ($fechas_guarda as $key => $value) {
				$sql = "INSERT INTO eventos (title,description,start_datetime,end_datetime,color,idusuario,dpto, fecha_notifica, tiempo_notifica,formato_notifica) VALUES ('$title','$description','$value[0]','$value[1]','$color','$idusuario','$dpto','$value[2]','$otra_tiempo_notifica', '$notifica_antes')";
			     ejecutarConsulta($sql) or $res = false;
			}
		else {
			$sql = "INSERT INTO eventos (title,description,start_datetime,end_datetime,color,idusuario,dpto, fecha_notifica,tiempo_notifica,formato_notifica) VALUES ('$title','$description','$start_datetime','$end_datetime','$color','$idusuario','$dpto', '$fecha_notifica','$otra_tiempo_notifica', '$notifica_antes')";
			ejecutarConsulta($sql) or $res = false;
		}

		return $res;
	}


	public function actualizar($id, $title, $description, $start_datetime, $end_datetime, $color, $dpto, $otra_tiempo_notifica, $notifica_antes, $fecha_notifica)
	{
		$res = true;

		$sql = "UPDATE eventos SET title = '$title', description = '$description', start_datetime = '$start_datetime', end_datetime = '$end_datetime', color = '$color', dpto = '$dpto', tiempo_notifica='$otra_tiempo_notifica', formato_notifica='$notifica_antes',fecha_notifica='$fecha_notifica' where id = '$id';";
		ejecutarConsulta($sql) or $res = false;

		return $res;
	}

	public function eliminar($id)
	{
		$sql = "UPDATE eventos SET status='0' WHERE id='$id'";
		return ejecutarConsulta($sql);
	}

	public function traeProximasNotificaciones($date)
	{
		$sql = "SELECT * from eventos WHERE status = 1 AND fecha_notifica >= '$date' ORDER BY fecha_notifica ASC LIMIT 1;";
		return ejecutarConsulta($sql);
	}

	public function obtenerEvento($id)
	{
		$sql = "SELECT * from eventos WHERE status = '1' and id='$id'";
		return ejecutarConsulta($sql);
	}

	public function listarFiltradoEventos()
	{

		$sql = "SELECT * FROM eventos where status='1'";
		return ejecutarConsulta($sql);
	}

	public function listarFiltradoEventosFecha($buscarFechaInicio, $buscarFechaFin)
	{

		$sql = "SELECT * FROM eventos where start_datetime between '$buscarFechaInicio' and '$buscarFechaFin' and status='1'";
		return ejecutarConsulta($sql);
	}

	public function finalizarEvento($id)
	{
		$sql = "UPDATE eventos SET color='#A9A9A9' WHERE id='$id'";
		return ejecutarConsulta($sql);
	}
}