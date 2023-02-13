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

$idbitacora_repetir = isset($_POST["idbitacora_repetir"]) ? limpiarCadena($_POST["idbitacora_repetir"]) : "";
$numero_repite = isset($_POST["numero_repite"]) ? limpiarCadena($_POST["numero_repite"]) : "";
$opciones_repetir = isset($_POST["opciones_repetir"]) ? limpiarCadena($_POST["opciones_repetir"]) : "";
$otra_tiempo_notifica = isset($_POST["otra_tiempo_notifica"]) ? limpiarCadena($_POST["otra_tiempo_notifica"]) : "";
$notifica_antes = isset($_POST["notifica_antes"]) ? limpiarCadena($_POST["notifica_antes"]) : "";

date_default_timezone_set('America/Mexico_City');
$date = date('Y-m-d H:i:s');

function formulasNuevaFechaParaNotificar($fecha, $digito, $formato, $simbolo)
{
    switch ($formato) {
        case 'Minutos':
            return date('Y-m-d H:i:s', strtotime($simbolo . '' . $digito . ' minutes', strtotime($fecha)));
            break;
        case 'Horas':
            return date('Y-m-d H:i:s', strtotime($simbolo . '' . $digito . ' hour', strtotime($fecha)));
            break;
        case 'Dias':
            return date('Y-m-d H:i:s', strtotime($simbolo . '' . $digito . ' day', strtotime($fecha)));
            break;
        case 'Semanas':
            return date('Y-m-d H:i:s', strtotime($simbolo . '' . $digito . ' week', strtotime($fecha)));
            break;
        case 'Meses':
            return date('Y-m-d H:i:s', strtotime($simbolo . '-' . $digito . ' month', strtotime($fecha)));
            break;
    }
}

switch ($_GET["op"]) {
    case 'getEvents':
        $response = $events->getAll();
        $sched_res = [];
        foreach ($response as $row) {
            $row['sdate'] = date("F d, Y h:i A", strtotime($row['start_datetime']));
            $row['edate'] = date("F d, Y h:i A", strtotime($row['end_datetime']));
            $sched_res[$row['id']] = $row;
        }

        echo json_encode($sched_res);
        break;

    case 'saveEvents':
        if ($otra_tiempo_notifica)
            $fecha_notifica = formulasNuevaFechaParaNotificar($start_datetime, $otra_tiempo_notifica, $notifica_antes, '-');
        else
            $fecha_notifica = $start_datetime;

        if (empty($id)) {
            $response = $events->save($title, $description, $start_datetime, $end_datetime, $color, $numero_repite, $opciones_repetir, $otra_tiempo_notifica, $notifica_antes, $fecha_notifica);
            echo $response ? "Evento guardado exitosamente" : "No se pudo guardar";
        } else {
            $response = $events->update($id, $title, $description, $start_datetime, $end_datetime, $color, $numero_repite, $opciones_repetir, $otra_tiempo_notifica, $notifica_antes, $idbitacora_repetir, $fecha_notifica);
            echo $response ? "Datos actualizados" : "No se pudo actualizar";
        }
        break;

    case 'deleteEvents':
        $response = $events->delete($id);
        echo $response ? "Se elimino correctamente" : "No se pudo eliminar";
        break;

    case 'traeFechasNotificaciones':
        $result = $events->traeProximasNotificaciones(date('Y-m-d H:i', strtotime($date)));

        if (mysqli_num_rows($result) > 0)
            while ($reg = $result->fetch_assoc()) {
                if (date('Y-m-d H:i', strtotime($reg['fecha_notifica'])) == date('Y-m-d H:i', strtotime($date))) {
                    $data['title'] = $reg['title'];
                    $data['message'] = $reg['description'];
                    $data['icon'] = '../assets/img/notification.webp';
                    $data['url'] = 'https://localhost:5600';
                    $rows[] = $data;

                    if ($reg['repite'] != 0) {
                        $fecha1_siguiente = formulasNuevaFechaParaNotificar($reg['start_datetime'], $reg['repite'], $reg['formato_repite'], '+');
                        $fecha2_siguiente = formulasNuevaFechaParaNotificar($reg['end_datetime'], $reg['repite'], $reg['formato_repite'], '+');
                        $fecha_notificacion = formulasNuevaFechaParaNotificar($fecha1_siguiente, $reg['notifica'], $reg['formato_notifica'], '-');

                        $ejecucion = $events->proximaRepeticionDeEvento($fecha1_siguiente, $fecha2_siguiente, $reg['id'], $reg['idbitacora_repetir'], $fecha_notificacion);
                    } else {
                        $ejecucion = $events->desactiva($reg['id'], $reg['idbitacora_repetir']);
                    }

                    $array['notif'] = $rows;
                    $array['count'] = 0;
                    $array['result'] = true;

                    if ($ejecucion)
                        echo json_encode($array);
                } else
                    echo 300;
            }
        else
            echo 300;
        break;
}
