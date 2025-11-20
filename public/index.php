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

use Cineplanet\App\Rutas;

Rutas::registrarRutas($app);

$app->run();
