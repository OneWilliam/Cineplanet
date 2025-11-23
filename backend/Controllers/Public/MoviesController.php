<?php
// Controlador público de películas
// Documentación Slim Controllers: https://www.slimframework.com/docs/v4/objects/controller.html

// Documentación namespaces en PHP: https://www.php.net/manual/es/language.namespaces.php
namespace Cineplanet\App\Controllers\Public;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PDO;

class MoviesController
{
    private $pdo;

    /**
     * Constructor del controlador de películas públicas.
     * @param PDO $pdo Conexión PDO a la base de datos.
     * @see https://www.php.net/manual/es/class.pdo.php
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
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

    /**
     * Obtener una película por ID (público).
     * @param Request $request PSR-7 Request
     * @param Response $response PSR-7 Response
     * @param array $args Argumentos de ruta (debe incluir 'id')
     * @return Response
     * @see https://www.slimframework.com/docs/v4/objects/router.html#route-placeholders
     * @see https://www.php.net/manual/es/pdo.prepare.php
     * @see https://www.php.net/manual/es/pdostatement.bindparam.php
     * @see https://www.php.net/manual/es/function.json-encode.php
     */
    public function getById(Request $request, Response $response, array $args)
    {
        $id = $args["id"];

        try {
            // Consulta la película por ID
            $stmt = $this->pdo->prepare("
                SELECT
                    id_pelicula as id,
                    nombre as title,
                    duracion as duration
                FROM pelicula
                WHERE id_pelicula = :id
            ");

            $stmt->bindParam(":id", $id);
            $stmt->execute();

            $movie = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$movie) {
                $response->getBody()->write(
                    json_encode([
                        "success" => false,
                        "message" => "Película no encontrada",
                    ]),
                );

                return $response
                    ->withHeader("Content-Type", "application/json")
                    ->withStatus(404);
            }

            $response->getBody()->write(
                json_encode([
                    "success" => true,
                    "data" => $movie,
                ]),
            );

            return $response->withHeader("Content-Type", "application/json");
        } catch (\PDOException $e) {
            // Limpiar cualquier salida previa
            if (ob_get_level()) {
                ob_clean();
            }

            $response->getBody()->write(
                json_encode([
                    "success" => false,
                    "message" =>
                        "Error al obtener película: " . $e->getMessage(),
                ]),
            );

            return $response
                ->withHeader("Content-Type", "application/json")
                ->withStatus(500);
        }
    }
}
