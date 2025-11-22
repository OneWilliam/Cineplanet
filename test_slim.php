<?php
// Simple test to verify Slim is working

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

// Instantiate the app
$app = AppFactory::create();

// Test route
$app->get('/test', function (Request $request, Response $response) {
    $response->getBody()->write(json_encode(['status' => 'ok', 'message' => 'Slim is working!']));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();