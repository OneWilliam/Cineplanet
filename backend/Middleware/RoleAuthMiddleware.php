<?php

namespace Cineplanet\App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Server\MiddlewareInterface;

class RoleAuthMiddleware implements MiddlewareInterface
{
    private $requiredRoles;

    public function __construct(array $requiredRoles = [])
    {
        $this->requiredRoles = $requiredRoles;
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $response = new \Slim\Psr7\Response();

        if (!isset($_SESSION["user_id"]) || empty($_SESSION["user_id"])) {
            $body = json_encode([
                "success" => false,
                "message" => "Authentication required",
            ]);

            $response = $response->withHeader(
                "Content-Type",
                "application/json",
            );
            $response->getBody()->write($body);

            return $response->withStatus(401);
        }

        if (empty($this->requiredRoles)) {
            $request = $request->withAttribute("user_id", $_SESSION["user_id"]);
            $request = $request->withAttribute(
                "user_data",
                $_SESSION["user_data"] ?? null,
            );
            return $handler->handle($request);
        }

        $userRole = $_SESSION["user_data"]["rol_nombre"] ?? null;

        if (!$userRole || !in_array($userRole, $this->requiredRoles)) {
            $body = json_encode([
                "success" => false,
                "message" => "Insufficient permissions",
            ]);

            $response = $response->withHeader(
                "Content-Type",
                "application/json",
            );
            $response->getBody()->write($body);

            return $response->withStatus(403);
        }

        $request = $request->withAttribute("user_id", $_SESSION["user_id"]);
        $request = $request->withAttribute(
            "user_data",
            $_SESSION["user_data"] ?? null,
        );

        return $handler->handle($request);
    }
}
