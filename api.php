<?php
// API Entry Point - index.php in root

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Cineplanet\App\Controllers\AuthController;
use Cineplanet\App\Controllers\MoviesController;

require __DIR__ . "/vendor/autoload.php";

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Database connection
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

// Create Slim app without container
$app = AppFactory::create();

// Set base path for API routes
$app->setBasePath("/api");

// Add the routing middleware
$app->addRoutingMiddleware();

// Error middleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Create controller instances
$authController = new AuthController($pdo);
$moviesController = new MoviesController($pdo);

// API Routes (sin /api porque ya está en basePath)
// Root API endpoint
$app->get("/", function (Request $request, Response $response) {
    $response->getBody()->write(
        json_encode([
            "message" => "Cineplanet API",
            "version" => "1.0",
            "endpoints" => [
                "GET /api/health" => "Health check",
                "POST /api/login" => "User login",
                "POST /api/register" => "User registration",
            ],
        ]),
    );
    return $response->withHeader("Content-Type", "application/json");
});

$app->get("/health", function (Request $request, Response $response) {
    $response
        ->getBody()
        ->write(json_encode(["status" => "ok", "timestamp" => date("c")]));
    return $response->withHeader("Content-Type", "application/json");
});

// Auth routes
$app->post("/login", [$authController, "login"]);
$app->post("/register", [$authController, "register"]);

// Movies routes
$app->get("/movies", [$moviesController, "getAll"]);
$app->get("/movies/{id}", [$moviesController, "getById"]);

// Fallback route for non-API requests - redirect to SPA
$app->any("/{routes:.+}", function (Request $request, Response $response) {
    // For non-API routes, we should not reach the PHP backend
    // This is just a safety fallback
    $response->getBody()->write(
        json_encode([
            "error" =>
                "API endpoint not found. This is the API backend, not the SPA frontend.",
        ]),
    );
    return $response
        ->withHeader("Content-Type", "application/json")
        ->withStatus(404);
});

// Run the app
$app->run();
