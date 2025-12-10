<?php
use Cineplanet\App\Controllers\Admin\AdminController;
use Cineplanet\App\Controllers\Admin\AsientoController;
use Cineplanet\App\Controllers\Admin\BoletaController;
use Cineplanet\App\Controllers\Admin\CategoriaController;
use Cineplanet\App\Controllers\Admin\CineController;
use Cineplanet\App\Controllers\Admin\CineticketController;
use Cineplanet\App\Controllers\Admin\CiudadController;
use Cineplanet\App\Controllers\Admin\ClienteController;
use Cineplanet\App\Controllers\Admin\CompracineController;
use Cineplanet\App\Controllers\Admin\CompradulceriaController;
use Cineplanet\App\Controllers\Admin\DulceController;
use Cineplanet\App\Controllers\Admin\DulceriacategoriaController;
use Cineplanet\App\Controllers\Admin\DulceriaController;
use Cineplanet\App\Controllers\Admin\DulceriaticketController;
use Cineplanet\App\Controllers\Admin\EmpleadoController;
use Cineplanet\App\Controllers\Admin\EstadoController;
use Cineplanet\App\Controllers\Admin\FormatoController;
use Cineplanet\App\Controllers\Admin\FuncionController;
use Cineplanet\App\Controllers\Admin\HorarioController;
use Cineplanet\App\Controllers\Admin\PeliculaController;
use Cineplanet\App\Controllers\Admin\PeliculaformatoController;
use Cineplanet\App\Controllers\Admin\SalaController;
use Cineplanet\App\Controllers\Admin\UploadController;
use Cineplanet\App\Controllers\Public\MoviesController as PublicMovieController;

use Cineplanet\App\Middleware\RoleAuthMiddleware;

