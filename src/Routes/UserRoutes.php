<?php

namespace Cineplanet\App\Routes;

use Cineplanet\App\Controllers\InicioController;
use Cineplanet\App\Middleware\UserAuthMiddleware;
use Slim\App;

class UserRoutes
{
    public static function register(App $app): void
    {
        $app->get("/cuenta", InicioController::class . ':cuenta')->add(UserAuthMiddleware::class);
    }
}