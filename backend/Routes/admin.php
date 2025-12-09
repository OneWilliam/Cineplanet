<?php
use Cineplanet\App\Controllers\Admin\MoviesController;
use Cineplanet\App\Controllers\Admin\UploadController;
use Cineplanet\App\Middleware\RoleAuthMiddleware;

return function ($app, $pdo) {
    $uploadController = new UploadController();
    $moviesController = new MoviesController($pdo);

    $app->group("/admin", function ($group) use (
        $moviesController,
        $uploadController,
    ) {
        $group->post("/upload", [$uploadController, "uploadImage"]);
        $group->get("/uploads", [$uploadController, "getUploadedImages"]);
        
        $group->post("/movies", [$moviesController, "create"]);
        $group->get("/movies", [$moviesController, "getAll"]);
        $group->delete('/movies/{id}', [$moviesController, 'delete']);
        
        // Aquí puedes agregar más rutas de administración, por ejemplo:
        // $group->put('/movies/{id}', [$moviesController, 'update']);
        // $group->delete('/movies/{id}', [$moviesController, 'delete']);
        // $group->get('/movies', [$moviesController, 'getAll']);
        // $group->get('/movies/{id}', [$moviesController, 'getById']);
    })->add(new RoleAuthMiddleware(["admin"]));
};
