<?php

namespace Cineplanet\App;

use Cineplanet\App\Controllers\InicioController;
use Cineplanet\App\Controllers\AdminController;
use Slim\App;

class Rutas
{
    public static function registrarRutas(App $app)
    {
        $inicioController = new InicioController();
        $adminController = new AdminController();

        $app->get("/", [$inicioController, 'index']);
        $app->get("/peliculas", [$inicioController, 'peliculas']);
        $app->get("/peliculas/{id}", [$inicioController, 'peliculaDetalle']);
        $app->get("/cuenta", [$inicioController, 'cuenta']);
        $app->get("/login", [$inicioController, 'mostrarLogin']);
        $app->post("/login", [$inicioController, 'procesarLogin']);
        $app->get("/register", [$inicioController, 'mostrarRegister']);
        $app->post("/register", [$inicioController, 'procesarRegister']);
        $app->post("/logout", [$inicioController, 'procesarLogout']);

        // Rutas de administración - requieren autenticación de admin
        $app->get("/admin", [$adminController, 'dashboard']);
        $app->get("/admin/peliculas", [$adminController, 'peliculas']);
        $app->get("/admin/cines", [$adminController, 'cines']);
        $app->get("/admin/usuarios", [$adminController, 'usuarios']);
    }
}