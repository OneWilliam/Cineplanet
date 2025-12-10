<?php

namespace Cineplanet\App\Controllers\Admin;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PDO;

class PeliculaController
{
    /*
        CREATE TABLE pelicula (
            id_pelicula INT AUTO_INCREMENT,
            nombre VARCHAR(20),
            duracion INT NOT NULL,
            PRIMARY KEY (id_pelicula)
        );
    */

    /**
     * Obtener todas las peliculas.
     *
     * @return array Lista de peliculas.
     */
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
     * Obtener todas las películas.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function getAll(Request $request, Response $response)
    {
        $table = strtolower(str_replace('Controller', '', (new \ReflectionClass($this))->getShortName()));
        $candidates = ["vista" . $table, "vista_" . $table];

        foreach ($candidates as $view) {
            try {
                $stmt = $this->pdo->query("SELECT * FROM `" . $view . "`");
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $response->getBody()->write(json_encode([
                    "success" => true,
                    "data" => $rows,
                ]));

                return $response->withHeader("Content-Type", "application/json");
            } catch (\PDOException $e) {
                // intentar siguiente candidato
            }
        }

        $response->getBody()->write(json_encode([
            "success" => false,
            "data" => [],
            "message" => "No view found for table: " . $table,
        ]));

        return $response->withHeader("Content-Type", "application/json")->withStatus(500);
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
     * Eliminar una película por ID.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function delete(Request $request, Response $response)
    {
        try {
            $data = json_decode($request->getBody(), true);
            
            if (!$data) {
                $response->getBody()->write(json_encode([
                    "success" => false,
                    "message" => "No data provided",
                ]));
                return $response->withHeader("Content-Type", "application/json")->withStatus(400);
            }

            // Obtener la primera clave como identificador (generalmente la clave primaria)
            $idKey = array_key_first($data);
            $idValue = $data[$idKey];

            if (!$idKey || !$idValue) {
                $response->getBody()->write(json_encode([
                    "success" => false,
                    "message" => "ID field not provided",
                ]));
                return $response->withHeader("Content-Type", "application/json")->withStatus(400);
            }

            // Ejecutar DELETE desde tabla pelicula
            $stmt = $this->pdo->prepare("DELETE FROM `pelicula` WHERE `" . $idKey . "` = :id");
            $stmt->bindParam(':id', $idValue);
            $stmt->execute();

            $response->getBody()->write(json_encode([
                "success" => true,
                "message" => "Record deleted successfully",
            ]));

            return $response->withHeader("Content-Type", "application/json")->withStatus(200);
        } catch (\PDOException $e) {
            $response->getBody()->write(json_encode([
                "success" => false,
                "message" => "DB error: " . $e->getMessage(),
            ]));
            return $response->withHeader("Content-Type", "application/json")->withStatus(500);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                "success" => false,
                "message" => "Error: " . $e->getMessage(),
            ]));
            return $response->withHeader("Content-Type", "application/json")->withStatus(500);
        }
    }
}