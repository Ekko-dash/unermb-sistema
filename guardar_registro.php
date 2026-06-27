<?php
header('Content-Type: application/json');
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Obtener los datos del formulario correctamente
    $nombres = trim($_POST['nombres'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $cedula = trim($_POST['cedula'] ?? '');
    $cargo = trim($_POST['cargo'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $usuario = trim($_POST['usuario'] ?? '');
    $contraseña = trim($_POST['contraseña'] ?? '');

    // Validar campos vacíos
    if (empty($nombres) || empty($apellidos) || empty($cedula) || empty($cargo) || empty($correo) || empty($usuario) || empty($contraseña)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
        exit;
    }

    // Validar formato de correo
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Correo electrónico inválido']);
        exit;
    }

    // Validar que la cédula sea numérica y tenga 8 dígitos
    if (!is_numeric($cedula) || strlen($cedula) != 8) {
        echo json_encode(['success' => false, 'message' => 'La cédula debe contener 8 dígitos numéricos']);
        exit;
    }

    // Verificar si la cédula ya existe
    $check_sql = "SELECT ID FROM registro WHERE cedula = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $cedula);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => '❌ Ya existe un registro con esta cédula']);
        $check_stmt->close();
        $conn->close();
        exit;
    }
    $check_stmt->close();

    // Verificar si el correo ya existe
    $check_sql2 = "SELECT ID FROM registro WHERE correo = ?";
    $check_stmt2 = $conn->prepare($check_sql2);
    $check_stmt2->bind_param("s", $correo);
    $check_stmt2->execute();
    $check_result2 = $check_stmt2->get_result();

    if ($check_result2->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => '❌ Ya existe un registro con este correo']);
        $check_stmt2->close();
        $conn->close();
        exit;
    }
    $check_stmt2->close();

    // Verificar si el usuario ya existe
    $check_sql3 = "SELECT ID FROM registro WHERE usuario = ?";
    $check_stmt3 = $conn->prepare($check_sql3);
    $check_stmt3->bind_param("s", $usuario);
    $check_stmt3->execute();
    $check_result3 = $check_stmt3->get_result();

    if ($check_result3->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => '❌ Ya existe un registro con este usuario']);
        $check_stmt3->close();
        $conn->close();
        exit;
    }
    $check_stmt3->close();

    // Insertar los datos
    // En el INSERT, agrega el campo rol
    $sql = "INSERT INTO registro (nombre, apellido, cedula, cargo, correo, usuario, contraseña, rol) 
        VALUES (?, ?, ?, ?, ?, ?, ?, 'empleado')";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $nombres, $apellidos, $cedula, $cargo, $correo, $usuario, $contraseña);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => '✅ Registro exitoso. ¡Bienvenido!',
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
