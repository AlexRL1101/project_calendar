<?php
//Incluímos inicialmente la conexión a la base de datos
require "../env/Conexion.php";

class Events
{
	//Implementamos nuestro constructor
	public function __construct()
	{
	}

	public function getAll()
	{
		$sql = "SELECT * FROM eventos WHERE status='1'";
		return ejecutarConsulta($sql);
	}

	public function save($title, $description, $start_datetime, $end_datetime, $color)
	{
		$sql = "INSERT INTO eventos (title,description,start_datetime,end_datetime,color) VALUES ('$title','$description','$start_datetime','$end_datetime','$color')";
		return ejecutarConsulta($sql);
	}


	public function update($id, $title, $description, $start_datetime, $end_datetime, $color)
	{
		$sql = "UPDATE eventos SET title = '{$title}', description = '{$description}', start_datetime = '{$start_datetime}', end_datetime = '{$end_datetime}',  color = '{$color}' where id = '{$id}'";
		return ejecutarConsulta($sql);
	}

	public function delete($id)
	{
		$sql = "UPDATE eventos SET status='0' WHERE id='$id'";
		return ejecutarConsulta($sql);
	}

	public function traeProximasNotificaciones()
	{
		$sql = "SELECT ev.id,ev.title,ev.description,ev.start_datetime,ev.end_datetime,ev.color,br.horas,br.fecha_repitio FROM eventos ev LEFT JOIN bitacora_repetir br ON ev.id = br.id_evento AND br.status = 1 WHERE ev.status = 1 AND ev.start_datetime >= CURRENT_TIMESTAMP();";
		return ejecutarConsulta($sql);
	}
}
