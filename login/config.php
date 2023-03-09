<?php
$server = "localhost";
$username = "root";
$password = "";
$dbname = "Vesselcal";

try {
    $conn = new PDO("mysql:host=$server;dbname=$dbname","$username","$password");
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    die('No se puede conectar a la base de datos');
}
?>