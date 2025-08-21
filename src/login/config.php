<?php
$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$db   = getenv('DB_NAME');
$port = getenv('DB_PORT');

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // Fuerza conexión SSL
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
        PDO::MYSQL_ATTR_SSL_CA => __DIR__ . "../assets/cert/cacert.pem" // ruta al certificado CA
    ];

    $conn = new PDO($dsn, $user, $pass, $options);
} catch(PDOException $e) {
    print_r($e);
    die('❌ No se puede conectar a la base de datos');
}