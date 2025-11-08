<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Dotenv\Dotenv;

require __DIR__ . "/../vendor/autoload.php";

$dotenv = Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

$app = AppFactory::create();

$app->setBasePath("");

$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

require_once __DIR__ . "/../src/Database.php";
require_once __DIR__ . "/../src/View.php";

use Cineplanet\App\View;

$app->get("/", function (Request $request, Response $response, $args) {
    try {
        $db = \Cineplanet\App\Database::getConnection();
        $dbStatus = "success";
        $dbError = null;
    } catch (\Exception $e) {
        $dbStatus = "error";
        $dbError = $e->getMessage();
    }

    return View::render($response, "inicio", [
        "title" => "Inicio - Cineplanet",
        "dbStatus" => $dbStatus,
        "dbError" => $dbError,
    ]);
});

$app->get("/peliculas", function (Request $request, Response $response, $args) {
    try {
        $db = \Cineplanet\App\Database::getConnection();

        $peliculas = \Cineplanet\App\Database::executeProcedure('listarPeliculas');

        return View::render($response, "peliculas/lista", [
            "peliculas" => $peliculas,
        ]);
    } catch (\Exception $e) {
        $html =
            '<div class="alert alert-danger">Error: ' .
            htmlspecialchars($e->getMessage()) .
            "</div>";
        $response->getBody()->write($html);
        return $response->withStatus(500);
    }
});

$app->run();
