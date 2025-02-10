<?php
/**
 * Configuración de la conexión a la base de datos MySQL
 * Este archivo maneja la conexión a la base de datos del sistema RSS Feed
 */

// Credenciales de la base de datos
$host = "localhost";     // Servidor de la base de datos
$user = "root";         // Usuario de MySQL
$pass = "";            // Contraseña del usuario
$dbname = "rss_feed_db"; // Nombre de la base de datos

// Crear conexión usando mysqli
$conn = new mysqli($host, $user, $pass, $dbname);

// Verificar si hay errores en la conexión
if ($conn->connect_error) {
    die(json_encode(["error" => "Conexión fallida: " . $conn->connect_error]));
}

// Establecer el conjunto de caracteres a UTF-8
$conn->set_charset("utf8");
?>
