<?php

namespace Cineplanet\App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class UserAuthMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            // Create a redirect response
            $response = new \Slim\Psr7\Response();
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        // User is authenticated, continue with the request
        return $handler->handle($request);
    }
}