<?php

namespace Cineplanet\App\Routes;

use Cineplanet\App\Controllers\InicioController;
use Slim\App;

class PublicRoutes
{
    public static function register(App $app): void
    {
        // Eliminar rutas no-API ya que se manejan por el frontend SPA
        // Mantener solo las rutas POST para procesamiento de formularios
        $app->post("/login", InicioController::class . ':procesarLogin');
        $app->post("/register", InicioController::class . ':procesarRegister');
        $app->post("/logout", InicioController::class . ':procesarLogout');
    }
}