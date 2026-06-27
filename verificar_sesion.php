<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['user_id']) && isset($_SESSION['rol'])) {
    echo json_encode([
        'autenticado' => true,
        'user_id' => $_SESSION['user_id'],
        'nombre' => $_SESSION['nombre'],
        'rol' => $_SESSION['rol']
    ]);
} else {
    echo json_encode(['autenticado' => false]);
}
