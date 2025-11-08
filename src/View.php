<?php
namespace Cineplanet\App;

use Psr\Http\Message\ResponseInterface as Response;

class View {
    public static function render(Response $response, $viewPath, $data = []) {
        extract($data);
        $fullPath = __DIR__ . "/../views/" . $viewPath . ".php";
        
        if (!file_exists($fullPath)) {
            throw new \Exception("View file not found: " . $fullPath);
        }
        
        ob_start();
        include $fullPath;
        $html = ob_get_clean();
        
        $response->getBody()->write($html);
        return $response;
    }
}
