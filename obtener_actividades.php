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

// Usar backticks para el campo con acento
$sql = "SELECT ID, nombre, `descripcion`, fecha_creacion FROM actividades ORDER BY fecha_creacion DESC";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(['error' => 'Error SQL: ' . $conn->error]);
    exit;
}

$actividades = [];
while ($row = $result->fetch_assoc()) {
    $actividades[] = [
        'id' => $row['ID'],
        'responsable' => $row['nombre'],
        'detalle' => $row['descripcion'],  // Con acento
        'fecha' => date('d/m/Y H:i', strtotime($row['fecha_creacion']))
    ];
}

echo json_encode($actividades);
$conn->close();
