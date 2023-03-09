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
		$sql = "SELECT e.*, br.idbitacora_repetir,br.repite,br.formato_repite,br.notifica,br.formato_notifica FROM eventos e LEFT JOIN bitacora_repetir br ON br.id_evento = e.id AND br.status IN (1,2) WHERE e.status IN (1,2);";
		return ejecutarConsulta($sql);
	}

	public function guardar($title, $description, $start_datetime, $end_datetime, $color, $dpto,$numero_repite, $opciones_repetir, $otra_tiempo_notifica, $notifica_antes, $fecha_notifica, $idusuario)
	{
		$res = true;

		$sql = "INSERT INTO eventos (title,description,start_datetime,end_datetime,color,idusuario,dpto) VALUES ('$title','$description','$start_datetime','$end_datetime','$color','$idusuario','$dpto')";
		$id_evento = ejecutarConsulta_retornarID($sql) or $res = false;

		if ($id_evento) {
			$sql = "INSERT INTO bitacora_repetir(id_evento,repite,formato_repite,notifica,formato_notifica,fecha_repitio,fecha_notifica,status) VALUES ('$id_evento','$numero_repite','$opciones_repetir','$otra_tiempo_notifica','$notifica_antes','$start_datetime','$fecha_notifica',1);";
			ejecutarConsulta($sql) or $res = false;
		}

		return $res;
	}


	public function actualizar($id, $title, $description, $start_datetime, $end_datetime, $color,$dpto, $numero_repite, $opciones_repetir, $otra_tiempo_notifica, $notifica_antes, $idbitacora_repetir, $fecha_notifica)
	{
		$res = true;

		$sql = "UPDATE eventos SET title = '$title', description = '$description', start_datetime = '$start_datetime', end_datetime = '$end_datetime', color = '$color', dpto = '$dpto'  where id = '$id';";
		ejecutarConsulta($sql) or $res = false;

		if ($res) {
			$sql = "UPDATE bitacora_repetir SET repite = '$numero_repite',formato_repite = '$opciones_repetir',notifica = '$otra_tiempo_notifica',formato_notifica = '$notifica_antes',fecha_repitio = '$start_datetime',fecha_notifica = '$fecha_notifica' WHERE idbitacora_repetir = '$idbitacora_repetir';";
			ejecutarConsulta($sql) or $res = false;
		}

		return $res;
	}

	public function eliminar($id)
	{
		$sql = "UPDATE eventos SET status='0' WHERE id='$id'";
		return ejecutarConsulta($sql);
	}

	public function traeProximasNotificaciones($date)
	{
		$sql = "SELECT ev.id,ev.title,ev.description,ev.start_datetime,ev.end_datetime,ev.color,ev.dpto,br.idbitacora_repetir,br.repite,br.formato_repite,br.notifica,br.formato_notifica,br.fecha_repitio,br.fecha_notifica FROM eventos ev INNER JOIN bitacora_repetir br ON ev.id = br.id_evento WHERE ev.status = 1 AND br.status = 1 AND br.fecha_notifica >= '$date' ORDER BY br.fecha_notifica ASC LIMIT 1;";
		return ejecutarConsulta($sql);
	}

	public function proximaRepeticionDeEvento($date1, $date2, $id, $idbitacora_repetir, $fecha_notificacion)
	{
		$res = true;

		$sql = "UPDATE eventos SET status = 2 WHERE id = '$id';";
		ejecutarConsulta($sql) or $res = false;

		$sql = "UPDATE bitacora_repetir SET status = 2 WHERE idbitacora_repetir = '$idbitacora_repetir';";
		ejecutarConsulta($sql) or $res = false;

		if ($res) {
			$sql = "INSERT INTO eventos(title,description,start_datetime,end_datetime,color,dpto,idusuario) SELECT title,description,'$date1','$date2',color,dpto,idusuario FROM eventos WHERE id = '$id';";
			$id_evento = ejecutarConsulta_retornarID($sql) or $res = false;

			if ($id_evento) {
				$sql = "INSERT INTO bitacora_repetir(id_evento,repite,formato_repite,notifica,formato_notifica,fecha_repitio,fecha_notifica,status) SELECT '$id_evento',repite,formato_repite,notifica,formato_notifica,'$date1','$fecha_notificacion',1 FROM bitacora_repetir WHERE idbitacora_repetir = '$idbitacora_repetir';";
				ejecutarConsulta($sql) or $res = false;
			}
		}

		return $res;
	}

	public function desactiva($id, $idbitacora_repetir)
	{
		$res = true;

		$sql = "UPDATE eventos SET status = 2 WHERE id = '$id';";
		ejecutarConsulta($sql) or $res = false;

		$sql = "UPDATE bitacora_repetir SET status = 2 WHERE idbitacora_repetir = '$idbitacora_repetir';";
		ejecutarConsulta($sql) or $res = false;

		return $res;
	}

	
	public function obtenerEvento($id)
	{
		$sql = "SELECT e.*, br.idbitacora_repetir,br.repite,br.formato_repite,br.notifica,br.formato_notifica FROM eventos e LEFT JOIN bitacora_repetir br ON br.id_evento = e.id AND br.status IN (1,2) WHERE e.status IN (1,2) and id='$id'";
		return ejecutarConsulta($sql);
	}

	public function listarFiltradoEventos(){

		$sql = "SELECT * FROM eventos where status='1'";
		return ejecutarConsulta($sql);
		
	}

	public function listarFiltradoEventosFecha($buscarFechaInicio,$buscarFechaFin){

		$sql = "SELECT * FROM eventos where start_datetime between '$buscarFechaInicio' and '$buscarFechaFin' and status='1'";
		return ejecutarConsulta($sql);
		
	}

}
