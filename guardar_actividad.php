<?php
header('Content-Type: application/json');
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $responsable = trim($_POST['responsable'] ?? '');
    $detalle = trim($_POST['detalle'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($responsable) || empty($detalle) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
        exit;
    }

    // Verificar si la contraseña existe en la tabla encargado
    $sql_check = "SELECT ID, nombre FROM encargado WHERE contraseña = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $password);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => '❌ Contraseña no válida. No tienes permisos para publicar actividades.']);
        $stmt_check->close();
        $conn->close();
        exit;
    }

    $encargado = $result_check->fetch_assoc();

    // Usar backticks para el campo con acento
    $sql = "INSERT INTO actividades (nombre, `descripcion`, contraseña) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $responsable, $detalle, $password);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => '✅ Actividad publicada exitosamente por ' . $encargado['nombre']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => '❌ Error al publicar: ' . $stmt->error]);
    }

    $stmt_check->close();
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
