<?php

namespace Cineplanet\App\Routes;

use Cineplanet\App\Controllers\AdminController;
use Cineplanet\App\Middleware\AdminAuthMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

class AdminRoutes
{
    public static function register(App $app): void
    {
        $app->group('/admin', function (RouteCollectorProxy $group) {
            $group->get('', AdminController::class . ':dashboard');
            $group->get('/peliculas', AdminController::class . ':peliculas');
            $group->get('/cines', AdminController::class . ':cines');
            $group->get('/usuarios', AdminController::class . ':usuarios');
        })->add(AdminAuthMiddleware::class);
    }
}