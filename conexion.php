<?php
// conexion.php - Versión para Railway

// Obtener variables de entorno de Railway
$host = getenv('MYSQLHOST') ?: 'localhost';
$user = getenv('MYSQLUSER') ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: '';
$db = getenv('MYSQLDATABASE') ?: 'unermb';
$port = getenv('MYSQLPORT') ?: '3306';

// Crear conexión
$conn = new mysqli($host, $user, $pass, $db, $port);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// (Opcional) Para depuración, descomenta la línea de abajo:
// echo "✅ Conexión exitosa a la base de datos";
