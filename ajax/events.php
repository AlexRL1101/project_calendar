<?php
ob_start();

	require_once "../modelo/Events.php";
	$events = new Events();

	$id = isset($_POST["id"]) ? limpiarCadena($_POST["id"]) : "";
	$title = isset($_POST["title"]) ? limpiarCadena($_POST["title"]) : "";
	$description = isset($_POST["description"]) ? limpiarCadena($_POST["description"]) : "";
	$start_datetime = isset($_POST["start_datetime"]) ? limpiarCadena($_POST["start_datetime"]) : "";
    $end_datetime = isset($_POST["end_datetime"]) ? limpiarCadena($_POST["end_datetime"]) : "";
    $color = isset($_POST["color"]) ? limpiarCadena($_POST["color"]) : "";
			
		switch ($_GET["op"]) {
            case 'getEvents':
            $response = $events->getAll();
            $sched_res = [];
            foreach($response as $row){
                $row['sdate'] = date("F d, Y h:i A",strtotime($row['start_datetime']));
                $row['edate'] = date("F d, Y h:i A",strtotime($row['end_datetime']));
                $sched_res[$row['id']] = $row;
            }

            echo json_encode($sched_res);
            break;

			case 'saveEvents':
                if(empty($id)){
                    $response = $events-> save($title,$description,$start_datetime,$end_datetime,$color);
                    echo $response ? "Evento guardado exitosamente" : "No se pudo guardar";
                }else{
                    $response = $events->update($id,$title,$description,$start_datetime,$end_datetime,$color);
                    echo $response ? "Datos actualizados" : "No se pudo actualizar";
                }
			break;

            case 'deleteEvents':
                $response = $events->delete($id);
                echo $response ? "Se elimino correctamente" : "No se pudo eliminar";
            break;

		}
		