return function ($app, $pdo) {
    $uploadController = new UploadController();
    $peliculaController = new PeliculaController($pdo);
    $publicMovieController = new PublicMovieController($pdo);
    $ciudadController = new CiudadController($pdo);
    $cineController = new CineController($pdo);
    $adminController = new AdminController($pdo);
    $asientoController = new AsientoController($pdo);
    $boletaController = new BoletaController($pdo);
    $categoriaController = new CategoriaController($pdo);
    $cineticketController = new CineticketController($pdo);
    $clienteController = new ClienteController($pdo);
    $compracineController = new CompracineController($pdo);
    $compradulceriaController = new CompradulceriaController($pdo);
    $dulceController = new DulceController($pdo);
    $dulceriacategoriaController = new DulceriacategoriaController($pdo);
    $dulceriaController = new DulceriaController($pdo);
    $dulceriaticketController = new DulceriaticketController($pdo);
    $empleadoController = new EmpleadoController($pdo);
    $estadoController = new EstadoController($pdo);
    $formatoController = new FormatoController($pdo);
    $funcionController = new FuncionController($pdo);
    $horarioController = new HorarioController($pdo);
    $peliculaformatoController = new PeliculaformatoController($pdo);
    $salaController = new SalaController($pdo);

    $app->group("/admin", function ($group) use (
        $peliculaController,
        $uploadController,
        $publicMovieController,
        $ciudadController,
        $cineController,
        $adminController,
        $asientoController,
        $boletaController,
        $categoriaController,
        $cineticketController,
        $clienteController,
        $compracineController,
        $compradulceriaController,
        $dulceController,
        $dulceriacategoriaController,
        $dulceriaController,
        $dulceriaticketController,
        $empleadoController,
        $estadoController,
        $formatoController,
        $funcionController,
        $horarioController,
        $peliculaformatoController,
        $salaController
    ) {
        $group->post("/upload", [$uploadController, "uploadImage"]);
        $group->get("/uploads", [$uploadController, "getUploadedImages"]);
        
        $group->post("/movie", [$peliculaController, "createMovie"]);
        $group->get("/movie", [$publicMovieController, "getAll"]);
        
        $group->get("/admin", [$adminController, "getAll"]);
        $group->post("/admin", [$adminController, "create"]);
        $group->delete("/admin", [$adminController, "delete"]);

        $group->get("/cinema", [$cineController, "getAll"]);
        $group->post("/cinema", [$cineController, "create"]);
        $group->delete("/cinema", [$cineController, "delete"]);

        $group->get("/seat", [$asientoController, "getAll"]);
        $group->post("/seat", [$asientoController, "create"]);
        $group->delete("/seat", [$asientoController, "delete"]);

        $group->get("/receipt", [$boletaController, "getAll"]);
        $group->post("/receipt", [$boletaController, "create"]);
        $group->delete("/receipt", [$boletaController, "delete"]);

        $group->get("/category", [$categoriaController, "getAll"]);
        $group->post("/category", [$categoriaController, "create"]);
        $group->delete("/category", [$categoriaController, "delete"]);

        $group->get("/cinematicket", [$cineticketController, "getAll"]);
        $group->post("/cinematicket", [$cineticketController, "create"]);
        $group->delete("/cinematicket", [$cineticketController, "delete"]);

        $group->get("/city", [$ciudadController, "getAll"]);
        $group->post("/city", [$ciudadController, "create"]);
        $group->delete("/city", [$ciudadController, "delete"]);

        $group->get("/customer", [$clienteController, "getAll"]);
        $group->post("/customer", [$clienteController, "create"]);
        $group->delete("/customer", [$clienteController, "delete"]);

        $group->get("/cinemapurchase", [$compracineController, "getAll"]);
        $group->post("/cinemapurchase", [$compracineController, "create"]);
        $group->delete("/cinemapurchase", [$compracineController, "delete"]);

        $group->get("/shoppurchase", [$compradulceriaController, "getAll"]);
        $group->post("/shoppurchase", [$compradulceriaController, "create"]);
        $group->delete("/shoppurchase", [$compradulceriaController, "delete"]);

        $group->get("/snack", [$dulceController, "getAll"]);
        $group->post("/snack", [$dulceController, "create"]);
        $group->delete("/snack", [$dulceController, "delete"]);

        $group->get("/shopcategory", [$dulceriacategoriaController, "getAll"]);
        $group->post("/shopcategory", [$dulceriacategoriaController, "create"]);
        $group->delete("/shopcategory", [$dulceriacategoriaController, "delete"]);

        $group->get("/shop", [$dulceriaController, "getAll"]);
        $group->post("/shop", [$dulceriaController, "create"]);
        $group->delete("/shop", [$dulceriaController, "delete"]);

        $group->get("/shopticket", [$dulceriaticketController, "getAll"]);
        $group->post("/shopticket", [$dulceriaticketController, "create"]);
        $group->delete("/shopticket", [$dulceriaticketController, "delete"]);

        $group->get("/employee", [$empleadoController, "getAll"]);
        $group->post("/employee", [$empleadoController, "create"]);
        $group->delete("/employee", [$empleadoController, "delete"]);

        $group->get("/state", [$estadoController, "getAll"]);
        $group->post("/state", [$estadoController, "create"]);
        $group->delete("/state", [$estadoController, "delete"]);

        $group->get("/format", [$formatoController, "getAll"]);
        $group->post("/format", [$formatoController, "create"]);
        $group->delete("/format", [$formatoController, "delete"]);

        $group->get("/screening", [$funcionController, "getAll"]);
        $group->post("/screening", [$funcionController, "create"]);
        $group->delete("/screening", [$funcionController, "delete"]);

        $group->get("/schedule", [$horarioController, "getAll"]);
        $group->post("/schedule", [$horarioController, "create"]);
        $group->delete("/schedule", [$horarioController, "delete"]);

        $group->get("/movieformat", [$peliculaformatoController, "getAll"]);
        $group->post("/movieformat", [$peliculaformatoController, "create"]);
        $group->delete("/movieformat", [$peliculaformatoController, "delete"]);

        $group->get("/room", [$salaController, "getAll"]);
        $group->post("/room", [$salaController, "create"]);
        $group->delete("/room", [$salaController, "delete"]);

        $group->get("/adminlog", [$adminController, "getLogs"]);


        // Aquí puedes agregar más rutas de administración, por ejemplo:
        // $group->put('/movies/{id}', [$moviesController, 'update']);
        // $group->delete('/movies/{id}', [$moviesController, 'delete']);
        // $group->get('/movies', [$moviesController, 'getAll']);
        // $group->get('/movies/{id}', [$moviesController, 'getById']);

    })->add(new RoleAuthMiddleware(["admin"]));
};
