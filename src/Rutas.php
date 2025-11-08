<?php

namespace Cineplanet\App;

use Cineplanet\App\Controllers\InicioController;
use Slim\App;

class Rutas
{
    public static function registrarRutas(App $app)
    {
        $inicioController = new InicioController();

        $app->get("/", [$inicioController, 'index']);
        $app->get("/peliculas", [$inicioController, 'peliculas']);
    }
}