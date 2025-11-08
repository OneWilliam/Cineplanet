<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Dotenv\Dotenv;

require __DIR__ . "../vendor/autoload.php";

$dotenv = Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

$app = AppFactory::create();

$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->get("/", function (Request $request, Response $response, $args) {
    $response->getBody()->write("¡Hola, mundo desde Slim 4!");
    return $response;
});

$app->get("/hello/{name}", function (
    Request $request,
    Response $response,
    $args,
) {
    $name = $args["name"];
    $response->getBody()->write("Hello, $name");
    return $response;
});

$app->run();
