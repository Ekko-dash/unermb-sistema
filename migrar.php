<?php
// migrar.php - Importa tu base de datos completa
require_once 'conexion.php';

// Leer el archivo SQL
$sql_file = file_get_contents('unermb.sql');

// Dividir las consultas por ";" (cuidado con los comentarios)
$queries = explode(";", $sql_file);

echo "<h1>🚀 Importando base de datos a Railway...</h1>";

$errores = 0;
$exitos = 0;

foreach ($queries as $query) {
    $query = trim($query);

    // Saltar líneas vacías y comentarios
    if (empty($query) || strpos($query, '--') === 0 || strpos($query, '/*') === 0) {
        continue;
    }

    // Intentar ejecutar la consulta
    if ($conn->query($query) === TRUE) {
        $exitos++;
    } else {
        $errores++;
        echo "❌ Error: " . $conn->error . "<br>";
        echo "Consulta: " . substr($query, 0, 100) . "...<br><br>";
    }
}

echo "<hr>";
echo "<h2>✅ Importación completada</h2>";
echo "<p>Consultas exitosas: $exitos</p>";
echo "<p>Errores: $errores</p>";

echo "<br><a href='index.login.html'>🔐 Ir al Login</a>";

$conn->close();
