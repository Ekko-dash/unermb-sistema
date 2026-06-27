// boton_dashboard.js - Botón flotante para regresar al dashboard

(function () {
    // Verificar sesión para saber a qué dashboard regresar
    fetch('verificar_sesion.php')
        .then(res => res.json())
        .then(data => {
            if (data.autenticado) {
                let dashboardUrl = '';

                if (data.rol === 'encargado') {
                    dashboardUrl = 'dashboard_encargado.html';
                } else if (data.rol === 'empleado') {
                    dashboardUrl = 'dashboard_empleado.html';
                } else {
                    return; // No hay sesión válida, no mostrar botón
                }

                // Crear el botón flotante
                crearBotonFlotante(dashboardUrl);
            }
        })
        .catch(() => {
            // No hay sesión, no mostrar botón
        });

    function crearBotonFlotante(dashboardUrl) {
        // Verificar si ya existe el botón
        if (document.getElementById('btn-regresar-dashboard')) return;

        // Crear el botón
        const btn = document.createElement('button');
        btn.id = 'btn-regresar-dashboard';
        btn.innerHTML = '🏠 Regresar al Dashboard';
        btn.onclick = function () {
            window.location.href = dashboardUrl;
        };

        // Estilos del botón flotante
        btn.style.position = 'fixed';
        btn.style.bottom = '20px';
        btn.style.right = '20px';
        btn.style.zIndex = '9999';
        btn.style.background = '#00e5ff';
        btn.style.color = '#000';
        btn.style.border = 'none';
        btn.style.borderRadius = '50px';
        btn.style.padding = '12px 24px';
        btn.style.fontWeight = '900';
        btn.style.fontSize = '14px';
        btn.style.cursor = 'pointer';
        btn.style.boxShadow = '0 4px 15px rgba(0, 229, 255, 0.4)';
        btn.style.transition = 'all 0.3s ease';
        btn.style.fontFamily = "'Roboto', sans-serif";

        // Efecto hover
        btn.onmouseenter = function () {
            this.style.transform = 'translateY(-3px)';
            this.style.boxShadow = '0 8px 25px rgba(0, 229, 255, 0.6)';
            this.style.background = '#ffffff';
        };
        btn.onmouseleave = function () {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 4px 15px rgba(0, 229, 255, 0.4)';
            this.style.background = '#00e5ff';
        };

        document.body.appendChild(btn);
    }
})();