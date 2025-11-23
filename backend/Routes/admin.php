<?php
use Cineplanet\App\Controllers\Admin\MoviesController;
use Cineplanet\App\Middleware\RoleAuthMiddleware;

return function ($app, $pdo) {
    $moviesController = new MoviesController($pdo);

    $app->group('/admin', function ($group) use ($moviesController) {
        // Rutas de administración de películas
        $group->post('/movies', [$moviesController, 'create']);
        // Aquí puedes agregar más rutas de administración, por ejemplo:
        // $group->put('/movies/{id}', [$moviesController, 'update']);
        // $group->delete('/movies/{id}', [$moviesController, 'delete']);
        // $group->get('/movies', [$moviesController, 'getAll']);
        // $group->get('/movies/{id}', [$moviesController, 'getById']);
    })->add(new RoleAuthMiddleware(['admin']));
};
