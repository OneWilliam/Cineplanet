<?php
use Cineplanet\App\Controllers\AuthController;
use Cineplanet\App\Controllers\Public\MoviesController;
use Cineplanet\App\Controllers\UploadController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function ($app, $pdo) {
    // Instanciar controladores públicos
    $authController = new AuthController($pdo);
    $moviesController = new MoviesController($pdo);
    $uploadController = new UploadController();

    // Grupo de rutas públicas
    $app->group("", function ($group) use ($authController, $moviesController, $uploadController) {
        // Películas públicas
        $group->get("/movies", [$moviesController, "getAll"]);
        $group->get("/movies/{id}", [$moviesController, "getById"]);

        // Subida de archivos
        $group->post("/upload", [$uploadController, "uploadImage"]);
        $group->get("/uploads", [$uploadController, "getUploadedImages"]);

        // Chequeo de sesión
        $group->get("/session", function (
            Request $request,
            Response $response,
        ) {
            if (isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])) {
                $response->getBody()->write(
                    json_encode([
                        "success" => true,
                        "authenticated" => true,
                        "user" => $_SESSION["user_data"] ?? null,
                    ]),
                );
            } else {
                $response->getBody()->write(
                    json_encode([
                        "success" => true,
                        "authenticated" => false,
                    ]),
                );
            }
            return $response->withHeader("Content-Type", "application/json");
        });

        // Aquí puedes agregar más rutas públicas (funciones, salas, etc.)
    });
};
