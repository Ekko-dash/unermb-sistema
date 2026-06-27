<?php
header('Content-Type: application/json');
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Obtener los datos del formulario
    $nombre = trim($_POST['nombre'] ?? '');
    $cedula = trim($_POST['cedula'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $usuario = trim($_POST['usuario'] ?? '');
    $contraseña = trim($_POST['password'] ?? ''); // Del formulario viene como 'password'

    // Validar que no haya campos vacíos
    if (empty($nombre) || empty($cedula) || empty($correo) || empty($usuario) || empty($contraseña)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
        exit;
    }

    // Validar formato de correo
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Correo electrónico inválido']);
        exit;
    }

    // Validar que la cédula sea numérica
    if (!is_numeric($cedula)) {
        echo json_encode(['success' => false, 'message' => 'La cédula debe contener solo números']);
        exit;
    }

    // Verificar si la cédula ya existe en tabla encargado
    $check_sql = "SELECT ID FROM encargado WHERE cedula = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $cedula);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => '❌ Ya existe un encargado con esta cédula']);
        $check_stmt->close();
        $conn->close();
        exit;
    }
    $check_stmt->close();

    // Verificar si el correo ya existe
    $check_sql2 = "SELECT ID FROM encargado WHERE correo = ?";
    $check_stmt2 = $conn->prepare($check_sql2);
    $check_stmt2->bind_param("s", $correo);
    $check_stmt2->execute();
    $check_result2 = $check_stmt2->get_result();

    if ($check_result2->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => '❌ Ya existe un encargado con este correo']);
        $check_stmt2->close();
        $conn->close();
        exit;
    }
    $check_stmt2->close();

    // Verificar si el usuario ya existe
    $check_sql3 = "SELECT ID FROM encargado WHERE usuario = ?";
    $check_stmt3 = $conn->prepare($check_sql3);
    $check_stmt3->bind_param("s", $usuario);
    $check_stmt3->execute();
    $check_result3 = $check_stmt3->get_result();

    if ($check_result3->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => '❌ Ya existe un encargado con este usuario']);
        $check_stmt3->close();
        $conn->close();
        exit;
    }
    $check_stmt3->close();

    // Insertar los datos en la tabla 'encargado'
    $sql = "INSERT INTO encargado (nombre, cedula, correo, usuario, contraseña) 
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nombre, $cedula, $correo, $usuario, $contraseña);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => '✅ Encargado registrado exitosamente',
            'id' => $stmt->insert_id
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => '❌ Error al guardar: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
