// navbar_controller.js - Controlador de navegación según rol

// Configuración de qué enlaces ver según el rol
const NAV_CONFIG = {
    empleado: {
        visible: [
            { href: "asistencia.index.html", text: "Asistencia" },
            { href: "index actividades personales.html", text: "Actividades" },
            { href: "index noticias.html", text: "Noticias" },
            { href: "index justificativo.html", text: "Justificativos" }
        ],
        hidden: [
            "index.registro.html",
            "index encargado.html",
            "index.tablas de asistencia.html"
        ]
    },
    encargado: {
        visible: [
            { href: "index.registro.html", text: "Registro" },
            { href: "index encargado.html", text: "Registro de encargado" },
            { href: "asistencia.index.html", text: "Asistencia" },
            { href: "index actividades personales.html", text: "Actividades" },
            { href: "index noticias.html", text: "Noticias" },
            { href: "index justificativo.html", text: "Justificativos" },
            { href: "index.tablas de asistencia.html", text: "Tablas" }
        ]
    }
};

// Enlace de login (siempre visible para ambos roles cuando no hay sesión)
const LOGIN_LINK = { href: "index.login.html", text: "Login" };

// Enlace de logout (para usuarios autenticados)
const LOGOUT_LINK = { href: "#", text: "Cerrar Sesión", onclick: "cerrarSesionYRecargar()" };

// Función para cerrar sesión
function cerrarSesionYRecargar() {
    fetch('cerrar_sesion.php')
        .then(() => {
            window.location.href = 'index.login.html';
        })
        .catch(() => {
            window.location.href = 'index.login.html';
        });
}

// Función principal para configurar la navegación
async function configurarNavegacion() {
    const navContainer = document.querySelector('.nav-links, .enlaces-contenedor');
    if (!navContainer) return;

    try {
        const response = await fetch('verificar_sesion.php');
        const data = await response.json();

        let enlacesMostrar = [];
        let mostrarLogin = false;

        if (data.autenticado && data.rol) {
            const rol = data.rol;

            if (rol === 'empleado') {
                enlacesMostrar = [...NAV_CONFIG.empleado.visible];
            } else if (rol === 'encargado') {
                enlacesMostrar = [...NAV_CONFIG.encargado.visible];
            }

            // Agregar botón de logout para usuarios autenticados
            enlacesMostrar.push(LOGOUT_LINK);
        } else {
            // Si no hay sesión, mostrar solo login
            enlacesMostrar = [LOGIN_LINK];
        }

        // Limpiar y reconstruir el navbar
        navContainer.innerHTML = '';

        enlacesMostrar.forEach(enlace => {
            const a = document.createElement('a');
            a.href = enlace.href;
            a.textContent = enlace.text;

            if (enlace.onclick) {
                a.onclick = (e) => {
                    e.preventDefault();
                    eval(enlace.onclick);
                };
            }

            // Marcar el enlace activo según la página actual
            const currentPath = window.location.pathname.split('/').pop();
            if (currentPath === enlace.href) {
                a.classList.add('active');
            }

            navContainer.appendChild(a);
        });

        // Guardar el rol en localStorage para uso en otros componentes
        if (data.autenticado && data.rol) {
            localStorage.setItem('user_rol', data.rol);
            localStorage.setItem('user_nombre', data.nombre);
        } else {
            localStorage.removeItem('user_rol');
            localStorage.removeItem('user_nombre');
        }

    } catch (error) {
        console.error('Error al configurar navegación:', error);
        // Si hay error, mostrar solo login como fallback
        navContainer.innerHTML = '';
        const a = document.createElement('a');
        a.href = "index.login.html";
        a.textContent = "Login";
        navContainer.appendChild(a);
    }
}

// Función para verificar si el usuario tiene acceso a una página específica
async function verificarAccesoPagina() {
    const paginasRestringidasEncargado = ['index.registro.html', 'index encargado.html', 'index.tablas de asistencia.html'];
    const paginaActual = window.location.pathname.split('/').pop();

    // Si la página actual no está en la lista de restringidas, permitir acceso
    if (!paginasRestringidasEncargado.includes(paginaActual)) {
        return true;
    }

    try {
        const response = await fetch('verificar_sesion.php');
        const data = await response.json();

        if (data.autenticado && data.rol === 'encargado') {
            return true; // Encargado puede acceder
        } else {
            // Redirigir al dashboard de empleado
            window.location.href = 'dashboard_empleado.html';
            return false;
        }
    } catch (error) {
        console.error('Error al verificar acceso:', error);
        window.location.href = 'index.login.html';
        return false;
    }
}

// Ejecutar configuración cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    configurarNavegacion();
});