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

// Consultar asistencias con JOIN a registro
$sql = "SELECT 
            a.ID,
            a.cedula,
            a.fecha,
            a.entrada,
            a.salida,
            r.nombre,
            r.apellido,
            r.cargo
        FROM asistencia a
        INNER JOIN registro r ON a.cedula = r.cedula
        ORDER BY a.fecha DESC, a.entrada DESC
        LIMIT 50";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode(['error' => 'Error SQL: ' . $conn->error]);
    exit;
}

$asistencias = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $asistencias[] = [
            'id' => $row['ID'],
            'cedula' => $row['cedula'],
            'nombre_completo' => $row['nombre'] . ' ' . $row['apellido'],
            'cargo' => $row['cargo'],
            'fecha' => date('d/m/Y', strtotime($row['fecha'])),
            'entrada' => $row['entrada'] ? date('h:i A', strtotime($row['entrada'])) : '--:--',
            'salida' => $row['salida'] ? date('h:i A', strtotime($row['salida'])) : '--:--'
        ];
    }
}

echo json_encode($asistencias);
$conn->close();
