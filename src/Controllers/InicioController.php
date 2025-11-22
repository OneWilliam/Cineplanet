<?php

namespace Cineplanet\App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Cineplanet\App\View;
use Cineplanet\App\Repository\MovieRepository;
use Cineplanet\App\Service\ImageService;

class InicioController
{
    private MovieRepository $movieRepository;
    private ImageService $imageService;
    private \Cineplanet\App\Service\UserService $userService;

    public function __construct()
    {
        $this->movieRepository = new MovieRepository();
        $this->imageService = new ImageService();
        $this->userService = new \Cineplanet\App\Service\UserService();
    }

    public function index(Request $request, Response $response, $args)
    {
        try {
            $peliculas = $this->movieRepository->getAllMovies();
            // Process images for each movie
            $peliculas = $this->imageService->getMovieImages($peliculas);
        } catch (\Exception $e) {
            // En caso de error devolvemos un arreglo vacío para no romper la vista
            error_log("Error loading movies: " . $e->getMessage());
            $peliculas = [];
        }

        // Devolver solo los datos necesarios para el frontend
        $response->getBody()->write(json_encode($peliculas));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function peliculas(Request $request, Response $response, $args)
    {
        try {
            $peliculas = $this->movieRepository->getAllMovies();
            $peliculas = $this->imageService->getMovieImages($peliculas);

            $response->getBody()->write(json_encode($peliculas));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            error_log("Error loading peliculas: " . $e->getMessage());

            $error = [
                'error' => 'Error al cargar las películas',
                'message' => $e->getMessage()
            ];

            $response->getBody()->write(json_encode($error));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function peliculaDetalle(Request $request, Response $response, $args)
    {
        $id = $args['id'] ?? null;

        if (!$id) {
            $error = [
                'error' => 'Película no encontrada',
                'message' => 'ID de la película no proporcionado'
            ];
            $response->getBody()->write(json_encode($error));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        try {
            $pelicula = $this->movieRepository->getMovieById((int)$id);

            if (!$pelicula) {
                $error = [
                    'error' => 'Película no encontrada',
                    'message' => 'La película solicitada no existe'
                ];
                $response->getBody()->write(json_encode($error));
                return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
            }

            $response->getBody()->write(json_encode($pelicula));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            error_log("Error loading pelicula detail: " . $e->getMessage());

            $error = [
                'error' => 'Error Interno',
                'message' => 'Ocurrió un error al cargar la película'
            ];
            $response->getBody()->write(json_encode($error));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function cuenta(Request $request, Response $response, $args)
    {
        // En lugar de renderizar una vista, devolvemos datos de la cuenta del usuario
        $userData = [
            'nombre' => $_SESSION['user_nombre'] ?? null,
            'email' => $_SESSION['user_email'] ?? null,
            'rol' => $_SESSION['user_rol'] ?? null,
        ];

        if (empty($userData['nombre'])) {
            $error = [
                'error' => 'Usuario no autenticado',
                'message' => 'Debe iniciar sesión para ver su cuenta'
            ];
            $response->getBody()->write(json_encode($error));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode($userData));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function mostrarLogin(Request $request, Response $response, $args)
    {
        // Devolver información sobre el formulario de login
        $data = [
            'form_type' => 'login',
            'fields' => ['email', 'password'],
            'action' => '/api/login',
            'method' => 'POST'
        ];

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function procesarLogin(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        // Validación básica
        if (empty($email) || empty($password)) {
            return $response->withHeader('Location', '/cuenta')->withStatus(302);
        }

        try {
            $user = $this->userService->authenticateUser($email, $password);

            if ($user) {
                // Usuario autenticado correctamente
                $_SESSION['user_id'] = $user['id_usuario'];
                $_SESSION['user_nombre'] = $user['nombre'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_rol'] = $user['rol_nombre'];

                // Redirigir según el rol del usuario
                if ($user['rol_nombre'] === 'admin') {
                    return $response->withHeader('Location', '/admin')->withStatus(302);
                } else {
                    return $response->withHeader('Location', '/')->withStatus(302);
                }
            } else {
                // Credenciales incorrectas
                return $response->withHeader('Location', '/cuenta')->withStatus(302);
            }
        } catch (\Exception $e) {
            error_log("Login error: " . $e->getMessage());
            return $response->withHeader('Location', '/cuenta')->withStatus(302);
        }
    }

    public function mostrarRegister(Request $request, Response $response, $args)
    {
        // Devolver información sobre el formulario de registro
        $data = [
            'form_type' => 'register',
            'fields' => ['nombre', 'apellido', 'email', 'password', 'confirm_password'],
            'action' => '/api/register',
            'method' => 'POST'
        ];

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function procesarRegister(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $nombre = $data['nombre'] ?? '';
        $apellido = $data['apellido'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $confirm_password = $data['confirm_password'] ?? '';

        // Validación básica
        if (empty($nombre) || empty($email) || empty($password) || empty($confirm_password)) {
            return $response->withHeader('Location', '/register')->withStatus(302);
        }

        if ($password !== $confirm_password) {
            // Las contraseñas no coinciden
            return $response->withHeader('Location', '/register')->withStatus(302);
        }

        try {
            if ($this->userService->registerUser($nombre, $apellido, $email, $password)) {
                // User registered successfully, now authenticate them
                $user = $this->userService->authenticateUser($email, $password);

                if ($user) {
                    // Iniciar sesión para el nuevo usuario
                    $_SESSION['user_id'] = $user['id_usuario'];
                    $_SESSION['user_nombre'] = $user['nombre'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_rol'] = $user['rol_nombre'];

                    // Redirigir a la página principal
                    return $response->withHeader('Location', '/')->withStatus(302);
                }
            }

            // Registration failed (email already exists or other error)
            return $response->withHeader('Location', '/register')->withStatus(302);
        } catch (\Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            return $response->withHeader('Location', '/register')->withStatus(302);
        }
    }

    public function procesarLogout(Request $request, Response $response, $args)
    {
        // Destruir todas las variables de sesión
        $_SESSION = array();

        // Si se desea destruir la sesión completamente, también se debe borrar la cookie de sesión
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Finalmente, destruir la sesión
        session_destroy();

        // Redirigimos a la página principal
        return $response->withHeader('Location', '/')->withStatus(302);
    }

    // API Methods that return JSON
    public function homeFragment(Request $request, Response $response, $args): Response
    {
        $peliculas = $this->movieRepository->getAllMovies();
        $peliculas = $this->imageService->getMovieImages($peliculas);

        $response->getBody()->write(json_encode($peliculas));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function moviesFragment(Request $request, Response $response, $args): Response
    {
        $peliculas = $this->movieRepository->getAllMovies();
        $peliculas = $this->imageService->getMovieImages($peliculas);

        $response->getBody()->write(json_encode($peliculas));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function movieDetailFragment(Request $request, Response $response, $args): Response
    {
        $id = $args['id'] ?? null;
        $pelicula = $this->movieRepository->getMovieById((int)$id);

        if (!$pelicula) {
            $response->getBody()->write(json_encode(['error' => 'Película no encontrada']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode($pelicula));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function loginFragment(Request $request, Response $response, $args): Response
    {
        // Devolver información básica sobre el formulario de login
        $data = [
            'form_type' => 'login',
            'message' => 'Formulario de inicio de sesión'
        ];

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function registerFragment(Request $request, Response $response, $args): Response
    {
        // Devolver información básica sobre el formulario de registro
        $data = [
            'form_type' => 'register',
            'message' => 'Formulario de registro'
        ];

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // API Methods
    public function apiLogin(Request $request, Response $response, $args): Response
    {
        $data = $request->getParsedBody();
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        if (empty($email) || empty($password)) {
            $html = '<div class="error-message">Email y contraseña son requeridos</div>';
            $response->getBody()->write($html);
            return $response->withStatus(400);
        }

        $user = $this->userService->authenticateUser($email, $password);

        if ($user) {
            $_SESSION['user_id'] = $user['id_usuario'];
            $_SESSION['user_nombre'] = $user['nombre'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_rol'] = $user['rol_nombre'];

            // Return success message that updates the UI
            $html = '
            <div class="success-message">Inicio de sesión exitoso</div>
            <script>
                // Update Alpine store to reflect logged in state
                if (Alpine.store) {
                    const appStore = Alpine.store("app");
                    appStore.user.isLoggedIn = true;
                    appStore.user.name = "' . addslashes($user['nombre']) . '";
                    appStore.user.role = "' . addslashes($user['rol_nombre']) . '";
                }
                // Navigate to home after login
                htmx.ajax("GET", "/api/home", {target: "#main-content", swap: "innerHTML"});
            </script>';
            $response->getBody()->write($html);
            return $response;
        } else {
            $html = '<div class="error-message">Credenciales incorrectas</div>';
            $response->getBody()->write($html);
            return $response->withStatus(401);
        }
    }

    public function apiRegister(Request $request, Response $response, $args): Response
    {
        $data = $request->getParsedBody();
        $nombre = $data['nombre'] ?? '';
        $apellido = $data['apellido'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $confirm_password = $data['confirm_password'] ?? '';

        if (empty($nombre) || empty($apellido) || empty($email) || empty($password) || empty($confirm_password)) {
            $html = '<div class="error-message">Todos los campos son requeridos</div>';
            $response->getBody()->write($html);
            return $response->withStatus(400);
        }

        if ($password !== $confirm_password) {
            $html = '<div class="error-message">Las contraseñas no coinciden</div>';
            $response->getBody()->write($html);
            return $response->withStatus(400);
        }

        if ($this->userService->registerUser($nombre, $apellido, $email, $password)) {
            // Auto-login after successful registration
            $user = $this->userService->authenticateUser($email, $password);

            if ($user) {
                $_SESSION['user_id'] = $user['id_usuario'];
                $_SESSION['user_nombre'] = $user['nombre'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_rol'] = $user['rol_nombre'];

                $html = '
                <div class="success-message">Registro exitoso</div>
                <script>
                    // Update Alpine store to reflect logged in state
                    if (Alpine.store) {
                        const appStore = Alpine.store("app");
                        appStore.user.isLoggedIn = true;
                        appStore.user.name = "' . addslashes($user['nombre']) . '";
                        appStore.user.role = "' . addslashes($user['rol_nombre']) . '";
                    }
                    // Navigate to home after registration
                    htmx.ajax("GET", "/api/home", {target: "#main-content", swap: "innerHTML"});
                </script>';
                $response->getBody()->write($html);
                return $response;
            }
        }

        $html = '<div class="error-message">Error en el registro. El email puede estar en uso.</div>';
        $response->getBody()->write($html);
        return $response->withStatus(400);
    }

    public function apiLogout(Request $request, Response $response, $args): Response
    {
        // Destruir todas las variables de sesión
        $_SESSION = array();

        // Si se desea destruir la sesión completamente, también se debe borrar la cookie de sesión
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Finalmente, destruir la sesión
        session_destroy();

        // Return script to update Alpine store and refresh UI
        $html = '
        <script>
            // Update Alpine store to reflect logged out state
            if (Alpine.store) {
                const appStore = Alpine.store("app");
                appStore.user.isLoggedIn = false;
                appStore.user.name = "";
                appStore.user.role = "";
            }
            // Navigate to home after logout
            htmx.ajax("GET", "/api/home", {target: "#main-content", swap: "innerHTML"});
        </script>';
        $response->getBody()->write($html);
        return $response;
    }

    public function accountFragment(Request $request, Response $response, $args): Response
    {
        $userData = [
            'nombre' => $_SESSION['user_nombre'] ?? null,
            'email' => $_SESSION['user_email'] ?? null,
            'rol' => $_SESSION['user_rol'] ?? null,
        ];

        // Solo devolver datos si el usuario está autenticado
        if (empty($userData['nombre'])) {
            $response->getBody()->write(json_encode(['error' => 'Usuario no autenticado']));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode($userData));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function searchFragment(Request $request, Response $response, $args): Response
    {
        $queryParams = $request->getQueryParams();
        $searchQuery = $queryParams['searchQuery'] ?? $queryParams['q'] ?? '';

        if (empty($searchQuery)) {
            return $this->moviesFragment($request, $response, $args);
        }

        $peliculas = $this->movieRepository->searchMovies($searchQuery);
        $peliculas = $this->imageService->getMovieImages($peliculas);

        $data = [
            'peliculas' => $peliculas,
            'query' => $searchQuery
        ];

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function scheduleFragment(Request $request, Response $response, $args): Response
    {
        // Devolver datos para la cartelera (horarios, películas programadas, etc.)
        $data = [
            'message' => 'Datos de la cartelera'
        ];

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function concessionsFragment(Request $request, Response $response, $args): Response
    {
        // Devolver datos para la dulcería (productos, precios, etc.)
        $data = [
            'message' => 'Datos de la dulcería'
        ];

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
