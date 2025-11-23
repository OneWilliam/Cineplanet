<?php
use Cineplanet\App\Controllers\AuthController;
use Cineplanet\App\Middleware\RoleAuthMiddleware;

return function ($app, $pdo) {
    // Instanciar el controlador de autenticación
    $authController = new AuthController($pdo);

    // Aquí podrías agregar un controlador de perfil de usuario si lo tienes
    // use Cineplanet\App\Controllers\User\ProfileController;
    // $profileController = new ProfileController($pdo);

    $app->group('/user', function ($group) use ($authController) {
        $group->post('/logout', [$authController, 'logout']);
        // $group->get('/profile', [$profileController, 'getProfile']);
        // $group->post('/profile', [$profileController, 'updateProfile']);
        // Agrega aquí más rutas de usuario autenticado si es necesario
    })->add(new RoleAuthMiddleware([])); // Middleware para requerir autenticación
};
