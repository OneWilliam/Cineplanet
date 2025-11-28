<?php
use Cineplanet\App\Controllers\Public\MoviesController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function ($app, $pdo) {
    $moviesController = new MoviesController($pdo);

    $app->group("", function ($group) use ($moviesController) {
        $group->get("/movies", [$moviesController, "getAll"]);
        $group->get("/movies/{id}", [$moviesController, "getById"]);
    });
};
