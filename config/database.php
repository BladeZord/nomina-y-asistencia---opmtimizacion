<?php
/**
 * Conexión a la base de datos.
 * Usa variables de entorno: DB_HOST, DB_USER, DB_PASSWORD, DB_NAME
 */
if (!defined('APP_CONFIG_LOADED')) {
    require_once __DIR__ . '/app.php';
}

$host     = getenv('DB_HOST') ?: 'localhost';
$user     = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$database = getenv('DB_NAME') ?: 'apsystem';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    if (defined('APP_DEBUG') && APP_DEBUG) {
        die('Error de conexión: ' . $conn->connect_error);
    }
    die('Error de conexión. Contacte al administrador.');
}

$conn->set_charset('utf8mb4');
