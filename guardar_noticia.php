<?php
header('Content-Type: application/json');
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cambiar 'asunto' por 'motivo' (según tu tabla)
    $usuario = trim($_POST['usuario'] ?? '');
    $asunto = trim($_POST['asunto'] ?? '');
    $noticia = trim($_POST['noticia'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($usuario) || empty($asunto) || empty($noticia) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
        exit;
    }

    // 🔐 VERIFICAR QUE EL USUARIO Y CONTRASEÑA EXISTAN EN LA TABLA ENCARGADO
    $sql_check = "SELECT ID, nombre FROM encargado WHERE usuario = ? AND contraseña = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ss", $usuario, $password);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => '❌ Usuario o contraseña no válidos. No tienes permisos para publicar noticias.']);
        $stmt_check->close();
        $conn->close();
        exit;
    }

    $encargado = $result_check->fetch_assoc();

    // Insertar la noticia en la tabla (usando 'motivo' según tu estructura)
    $sql = "INSERT INTO noticias (usuario, asunto, noticia, contraseña) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $usuario, $asunto, $noticia, $password);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => '✅ Noticia publicada exitosamente por ' . $encargado['nombre']
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
