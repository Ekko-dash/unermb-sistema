<?php
// boton_dashboard_universal.php - Botón flotante universal para regresar al dashboard
// Incluir este archivo en todas las páginas HTML/PHP

session_start();

// Verificar si hay sesión activa
if (!isset($_SESSION['user_id']) || !isset($_SESSION['rol'])) {
    // No mostrar botón si no hay sesión
    return;
}

$rol = $_SESSION['rol'];
$dashboardUrl = ($rol === 'encargado') ? 'dashboard_encargado.html' : 'dashboard_empleado.html';
$dashboardText = ($rol === 'encargado') ? 'Panel Encargado' : 'Panel Empleado';
$icono = ($rol === 'encargado') ? '👑' : '👤';
?>

<style>
    .btn-dashboard-universal {
        position: fixed;
        bottom: 25px;
        right: 25px;
        z-index: 9999;
        background: linear-gradient(135deg, #00e5ff, #0099cc);
        color: #000;
        border: none;
        border-radius: 50px;
        padding: 12px 24px;
        font-weight: 900;
        font-size: 14px;
        cursor: pointer;
        box-shadow: 0 4px 20px rgba(0, 229, 255, 0.5);
        transition: all 0.3s ease;
        font-family: 'Roboto', sans-serif;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        backdrop-filter: blur(5px);
        letter-spacing: 0.5px;
    }

    .btn-dashboard-universal:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 8px 30px rgba(0, 229, 255, 0.7);
        background: linear-gradient(135deg, #ffffff, #00e5ff);
        color: #000;
        text-decoration: none;
    }

    .btn-dashboard-universal:active {
        transform: translateY(2px);
    }

    /* Responsive para móviles */
    @media (max-width: 768px) {
        .btn-dashboard-universal {
            bottom: 15px;
            right: 15px;
            padding: 10px 18px;
            font-size: 12px;
            gap: 6px;
        }
    }
</style>

<a href="<?php echo $dashboardUrl; ?>" class="btn-dashboard-universal" title="Volver al dashboard">
    🏠 <?php echo $icono; ?> <?php echo $dashboardText; ?>
</a>