<?php

namespace Cineplanet\App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Server\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $response = new \Slim\Psr7\Response();

        // Revisa si la sesion se encuntra
        if (!isset($_SESSION["user_id"]) || empty($_SESSION["user_id"])) {
            $body = json_encode([
                "success" => false,
                "message" => "Autenticacion requerida",
            ]);

            $response = $response->withHeader(
                "Content-Type",
                "application/json",
            );
            $response->getBody()->write($body);

            return $response->withStatus(401);
        }

        // Agrega los datos necesarios para los controladores
        $request = $request->withAttribute("user_id", $_SESSION["user_id"]);
        $request = $request->withAttribute(
            "user_data",
            $_SESSION["user_data"] ?? null,
        );

        // Continua
        return $handler->handle($request);
    }
}
