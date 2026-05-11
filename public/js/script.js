// public/js/script.js
// Script global del proyecto Intercambio.
// Aquí van funciones y utilidades compartidas entre todas las vistas.

// Ejemplo: Interceptor global de axios para manejo de errores 401
axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response && error.response.status === 401) {
            window.location.href = '/intercambio/admin/login';
        }
        return Promise.reject(error);
    }
);
