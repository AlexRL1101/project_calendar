<?php
$host = getenv('DB_HOST') ?: 'sql5.freesqldatabase.com';
$user = getenv('MYSQL_USER')?: 'sql5785606';
$pass = getenv('MYSQL_PASSWORD')?: 'Y68vPLzeQz';
$db   = getenv('MYSQL_DATABASE')?: 'sql5785606';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db","$user","$pass");
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    print_r($e);
    die('No se puede conectar a la base de datos');
}
?>