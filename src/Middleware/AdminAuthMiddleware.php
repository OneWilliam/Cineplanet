<?php

namespace Cineplanet\App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Cineplanet\App\View;

class AdminAuthMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        // Check if user is logged in and has admin role
        if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'admin') {
            // Create a redirect response
            $response = new \Slim\Psr7\Response();
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        // User is authenticated as admin, continue with the request
        return $handler->handle($request);
    }
}