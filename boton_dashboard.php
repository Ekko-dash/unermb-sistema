<?php
// boton_dashboard.php - Botón flotante para regresar al dashboard (PHP)
session_start();

// Verificar si hay sesión activa
if (!isset($_SESSION['user_id']) || !isset($_SESSION['rol'])) {
    return; // No mostrar botón si no hay sesión
}

$rol = $_SESSION['rol'];
$dashboardUrl = ($rol === 'encargado') ? 'dashboard_encargado.html' : 'dashboard_empleado.html';
?>

<style>
    .btn-dashboard-flotante {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        background: #00e5ff;
        color: #000;
        border: none;
        border-radius: 50px;
        padding: 12px 24px;
        font-weight: 900;
        font-size: 14px;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0, 229, 255, 0.4);
        transition: all 0.3s ease;
        font-family: 'Roboto', sans-serif;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-dashboard-flotante:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 229, 255, 0.6);
        background: #ffffff;
        color: #000;
        text-decoration: none;
    }
</style>

<a href="<?php echo $dashboardUrl; ?>" class="btn-dashboard-flotante">
    🏠 Regresar al Dashboard
</a>