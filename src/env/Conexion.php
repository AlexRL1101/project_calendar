<?php
$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$db   = getenv('DB_NAME');
$port = getenv('DB_PORT');

$ssl_ca = realpath(__DIR__ . "/../assets/cert/cacert.pem");

// Crear conexión MySQLi
$conexion = mysqli_init();

// Configurar SSL (solo CA, no necesitas client-cert ni client-key para PlanetScale)
mysqli_ssl_set($conexion, NULL, NULL, $ssl_ca, NULL, NULL);

// Conectar con SSL
if (!mysqli_real_connect($conexion, $host, $user, $pass, $db, $port, NULL, MYSQLI_CLIENT_SSL)) {
    printf("❌ Falló conexión a la base de datos: %s\n");
    exit();
}

// Configurar charset
mysqli_set_charset($conexion, "utf8");

// ----------------------
// FUNCIONES AUXILIARES
// ----------------------
if (!function_exists('ejecutarConsulta')) {
    function ejecutarConsulta($sql)
    {
        global $conexion;
        return $conexion->query($sql);
    }

    function ejecutarConsultaLimit($sql)
    {
        global $conexion;
        $conexion->query("SET SESSION group_concat_max_len = 1000000");
        return $conexion->query($sql);
    }

    function ejecutarConsultaSimpleFila($sql)
    {
        global $conexion;
        $query = $conexion->query($sql);
        return $query ? $query->fetch_assoc() : null;
    }

    function ejecutarConsulta_retornarID($sql)
    {
        global $conexion;
        $conexion->query($sql);
        return $conexion->insert_id;
    }

    function limpiarCadena($str)
    {
        global $conexion;
        $str = mysqli_real_escape_string($conexion, trim($str));
        return htmlspecialchars($str);
    }

    function ejecutarConsultaComoJSON($sql)
    {
        global $conexion;
        $query = $conexion->query($sql);

        if ($query) {
            $resultados = [];
            while ($fila = $query->fetch_assoc()) {
                $resultados[] = $fila;
            }
            return json_encode($resultados);
        } else {
            return false;
        }
    }
}
?>