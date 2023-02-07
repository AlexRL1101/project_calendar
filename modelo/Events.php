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
}
