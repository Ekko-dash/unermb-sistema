<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'conexion.php';

echo "=== PRUEBA DIRECTA DE guardar_asistencia.php ===<br><br>";

$cedula = "30880463";
echo "Probando con cédula: $cedula<br><br>";

// Verificar conexión
if ($conn->connect_error) {
    echo "❌ Error conexión: " . $conn->connect_error . "<br>";
    exit;
}
echo "✅ Conexión OK<br><br>";

// Verificar si existe en registro
$sql = "SELECT nombre, apellido, cargo FROM registro WHERE cedula = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $cedula);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "❌ Cédula NO encontrada<br>";
    exit;
}

$personal = $result->fetch_assoc();
$nombre_completo = $personal['nombre'] . ' ' . $personal['apellido'];
$cargo = $personal['cargo'];
$fecha_hoy = date('Y-m-d');
$hora_actual = date('H:i:s');

echo "Usuario: $nombre_completo<br>";
echo "Cargo: $cargo<br>";
echo "Fecha: $fecha_hoy<br>";
echo "Hora: $hora_actual<br><br>";

$stmt->close();

// Buscar asistencia de hoy
$sql_buscar = "SELECT ID, entrada, salida FROM asistencia WHERE cedula = ? AND fecha = ?";
$stmt = $conn->prepare($sql_buscar);
$stmt->bind_param("ss", $cedula, $fecha_hoy);
$stmt->execute();
$result_asistencia = $stmt->get_result();
$asistencia = $result_asistencia->fetch_assoc();
$stmt->close();

if (!$asistencia) {
    echo "No hay asistencia hoy. Insertando ENTRADA...<br>";

    $sql_insert = "INSERT INTO asistencia (cedula, fecha, entrada) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("sss", $cedula, $fecha_hoy, $hora_actual);

    if ($stmt->execute()) {
        echo "✅ ENTRADA REGISTRADA EXITOSAMENTE!<br>";
        echo "JSON que se enviaría al navegador:<br>";
        echo json_encode([
            'status' => 'success',
            'nombre' => $nombre_completo,
            'cargo' => $cargo,
            'mensaje' => "✅ ENTRADA registrada a las " . date('h:i A')
        ], JSON_PRETTY_PRINT);
    } else {
        echo "❌ Error al insertar: " . $stmt->error . "<br>";
    }
    $stmt->close();
} elseif (empty($asistencia['salida'])) {
    echo "Tiene entrada pero no salida. Actualizando SALIDA...<br>";

    $sql_update = "UPDATE asistencia SET salida = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("si", $hora_actual, $asistencia['ID']);

    if ($stmt->execute()) {
        echo "✅ SALIDA REGISTRADA EXITOSAMENTE!<br>";
    } else {
        echo "❌ Error al actualizar: " . $stmt->error . "<br>";
    }
    $stmt->close();
} else {
    echo "⚠️ Ya tiene entrada y salida registrada hoy<br>";
}

$conn->close();
