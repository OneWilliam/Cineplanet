<?php
// src/Controllers/MoviesController.php

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
            // Verificar si las columnas existen
            $columns = $this->pdo->query("SHOW COLUMNS FROM pelicula")->fetchAll(PDO::FETCH_COLUMN);
            $hasImagen = in_array('imagen', $columns);
            $hasDescripcion = in_array('descripcion', $columns);
            $hasClasificacion = in_array('clasificacion', $columns);

            $selectImagen = $hasImagen ? "COALESCE(imagen, 'default.jpg') as imagen" : "'default.jpg' as imagen";
            $selectDescripcion = $hasDescripcion ? "COALESCE(descripcion, '') as descripcion" : "'' as descripcion";
            $selectClasificacion = $hasClasificacion ? "COALESCE(clasificacion, 'ATP') as clasificacion" : "'ATP' as clasificacion";

            $stmt = $this->pdo->query("
                SELECT 
                    id_pelicula as id,
                    nombre as title,
                    duracion as duration,
                    $selectImagen,
                    $selectDescripcion,
                    $selectClasificacion
                FROM pelicula
                ORDER BY nombre
            ");
            
            $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $response->getBody()->write(json_encode([
                'success' => true,
                'data' => $movies
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json');
                
        } catch (\PDOException $e) {
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
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Error al obtener película: ' . $e->getMessage()
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }
}
