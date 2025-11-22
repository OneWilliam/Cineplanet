<?php

namespace Cineplanet\App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Cineplanet\App\Repository\MovieRepository;

class AdminController
{
    private MovieRepository $movieRepository;

    public function __construct()
    {
        $this->movieRepository = new MovieRepository();
    }

    public function dashboard(Request $request, Response $response, $args)
    {
        // The authentication is now handled by middleware
        // Obtener estadísticas del sistema
        try {
            // Total películas
            $total_peliculas = $this->movieRepository->getTotalCount();

            // Total cines
            $stmt = \Cineplanet\App\Database::executeQuery("SELECT COUNT(*) as total FROM cine");
            $total_cines = $stmt[0]['total'] ?? 0;

            // Total usuarios
            $stmt = \Cineplanet\App\Database::executeQuery("SELECT COUNT(*) as total FROM usuarios");
            $total_usuarios = $stmt[0]['total'] ?? 0;

            // Total funciones
            $stmt = \Cineplanet\App\Database::executeQuery("SELECT COUNT(*) as total FROM funcion");
            $total_funciones = $stmt[0]['total'] ?? 0;

        } catch (\Exception $e) {
            error_log("Dashboard error: " . $e->getMessage());
            $total_peliculas = 0;
            $total_cines = 0;
            $total_usuarios = 0;
            $total_funciones = 0;
        }

        $data = [
            "total_peliculas" => $total_peliculas,
            "total_cines" => $total_cines,
            "total_usuarios" => $total_usuarios,
            "total_funciones" => $total_funciones,
        ];

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function peliculas(Request $request, Response $response, $args)
    {
        // The authentication is now handled by middleware
        try {
            $peliculas = $this->movieRepository->getAllMovies();
        } catch (\Exception $e) {
            error_log("Error loading admin movies: " . $e->getMessage());
            $peliculas = [];
        }

        $response->getBody()->write(json_encode($peliculas));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function cines(Request $request, Response $response, $args)
    {
        // The authentication is now handled by middleware
        try {
            $cines = \Cineplanet\App\Database::executeQuery("
                SELECT c.id_cine, c.nombre, ci.nombre as ciudad
                FROM cine c
                JOIN ciudad ci ON c.id_ciudad = ci.id_ciudad
            ");
        } catch (\Exception $e) {
            error_log("Error loading admin cines: " . $e->getMessage());
            $cines = [];
        }

        $response->getBody()->write(json_encode($cines));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function usuarios(Request $request, Response $response, $args)
    {
        // The authentication is now handled by middleware
        try {
            $usuarios = \Cineplanet\App\Database::executeProcedure("listarUsuarios");
        } catch (\Exception $e) {
            error_log("Error loading admin usuarios: " . $e->getMessage());
            $usuarios = [];
        }

        $response->getBody()->write(json_encode($usuarios));
        return $response->withHeader('Content-Type', 'application/json');
    }
}