<?php

namespace Cineplanet\App\Routes;

use Cineplanet\App\Controllers\InicioController;
use Cineplanet\App\Middleware\UserAuthMiddleware;
use Slim\App;

class ApiRoutes
{
    public static function register(App $app): void
    {
        // Public API routes
        $app->get("/api/home", InicioController::class . ':homeFragment');
        $app->get("/api/movies", InicioController::class . ':moviesFragment');
        $app->get("/api/movie/{id}", InicioController::class . ':movieDetailFragment');
        $app->get("/api/schedule", InicioController::class . ':scheduleFragment');
        $app->get("/api/concessions", InicioController::class . ':concessionsFragment');
        $app->get("/api/search", InicioController::class . ':searchFragment');
        $app->get("/api/login", InicioController::class . ':loginFragment');
        $app->get("/api/register", InicioController::class . ':registerFragment');
        
        // Authenticated API routes
        $app->get("/api/account", InicioController::class . ':accountFragment')->add(UserAuthMiddleware::class);
        
        // Authentication API routes
        $app->post("/api/login", InicioController::class . ':apiLogin');
        $app->post("/api/register", InicioController::class . ':apiRegister');
        $app->post("/api/logout", InicioController::class . ':apiLogout');
    }
}