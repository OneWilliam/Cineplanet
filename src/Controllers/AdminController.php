<?php

namespace Cineplanet\App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Cineplanet\App\View;
use Cineplanet\App\Database;

class AdminController
{
    public function dashboard(Request $request, Response $response, $args)
    {
        // Verificar que el usuario tenga rol de admin
        if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'admin') {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        // Obtener estadísticas del sistema
        try {
            $db = Database::getConnection();
            
            // Total películas
            $stmt = $db->query("SELECT COUNT(*) as total FROM pelicula");
            $total_peliculas = $stmt->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0;
            
            // Total cines
            $stmt = $db->query("SELECT COUNT(*) as total FROM cine");
            $total_cines = $stmt->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0;
            
            // Total usuarios
            $stmt = $db->query("SELECT COUNT(*) as total FROM usuarios");
            $total_usuarios = $stmt->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0;
            
            // Total funciones
            $stmt = $db->query("SELECT COUNT(*) as total FROM funcion");
            $total_funciones = $stmt->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0;
            
        } catch (\Exception $e) {
            $total_peliculas = 0;
            $total_cines = 0;
            $total_usuarios = 0;
            $total_funciones = 0;
        }

        return $this->renderAdminView($response, "admin/dashboard", [
            "title" => "Dashboard - Admin Cineplanet",
            "page_css" => "/css/admin.css",
            "current_section" => "dashboard",
            "total_peliculas" => $total_peliculas,
            "total_cines" => $total_cines,
            "total_usuarios" => $total_usuarios,
            "total_funciones" => $total_funciones,
        ]);
    }

    public function peliculas(Request $request, Response $response, $args)
    {
        // Verificar que el usuario tenga rol de admin
        if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'admin') {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        try {
            $db = Database::getConnection();

            // Obtener todas las películas
            $stmt = $db->prepare("CALL listarPeliculas()");
            $stmt->execute();
            $peliculas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        } catch (\Exception $e) {
            $peliculas = [];
        }

        return $this->renderAdminView($response, "admin/peliculas", [
            "title" => "Gestión de Películas - Admin Cineplanet",
            "page_css" => "/css/admin.css",
            "current_section" => "peliculas",
            "peliculas" => $peliculas,
        ]);
    }

    public function cines(Request $request, Response $response, $args)
    {
        // Verificar que el usuario tenga rol de admin
        if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'admin') {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        try {
            $db = Database::getConnection();

            // Obtener todos los cines con sus ciudades
            $stmt = $db->query("
                SELECT c.id_cine, c.nombre, ci.nombre as ciudad
                FROM cine c
                JOIN ciudad ci ON c.id_ciudad = ci.id_ciudad
            ");
            $cines = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        } catch (\Exception $e) {
            $cines = [];
        }

        return $this->renderAdminView($response, "admin/cines", [
            "title" => "Gestión de Cines - Admin Cineplanet",
            "page_css" => "/css/admin.css",
            "current_section" => "cines",
            "cines" => $cines,
        ]);
    }

    public function usuarios(Request $request, Response $response, $args)
    {
        // Verificar que el usuario tenga rol de admin
        if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'admin') {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        try {
            $db = Database::getConnection();
            
            // Obtener todos los usuarios
            $stmt = $db->prepare("CALL listarUsuarios()");
            $stmt->execute();
            $usuarios = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
        } catch (\Exception $e) {
            $usuarios = [];
        }

        return $this->renderAdminView($response, "admin/usuarios", [
            "title" => "Gestión de Usuarios - Admin Cineplanet",
            "page_css" => "/css/admin.css",
            "current_section" => "usuarios",
            "usuarios" => $usuarios,
        ]);
    }

    // Método auxiliar para renderizar vistas de admin
    private function renderAdminView($response, $viewPath, $data = [])
    {
        $viewContent = View::loadView($viewPath, $data);
        $fullData = array_merge($data, ["content" => $viewContent]);
        $layoutContent = View::loadView("admin/layout", $fullData);

        $response->getBody()->write($layoutContent);
        return $response;
    }
}