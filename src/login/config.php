<?php
$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$db   = getenv('DB_NAME');

try {
    $conn = new PDO("mysql:host=$host;dbname=$db","$user","$pass");
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    print_r($e);
    die('No se puede conectar a la base de datos');
}
?>