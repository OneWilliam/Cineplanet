<?php
// Controlador de películas para administración

namespace Cineplanet\App\Controllers\Admin;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PDO;

class MoviesController
{
    private $pdo;

    /**
     * Constructor del controlador de películas para administración.
     * @param PDO $pdo Conexión PDO a la base de datos.
     * @see https://www.php.net/manual/es/class.pdo.php
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Crear una nueva película (solo admin).
     *
     * @see https://www.php.net/manual/es/function.json-decode.php
     * @see https://www.php.net/manual/es/function.empty.php
     * @see https://www.php.net/manual/es/pdo.prepare.php
     * @see https://www.php.net/manual/es/pdostatement.bindparam.php
     * @see https://www.php.net/manual/es/pdostatement.execute.php
     * @see https://www.php.net/manual/es/function.ob-get-level.php
     * @see https://www.php.net/manual/es/function.ob-clean.php
     * @see https://www.slimframework.com/docs/v4/cookbook/route-parameters.html
     * @see https://www.php-fig.org/psr/psr-7/
     *
     * @param Request $request PSR-7 Request
     * @param Response $response PSR-7 Response
     * @return Response
     */
    public function create(Request $request, Response $response)
    {
        // Obtener datos del usuario autenticado (asignados por el middleware)
        // @see https://www.slimframework.com/docs/v4/cookbook/middleware.html
        $user_data = $request->getAttribute("user_data");

        if (!$user_data) {
            $response->getBody()->write(
                json_encode([
                    "success" => false,
                    "message" => "Autenticación requerida para crear películas",
                ]),
            );

            return $response
                ->withHeader("Content-Type", "application/json")
                ->withStatus(401);
        }

        // Verificar si el usuario tiene rol de administrador (también manejado por el middleware)
        $userRole = $user_data["rol_nombre"] ?? null;
        if ($userRole !== "admin") {
            $response->getBody()->write(
                json_encode([
                    "success" => false,
                    "message" => "Permisos insuficientes para crear películas",
                ]),
            );

            return $response
                ->withHeader("Content-Type", "application/json")
                ->withStatus(403);
        }

        $data = json_decode($request->getBody(), true);

        $title = $data["title"] ?? "";
        $duration = $data["duration"] ?? "";

        if (empty($title) || empty($duration)) {
            $response->getBody()->write(
                json_encode([
                    "success" => false,
                    "message" => "Título y duración son obligatorios",
                ]),
            );
            return $response
                ->withHeader("Content-Type", "application/json")
                ->withStatus(400);
        }

        try {
            // Insertar la nueva película en la base de datos
            // @see https://www.php.net/manual/es/pdo.prepare.php
            $stmt = $this->pdo->prepare("
                INSERT INTO pelicula (nombre, duracion)
                VALUES (:title, :duration)
            ");
            // @see https://www.php.net/manual/es/pdostatement.bindparam.php
            $stmt->bindParam(":title", $title);
            $stmt->bindParam(":duration", $duration);

            // @see https://www.php.net/manual/es/pdostatement.execute.php
            $stmt->execute();

            $response->getBody()->write(
                json_encode([
                    "success" => true,
                    "message" => "Película creada exitosamente",
                ]),
            );

            return $response
                ->withHeader("Content-Type", "application/json")
                ->withStatus(201);
        } catch (\PDOException $e) {
            // Limpiar cualquier salida previa
            // @see https://www.php.net/manual/es/function.ob-get-level.php
            // @see https://www.php.net/manual/es/function.ob-clean.php
            if (ob_get_level()) {
                ob_clean();
            }

            $response->getBody()->write(
                json_encode([
                    "success" => false,
                    "message" => "Error al crear película: " . $e->getMessage(),
                ]),
            );

            return $response
                ->withHeader("Content-Type", "application/json")
                ->withStatus(500);
        }
    }

    /**
     * Obtener todas las películas públicas.
     * @param Request $request PSR-7 Request
     * @param Response $response PSR-7 Response
     * @return Response
     * @see https://www.slimframework.com/docs/v4/objects/router.html#get
     * @see https://www.php.net/manual/es/pdo.query.php
     * @see https://www.php.net/manual/es/function.ob-get-level.php
     * @see https://www.php.net/manual/es/function.ob-clean.php
     * @see https://www.php.net/manual/es/function.json-encode.php
     */
    public function getAll(Request $request, Response $response)
    {
        try {
            // Consulta todas las películas
            $stmt = $this->pdo->query("
                SELECT
                    id_pelicula as id,
                    nombre as title,
                    duracion as duration
                FROM pelicula
                ORDER BY nombre
            ");

            $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Limpiar cualquier salida previa
            if (ob_get_level()) {
                ob_clean();
            }

            $response->getBody()->write(
                json_encode([
                    "success" => true,
                    "data" => $movies,
                ]),
            );

            return $response
                ->withHeader("Content-Type", "application/json")
                ->withHeader("Cache-Control", "no-cache");
        } catch (\PDOException $e) {
            // Limpiar cualquier salida previa
            if (ob_get_level()) {
                ob_clean();
            }

            $response->getBody()->write(
                json_encode([
                    "success" => false,
                    "message" =>
                        "Error al obtener películas: " . $e->getMessage(),
                ]),
            );

            return $response
                ->withHeader("Content-Type", "application/json")
                ->withStatus(500);
        }
    }
}
