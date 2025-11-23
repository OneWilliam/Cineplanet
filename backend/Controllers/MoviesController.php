<?php
// backend/Controllers/MoviesController.php

namespace Cineplanet\App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PDO;

class MoviesController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(Request $request, Response $response)
    {
        try {
            $stmt = $this->pdo->query("
                SELECT
                    id_pelicula as id,
                    nombre as title,
                    duracion as duration
                FROM pelicula
                ORDER BY nombre
            ");

            $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Clean any previous output
            if (ob_get_level()) {
                ob_clean();
            }

            $response->getBody()->write(json_encode([
                'success' => true,
                'data' => $movies
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withHeader('Cache-Control', 'no-cache');

        } catch (\PDOException $e) {
            // Clean any previous output
            if (ob_get_level()) {
                ob_clean();
            }

            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Error al obtener películas: ' . $e->getMessage()
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }

    public function getById(Request $request, Response $response, array $args)
    {
        $id = $args['id'];

        try {
            $stmt = $this->pdo->prepare("
                SELECT
                    id_pelicula as id,
                    nombre as title,
                    duracion as duration
                FROM pelicula
                WHERE id_pelicula = :id
            ");

            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $movie = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$movie) {
                $response->getBody()->write(json_encode([
                    'success' => false,
                    'message' => 'Película no encontrada'
                ]));

                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(404);
            }

            $response->getBody()->write(json_encode([
                'success' => true,
                'data' => $movie
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json');

        } catch (\PDOException $e) {
            // Clean any previous output
            if (ob_get_level()) {
                ob_clean();
            }

            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Error al obtener película: ' . $e->getMessage()
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }

    public function create(Request $request, Response $response)
    {
        // Get user data from request attributes (set by middleware)
        $user_data = $request->getAttribute('user_data');

        if (!$user_data) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Authentication required to create movies'
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        }

        // Check if user has admin role (this is now handled by middleware)
        $userRole = $user_data['rol_nombre'] ?? null;
        if ($userRole !== 'admin') {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Insufficient permissions to create movies'
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(403);
        }

        $data = json_decode($request->getBody(), true);

        $title = $data['title'] ?? '';
        $duration = $data['duration'] ?? '';

        if (empty($title) || empty($duration)) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Título y duración son obligatorios'
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }

        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO pelicula (nombre, duracion)
                VALUES (:title, :duration)
            ");
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':duration', $duration);

            $stmt->execute();

            $response->getBody()->write(json_encode([
                'success' => true,
                'message' => 'Película creada exitosamente'
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(201);

        } catch (\PDOException $e) {
            // Clean any previous output
            if (ob_get_level()) {
                ob_clean();
            }

            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Error al crear película: ' . $e->getMessage()
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }
}
