<?php
header('Content-Type: application/json');
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $cedula = trim($_POST['cedula'] ?? '');
    $motivo = trim($_POST['mensaje'] ?? '');

    if (empty($nombre) || empty($cedula) || empty($motivo)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
        exit;
    }

    // VERIFICAR SI EL NOMBRE Y CÉDULA EXISTEN EN LA TABLA REGISTRO
    $sql_verificar = "SELECT ID, nombre, apellido, cedula FROM registro WHERE cedula = ? AND (nombre = ? OR CONCAT(nombre, ' ', apellido) = ?)";
    $stmt_verificar = $conn->prepare($sql_verificar);
    $stmt_verificar->bind_param("sss", $cedula, $nombre, $nombre);
    $stmt_verificar->execute();
    $result_verificar = $stmt_verificar->get_result();

    if ($result_verificar->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => '❌ No estás registrado en el sistema. Por favor, regístrate primero.']);
        $stmt_verificar->close();
        $conn->close();
        exit;
    }

    $registro = $result_verificar->fetch_assoc();
    $nombre_completo_bd = $registro['nombre'] . ' ' . $registro['apellido'];

    // Procesar la foto subida
    $comprobante = '';
    if (isset($_FILES['foto_justificativo']) && $_FILES['foto_justificativo']['error'] === UPLOAD_ERR_OK) {
        $archivo_temporal = $_FILES['foto_justificativo']['tmp_name'];
        $extension = strtolower(pathinfo($_FILES['foto_justificativo']['name'], PATHINFO_EXTENSION));
        $nombre_archivo = time() . '_' . uniqid() . '.' . $extension;
        $ruta_destino = 'uploads/' . $nombre_archivo;

        // Crear carpeta uploads si no existe
        if (!file_exists('uploads')) {
            mkdir('uploads', 0777, true);
        }

        // Mover el archivo a la carpeta uploads
        if (move_uploaded_file($archivo_temporal, $ruta_destino)) {
            $comprobante = $ruta_destino;
        } else {
            echo json_encode(['success' => false, 'message' => '❌ Error al subir el archivo']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => '❌ Debes subir un comprobante']);
        exit;
    }

    // Insertar el justificativo en la tabla (usando los nombres de columna correctos)
    $sql = "INSERT INTO justificativos (nombre, cedula, comprobante, motivo) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nombre_completo_bd, $cedula, $comprobante, $motivo);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => '✅ Justificativo enviado exitosamente para ' . $nombre_completo_bd
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => '❌ Error al enviar: ' . $stmt->error]);
    }

    $stmt_verificar->close();
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
