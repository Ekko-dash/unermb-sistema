<?php
// conexion.php - Versión para InfinityFree
$host = "sql300.infinityfree.com";  // Host de tu base de datos
$user = "if0_42285925";              // Tu usuario
$pass = "j9yzd8k8vvzDvK9";             // La contraseña de tu panel
$db = "if0_42285925_unermb";         // Tu base de datos

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error conexión BD: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
