// boton_dashboard_universal.js - Versión mejorada con depuración

async function agregarBotonDashboard() {
    console.log("🔍 Intentando agregar botón dashboard...");

    try {
        const response = await fetch('verificar_sesion.php');
        const data = await response.json();

        console.log("📡 Respuesta del servidor:", data);

        if (!data.autenticado) {
            console.log("❌ Usuario no autenticado, no se muestra el botón");
            return;
        }

        const rol = data.rol;
        const dashboardUrl = rol === 'encargado' ? 'dashboard_encargado.html' : 'dashboard_empleado.html';
        const dashboardText = rol === 'encargado' ? '📊 Panel Encargado' : '👤 Panel Empleado';

        console.log(`✅ Usuario autenticado como: ${rol}`);
        console.log(`🔗 URL del dashboard: ${dashboardUrl}`);

        // Verificar si el botón ya existe
        if (document.querySelector('.btn-dashboard-universal')) {
            console.log("⚠️ El botón ya existe, no se crea otro");
            return;
        }

        // Crear el botón
        const btn = document.createElement('a');
        btn.href = dashboardUrl;
        btn.className = 'btn-dashboard-universal';
        btn.innerHTML = `🏠 ${dashboardText}`;
        btn.title = 'Volver al dashboard';

        // Estilos del botón (más específicos y con !important)
        btn.style.cssText = `
            position: fixed !important;
            bottom: 25px !important;
            right: 25px !important;
            z-index: 99999 !important;
            background: linear-gradient(135deg, #00e5ff, #0099cc) !important;
            color: #000 !important;
            border: none !important;
            border-radius: 50px !important;
            padding: 12px 24px !important;
            font-weight: 900 !important;
            font-size: 14px !important;
            cursor: pointer !important;
            box-shadow: 0 4px 20px rgba(0, 229, 255, 0.5) !important;
            transition: all 0.3s ease !important;
            font-family: 'Roboto', sans-serif !important;
            text-decoration: none !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 10px !important;
            backdrop-filter: blur(5px) !important;
            letter-spacing: 0.5px !important;
        `;

        // Efectos hover
        btn.addEventListener('mouseenter', () => {
            btn.style.transform = 'translateY(-4px) scale(1.02)';
            btn.style.boxShadow = '0 8px 30px rgba(0, 229, 255, 0.7)';
            btn.style.background = 'linear-gradient(135deg, #ffffff, #00e5ff)';
        });

        btn.addEventListener('mouseleave', () => {
            btn.style.transform = 'translateY(0) scale(1)';
            btn.style.boxShadow = '0 4px 20px rgba(0, 229, 255, 0.5)';
            btn.style.background = 'linear-gradient(135deg, #00e5ff, #0099cc)';
        });

        // Agregar al body
        document.body.appendChild(btn);
        console.log("✅ Botón agregado correctamente al DOM");

    } catch (error) {
        console.error('❌ Error al agregar botón dashboard:', error);
    }
}

// Ejecutar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', agregarBotonDashboard);
} else {
    // Si el DOM ya está cargado, ejecutar inmediatamente
    agregarBotonDashboard();
}

// También ejecutar después de un pequeño retraso para asegurar
setTimeout(agregarBotonDashboard, 500);