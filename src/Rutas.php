<?php

namespace Cineplanet\App;

use Cineplanet\App\Routes\PublicRoutes;
use Cineplanet\App\Routes\UserRoutes;
use Cineplanet\App\Routes\AdminRoutes;
use Cineplanet\App\Routes\ApiRoutes;
use Slim\App;

class Rutas
{
    public static function registrarRutas(App $app): void
    {
        PublicRoutes::register($app);
        UserRoutes::register($app);
        AdminRoutes::register($app);
        ApiRoutes::register($app);
    }
}