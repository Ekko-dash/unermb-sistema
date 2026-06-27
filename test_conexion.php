<?php
echo "=== PRUEBA DE CONEXIÓN ===<br><br>";

// Probar conexión
require_once 'conexion.php';

if ($conn->connect_error) {
    echo "❌ Error de conexión: " . $conn->connect_error . "<br>";
} else {
    echo "✅ Conexión exitosa a la base de datos 'unermb'<br><br>";
}

// Verificar tabla registro
$sql = "SELECT COUNT(*) as total FROM registro";
$result = $conn->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    echo "✅ Tabla 'registro': " . $row['total'] . " registros encontrados<br>";
} else {
    echo "❌ Error en tabla registro: " . $conn->error . "<br>";
}

// Verificar estructura de tabla asistencia
$sql = "DESCRIBE asistencia";
$result = $conn->query($sql);
if ($result) {
    echo "<br>📋 ESTRUCTURA DE TABLA 'asistencia':<br>";
    while ($row = $result->fetch_assoc()) {
        echo "- {$row['Field']} : {$row['Type']}";
        if ($row['Key'] == 'PRI') echo " (CLAVE PRIMARIA)";
        if ($row['Extra'] == 'auto_increment') echo " (AUTO_INCREMENT)";
        echo "<br>";
    }
} else {
    echo "❌ Error: " . $conn->error . "<br>";
}

$conn->close();
