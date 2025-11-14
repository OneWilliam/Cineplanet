<?php
namespace Cineplanet\App;

use Psr\Http\Message\ResponseInterface as Response;

class View
{
    public static function render(Response $response, $viewPath, $data = [])
    {
        $viewContent = self::loadView($viewPath, $data);

        $fullData = array_merge($data, ["content" => $viewContent]);
        $layoutContent = self::loadView("layout", $fullData);

        // Agregar encabezado de la ruta actual
        $currentPath = $_SERVER['REQUEST_URI'] ?? '/';
        $response = $response->withHeader('X-Current-Path', $currentPath);

        $response->getBody()->write($layoutContent);
        return $response;
    }

    public static function renderPartial(
        Response $response,
        $viewPath,
        $data = [],
    ) {
        $viewContent = self::loadView($viewPath, $data);
        
        // Agregar encabezado del CSS de página si está presente
        if (isset($data['page_css'])) {
            $response = $response->withHeader('X-Page-CSS', $data['page_css']);
        }
        
        // Agregar encabezado de la ruta actual
        $currentPath = $_SERVER['REQUEST_URI'] ?? '/';
        $response = $response->withHeader('X-Current-Path', $currentPath);
        
        $response->getBody()->write($viewContent);
        return $response;
    }

    private static function loadView($viewPath, $data = [])
    {
        $fullPath = __DIR__ . "/../views/" . $viewPath . ".php";

        if (!file_exists($fullPath)) {
            throw new \Exception("View file not found: " . $fullPath);
        }

        extract($data);
        ob_start();
        include $fullPath;
        return ob_get_clean();
    }
}
