<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'conexion.php';

// Verificar conexión
if ($conn->connect_error) {
    echo json_encode(['error' => 'Conexión fallida: ' . $conn->connect_error]);
    exit;
}

// CORREGIDO: usar 'asunto' en lugar de 'motivo'
$sql = "SELECT ID, usuario, asunto, noticia, contraseña, fecha_creacion 
        FROM noticias 
        ORDER BY fecha_creacion DESC 
        LIMIT 50";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode(['error' => 'Error SQL: ' . $conn->error]);
    exit;
}

$noticias = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $noticias[] = [
            'id' => $row['ID'],
            'usuario' => $row['usuario'],
            'asunto' => $row['asunto'],
            'noticia' => $row['noticia'],
            'contraseña' => $row['contraseña'],
            'fecha' => date('d/m/Y H:i', strtotime($row['fecha_creacion']))
        ];
    }
}

echo json_encode($noticias);
$conn->close();
