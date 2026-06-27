<?php
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($usuario) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
        exit;
    }

    // PRIMERO: Buscar en ENCARGADO (rol principal)
    $sql_encargado = "SELECT ID, nombre, cedula, correo, usuario, contraseña, 'encargado' as rol 
                      FROM encargado 
                      WHERE usuario = ?";
    $stmt = $conn->prepare($sql_encargado);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if ($password === $user['contraseña']) {
            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['cedula'] = $user['cedula'];
            $_SESSION['correo'] = $user['correo'];
            $_SESSION['usuario'] = $user['usuario'];
            $_SESSION['rol'] = 'encargado';
            $_SESSION['tipo_tabla'] = 'encargado';

            echo json_encode([
                'success' => true,
                'message' => '✅ Bienvenido Encargado ' . $user['nombre'],
                'redirect' => 'dashboard_encargado.html',
                'rol' => 'encargado'
            ]);
            exit;
        }
    }
    $stmt->close();

    // SEGUNDO: Buscar en REGISTRO (empleados)
    $sql_empleado = "SELECT ID, nombre, apellido, cedula, correo, usuario, contraseña, rol 
                     FROM registro 
                     WHERE usuario = ?";
    $stmt2 = $conn->prepare($sql_empleado);
    $stmt2->bind_param("s", $usuario);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    if ($result2->num_rows === 1) {
        $user = $result2->fetch_assoc();

        if ($password === $user['contraseña']) {
            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['nombre'] = $user['nombre'] . ' ' . $user['apellido'];
            $_SESSION['cedula'] = $user['cedula'];
            $_SESSION['correo'] = $user['correo'];
            $_SESSION['usuario'] = $user['usuario'];
            $_SESSION['rol'] = $user['rol']; // 'empleado'
            $_SESSION['tipo_tabla'] = 'registro';

            // Verificar si el empleado tiene rol encargado (caso especial)
            if ($user['rol'] === 'encargado') {
                echo json_encode([
                    'success' => true,
                    'message' => '✅ Bienvenido Encargado ' . $user['nombre'],
                    'redirect' => 'dashboard_encargado.html',
                    'rol' => 'encargado'
                ]);
            } else {
                echo json_encode([
                    'success' => true,
                    'message' => '✅ Bienvenido Empleado ' . $user['nombre'],
                    'redirect' => 'dashboard_empleado.html',
                    'rol' => 'empleado'
                ]);
            }
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => '❌ Contraseña incorrecta']);
            exit;
        }
    }
    $stmt2->close();

    echo json_encode(['success' => false, 'message' => '❌ Usuario no encontrado en el sistema']);
    $conn->close();
}
