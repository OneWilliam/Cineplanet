<?php
namespace Cineplanet\App;

class View
{
    public static function render($response, $view, $params = [])
    {
        $content = self::renderView($view, $params);
        $response->getBody()->write($content);
        return $response;
    }

    private static function renderView($view, $params)
    {
        $file = __DIR__ . "/../views/" . $view . ".php";
        ob_start();
        extract($params);
        include $file;
        return ob_get_clean();
    }
}
