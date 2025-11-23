<?php

// Configuración de errores - desactivar en producción
// https://www.php.net/manual/es/function.error-reporting.php
error_reporting(E_ALL);
// https://www.php.net/manual/es/function.ini-set.php
ini_set("display_errors", 1);

// Iniciar sesión para autenticación
// https://www.php.net/manual/es/function.session-start.php
session_start();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . "/vendor/autoload.php";

// Cargar variables de entorno con Dotenv
// Cargar variables de entorno con Dotenv
// https://github.com/vlucas/phpdotenv#usage
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Variables de conexión a la base de datos
// https://www.php.net/manual/es/pdo.construct.php
$host = $_ENV["DB_HOST"] ?? "localhost";
$dbname = $_ENV["DB_NAME"] ?? "cineplanet";
$username = $_ENV["DB_USER"] ?? "root";
$password = $_ENV["DB_PASS"] ?? "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Crear una instancia de la aplicación Slim
// https://www.slimframework.com/docs/v4/start/installation.html
$app = AppFactory::create();
// Establecer el path base de la API
// https://www.slimframework.com/docs/v4/objects/application.html#setbasepath
$app->setBasePath("/api");

// Middleware de Slim
// https://www.slimframework.com/docs/v4/middleware/routing.html
$app->addRoutingMiddleware();
// Middleware de manejo de errores
// https://www.slimframework.com/docs/v4/middleware/error-handling.html
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Endpoint raíz de la API
// https://www.slimframework.com/docs/v4/objects/router.html#get
$app->get("/", function (Request $request, Response $response) {
    $response->getBody()->write(
        json_encode([
            "message" => "Cineplanet API",
            "version" => "1.0",
            "endpoints" => [
                "GET /api/health" => "Health check",
            ],
        ]),
    );
    return $response->withHeader("Content-Type", "application/json");
});

// Endpoint de chequeo de salud (health check)
// https://www.slimframework.com/docs/v4/cookbook/retrieving-current-route.html
$app->get("/health", function (Request $request, Response $response) {
    $response
        ->getBody()
        ->write(json_encode(["status" => "ok", "timestamp" => date("c")]));
    return $response->withHeader("Content-Type", "application/json");
});

// Cargar rutas desde archivos independientes en backend/Routes
// Cada archivo define un grupo de rutas según el dominio (público, admin, usuario, auth)
// https://www.slimframework.com/docs/v4/objects/router.html#grouping-routes
foreach (["public", "admin", "auth", "user"] as $routeFile) {
    $routePath = __DIR__ . "/backend/Routes/{$routeFile}.php";
    if (file_exists($routePath)) {
        $routeLoader = require $routePath;
        if (is_callable($routeLoader)) {
            $routeLoader($app, $pdo);
        }
    }
}

// Ruta fallback para endpoints no encontrados
// https://www.slimframework.com/docs/v4/cookbook/enable-cors.html#catch-all-options-request
$app->any("/{routes:.+}", function (Request $request, Response $response) {
    $response->getBody()->write(
        json_encode([
            "error" => "API endpoint no encontrado.",
        ]),
    );
    return $response
        ->withHeader("Content-Type", "application/json")
        ->withStatus(404);
});

// Ejecutar la aplicación Slim
// https://www.slimframework.com/docs/v4/start/installation.html#run-your-application
$app->run();
