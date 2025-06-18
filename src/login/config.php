<?php
$host = getenv('DB_HOST') ?: 'db';          // en Docker Compose el servicio se llama "db"
$user = getenv('MYSQL_USER');
$pass = getenv('MYSQL_PASSWORD');
$db   = getenv('MYSQL_DATABASE');

try {
    $conn = new PDO("mysql:host=$host;dbname=$db","$user","$pass");
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    print_r($e);
    die('No se puede conectar a la base de datos');
}
?>