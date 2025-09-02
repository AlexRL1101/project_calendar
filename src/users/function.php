<?php
function db_query($query) {
    $host = getenv('DB_HOST');
    $user = getenv('DB_USER');
    $pass = getenv('DB_PASS');
    $db   = getenv('DB_NAME');
    $port = getenv('DB_PORT');

    // Ruta al certificado
    $ssl_ca = realpath(__DIR__ . "/../assets/cert/ca.pem");

    $connection = mysqli_init();

    if ($ssl_ca && file_exists($ssl_ca)) {
        mysqli_ssl_set($connection, NULL, NULL, $ssl_ca, NULL, NULL);
        $flags = MYSQLI_CLIENT_SSL;
    } else {
        $flags = 0; // Conectar sin SSL
    }

    if (!mysqli_real_connect($connection, $host, $user, $pass, $db, $port, NULL, $flags)) {
        die("❌ Falló conexión a la base de datos: ");
    }

    $result = mysqli_query($connection, $query);

    return $result;
}

 function insert($tblname,$form_data){
	$fields = array_keys($form_data);
	$sql="INSERT INTO ".$tblname."(".implode(',', $fields).")  VALUES('".implode("','", $form_data)."')";
	
	return db_query($sql);

}
function delete($tblname,$field_id,$id){

	$sql = "delete from ".$tblname." where ".$field_id."=".$id."";
	
	return db_query($sql);
}
function edit($tblname,$form_data,$field_id,$id){
	$sql = "UPDATE ".$tblname." SET ";
	$data = array();

	foreach($form_data as $column=>$value){

		$data[] =$column."="."'".$value."'";

	}
	$sql .= implode(',',$data);
	$sql.=" where ".$field_id." = ".$id."";
	return db_query($sql); 
}
function select_id($tblname,$field_name,$field_id){
	$sql = "Select * from ".$tblname." where ".$field_name." = ".$field_id."";
	$db=db_query($sql);
	$GLOBALS['row'] = mysqli_fetch_object($db);

	return $sql;

}
?>