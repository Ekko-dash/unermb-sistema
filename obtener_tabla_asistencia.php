<?php
header('Content-Type: application/json');
require_once 'conexion.php';

// Consulta específica para la tabla de reportes
// Esta consulta muestra TODAS las asistencias registradas
$sql = "SELECT 
            a.cedula,
            a.fecha,
            a.entrada,
            a.salida,
            r.nombre,
            r.apellido,
            r.cargo
        FROM asistencia a
        INNER JOIN registro r ON a.cedula = r.cedula
        ORDER BY a.fecha DESC, a.entrada DESC";

$result = $conn->query($sql);
$datos_tabla = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Calcular estado de la asistencia
        $estado = '';
        if ($row['entrada'] && $row['salida']) {
            $estado = 'COMPLETO';
        } elseif ($row['entrada'] && !$row['salida']) {
            $estado = 'SIN SALIDA';
        } else {
            $estado = 'INCOMPLETO';
        }

        $datos_tabla[] = [
            'cedula' => $row['cedula'],
            'nombre_completo' => $row['nombre'] . ' ' . $row['apellido'],
            'cargo' => $row['cargo'],
            'fecha' => date('d/m/Y', strtotime($row['fecha'])),
            'entrada' => $row['entrada'] ? date('h:i A', strtotime($row['entrada'])) : '--:--',
            'salida' => $row['salida'] ? date('h:i A', strtotime($row['salida'])) : '--:--',
            'estado' => $estado
        ];
    }
}

echo json_encode($datos_tabla);
$conn->close();
