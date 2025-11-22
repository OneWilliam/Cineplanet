<?php
/**
 * Punto de entrada principal para la aplicación
 * Si es una solicitud API, se incluye el backend Slim
 * Si es una solicitud de página web, se sirve el frontend SPA
 */

// Si la solicitud es para una API, incluir Slim
if (preg_match('/^\/api\//', $_SERVER['REQUEST_URI'])) {
    require_once __DIR__.'/public/index.php';
} else {
    // Para cualquier otra solicitud, servir el frontend SPA
    if (file_exists(__DIR__.'/public/index.html')) {
        readfile(__DIR__.'/public/index.html');
        exit();
    } else {
        // En caso de que no exista el archivo HTML
        header("HTTP/1.0 404 Not Found");
        echo "Frontend SPA file not found";
        exit();
    }
}
