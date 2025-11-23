<?php
use Cineplanet\App\Controllers\AuthController;

return function ($app, $pdo) {
    $authController = new AuthController($pdo);

    // Rutas de autenticación
    $app->post("/login", [$authController, "login"]);
    $app->post("/register", [$authController, "register"]);
    $app->get("/me", [$authController, "checkSession"]);
};
