<?php
header('Content-Type: application/json');
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = trim($_POST['cedula'] ?? '');

    if (empty($cedula)) {
        echo json_encode(['status' => 'error', 'mensaje' => 'Cédula requerida']);
        exit;
    }

    if (!preg_match('/^\d{8}$/', $cedula)) {
        echo json_encode(['status' => 'error', 'mensaje' => 'La cédula debe tener 8 dígitos']);
        exit;
    }

    // Verificar si la cédula existe en 'registro'
    $sql_verificar = "SELECT nombre, apellido, cedula, cargo FROM registro WHERE cedula = ?";
    $stmt_verificar = $conn->prepare($sql_verificar);
    $stmt_verificar->bind_param("s", $cedula);
    $stmt_verificar->execute();
    $result_verificar = $stmt_verificar->get_result();

    if ($result_verificar->num_rows === 0) {
        echo json_encode(['status' => 'error', 'mensaje' => '❌ Cédula del empleado no registrada en el sistema']);
        $stmt_verificar->close();
        $conn->close();
        exit;
    }

    $personal = $result_verificar->fetch_assoc();
    $nombre_completo = $personal['nombre'] . ' ' . $personal['apellido'];
    $cargo = $personal['cargo'];
    $fecha_actual = date('Y-m-d');
    $hora_actual = date('H:i:s');

    // Buscar registro de hoy
    $sql_check = "SELECT ID, entrada, salida FROM asistencia WHERE cedula = ? AND fecha = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ss", $cedula, $fecha_actual);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows === 0) {
        // SIN registro hoy → ENTRADA
        $sql_insert = "INSERT INTO asistencia (cedula, fecha, entrada, salida) VALUES (?, ?, ?, NULL)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("sss", $cedula, $fecha_actual, $hora_actual);

        if ($stmt_insert->execute()) {
            echo json_encode([
                'status' => 'success',
                'tipo' => 'ENTRADA',
                'nombre' => $nombre_completo,
                'cargo' => $cargo,
                'hora' => date('h:i A', strtotime($hora_actual)),
                'mensaje' => "✅ ENTRADA registrada: " . $nombre_completo . " a las " . date('h:i A', strtotime($hora_actual))
            ]);
        } else {
            echo json_encode(['status' => 'error', 'mensaje' => '❌ Error al registrar entrada']);
        }
        $stmt_insert->close();
    } else {
        // CON registro hoy → verificar salida
        $asistencia = $result_check->fetch_assoc();

        if (is_null($asistencia['salida'])) {
            // Tiene entrada, sin salida → SALIDA
            $sql_update = "UPDATE asistencia SET salida = ? WHERE ID = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("si", $hora_actual, $asistencia['ID']);

            if ($stmt_update->execute()) {
                echo json_encode([
                    'status' => 'success',
                    'tipo' => 'SALIDA',
                    'nombre' => $nombre_completo,
                    'cargo' => $cargo,
                    'hora' => date('h:i A', strtotime($hora_actual)),
                    'mensaje' => "✅ SALIDA registrada: " . $nombre_completo . " a las " . date('h:i A', strtotime($hora_actual))
                ]);
            } else {
                echo json_encode(['status' => 'error', 'mensaje' => '❌ Error al registrar salida']);
            }
            $stmt_update->close();
        } else {
            // Tiene entrada y salida → error
            echo json_encode([
                'status' => 'error',
                'mensaje' => "⚠️ " . $nombre_completo . " ya completó su jornada hoy"
            ]);
        }
    }

    $stmt_check->close();
    $stmt_verificar->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'mensaje' => 'Método no permitido']);
}
