<?php

use Slim\Factory\AppFactory;
use Dotenv\Dotenv;

require __DIR__ . "/../vendor/autoload.php";

$dotenv = Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$app = AppFactory::create();

$app->setBasePath("");

$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

require_once __DIR__ . "/../src/Database.php";
require_once __DIR__ . "/../src/View.php";

use Cineplanet\App\Rutas;

Rutas::registrarRutas($app);

$app->run();
