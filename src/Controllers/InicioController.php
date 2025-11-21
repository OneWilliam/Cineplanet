<?php

namespace Cineplanet\App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Cineplanet\App\View;
use Cineplanet\App\Database;

class InicioController
{
    public function index(Request $request, Response $response, $args)
    {
        try {
            $db = Database::getConnection();

            // Obtener las películas desde la base de datos usando el procedure listado
            $stmt = $db->prepare("CALL listarPeliculas()");
            $stmt->execute();
            $peliculas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            // En caso de error devolvemos un arreglo vacío para no romper la vista
            $peliculas = [];
        }

        // Regla: cada subcarpeta en public/assets/peliculas representa una película (slug)
        $assetsDir = realpath(__DIR__ . "/../../public/assets/peliculas");
        $folderMap = [];
        if ($assetsDir && is_dir($assetsDir)) {
            $dirs = glob($assetsDir . "/*", GLOB_ONLYDIR);
            foreach ($dirs as $d) {
                $key = basename($d); // nombre de la carpeta (slug)
                $images = [];
                foreach (
                    glob($d . "/*.{jpg,jpeg,png,gif,webp}", GLOB_BRACE)
                    as $imgPath
                ) {
                    $images[] =
                        "/assets/peliculas/" . $key . "/" . basename($imgPath);
                }
                if (!empty($images)) {
                    $folderMap[$key] = $images;
                }
            }
        }

        // helper slugify (coincide con la convención de nombres de carpeta)
        $slugify = function ($text) {
            $text = mb_strtolower(trim($text), "UTF-8");
            $trans = @iconv("UTF-8", "ASCII//TRANSLIT", $text);
            if ($trans !== false) {
                $text = $trans;
            }
            $text = preg_replace("/[^a-z0-9]+/i", "-", $text);
            $text = trim($text, "-");
            return $text;
        };

        // Para cada película, adjuntar un array 'images' con rutas públicas encontradas (puede estar vacío)
        foreach ($peliculas as &$p) {
            $nombre = $p["nombre"] ?? "";
            $slug = $slugify($nombre);

            if (isset($folderMap[$slug])) {
                // Si existe una carpeta con el slug, usar todas las imágenes dentro
                $p["images"] = $folderMap[$slug];
            } else {
                // Si no hay carpeta, intentar encontrar archivos sueltos en /public/assets basados en slug/nombre
                $candidates = [
                    "/assets/" . $slug . ".jpg",
                    "/assets/" . $slug . ".png",
                    "/assets/" . $slug . ".jpeg",
                    "/assets/" . strtolower($nombre) . ".jpg",
                    "/assets/" .
                    str_replace(" ", "_", strtolower($nombre)) .
                    ".jpg",
                ];
                $found = [];
                foreach ($candidates as $c) {
                    $fs = realpath(__DIR__ . "/../../public" . $c);
                    if ($fs && file_exists($fs)) {
                        $found[] = $c;
                    }
                }
                $p["images"] = $found; // puede quedar vacío -> la vista usará fallback si es necesario
            }
        }
        unset($p);

        return View::render($response, "inicio", [
            "titulo" => "Inicio - Cineplanet",
            "peliculas" => $peliculas,
            "page_css" => "/css/inicio.css",
        ]);
    }

    public function peliculas(Request $request, Response $response, $args)
    {
        try {
            $db = Database::getConnection();

            $stmt = $db->prepare("CALL listarPeliculas()");
            $stmt->execute();
            $peliculas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return View::render($response, "peliculas/lista", [
                "peliculas" => $peliculas,
                "page_css" => "/css/peliculas.css",
            ]);
        } catch (\Exception $e) {
            error_log("Error loading movies: " . $e->getMessage());
            return View::render($response, "error", [
                "title" => "Error",
                "mensaje" => "No se pudieron cargar las películas. Por favor, intenta más tarde.",
            ]);
        }
    }

    public function peliculaDetalle(Request $request, Response $response, $args)
    {
        $id = $args['id'] ?? null;

        if (!$id) {
            // Redirigir o mostrar error si no hay ID
            $response = $response->withStatus(404);
            return View::render($response, "error", [
                "title" => "Película no encontrada",
                "mensaje" => "La película solicitada no existe."
            ]);
        }

        try {
            $db = Database::getConnection();

            // Obtener detalles de la película específica
            // Nota: usando el mismo formato que listarPeliculas para consistencia
            $stmt = $db->prepare("SELECT id_pelicula AS pelicula_id, nombre, duracion FROM pelicula WHERE id_pelicula = ?");
            $stmt->execute([$id]);
            $pelicula = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$pelicula) {
                $response = $response->withStatus(404);
                return View::render($response, "error", [
                    "title" => "Película no encontrada",
                    "mensaje" => "La película solicitada no existe."
                ]);
            }

            return View::render($response, "peliculas/detalle", [
                "title" => $pelicula['nombre'] . " - Cineplanet",
                "pelicula" => $pelicula,
                "page_css" => "/css/peliculas.css",
            ]);
        } catch (\Exception $e) {
            $response = $response->withStatus(500);
            return View::render($response, "error", [
                "title" => "Error Interno",
                "mensaje" => "Ocurrió un error al cargar la película."
            ]);
        }
    }

    public function cuenta(Request $request, Response $response, $args)
    {
        // Por ahora, simplemente mostrar una página de cuenta básica
        // En el futuro, aquí se podrían mostrar datos del usuario logueado

        return View::render($response, "cuenta", [
            "title" => "Mi Cuenta - Cineplanet",
            "page_css" => "/css/cuenta.css",
        ]);
    }

    public function mostrarLogin(Request $request, Response $response, $args)
    {
        return View::render($response, "auth/login", [
            "title" => "Iniciar Sesión - Cineplanet",
            "page_css" => "/css/login.css",
        ]);
    }

    public function procesarLogin(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $email = filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $password = $data['password'] ?? '';

        // Validación básica
        if (empty($email) || empty($password)) {
            return $response->withHeader('Location', '/login?error=missing_fields')->withStatus(302);
        }
        
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $response->withHeader('Location', '/login?error=invalid_email')->withStatus(302);
        }

        try {
            $db = Database::getConnection();

            // Get user by email first
            $stmt = $db->prepare("SELECT id_usuario, nombre, email, password_hash FROM usuarios WHERE email = ? AND activo = 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            // Verify password using password_verify
            if (!$user || !password_verify($password, $user['password_hash'] ?? '')) {
                // Invalid credentials
                return $response->withHeader('Location', '/login?error=invalid_credentials')->withStatus(302);
            }
            
            // Get user role
            $stmt = $db->prepare("SELECT r.nombre as rol_nombre FROM usuarios_roles ur JOIN roles r ON ur.id_rol = r.id_rol WHERE ur.id_usuario = ? LIMIT 1");
            $stmt->execute([$user['id_usuario']]);
            $roleData = $stmt->fetch(\PDO::FETCH_ASSOC);
            $user['rol_nombre'] = $roleData['rol_nombre'] ?? 'cliente';

            // Usuario autenticado correctamente
            $_SESSION['user_id'] = $user['id_usuario'];
            $_SESSION['user_nombre'] = $user['nombre'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_rol'] = $user['rol_nombre'];

            // Actualizar el último acceso
            $stmt = $db->prepare("UPDATE usuarios SET ultimo_acceso = NOW() WHERE id_usuario = ?");
            $stmt->execute([$user['id_usuario']]);

            // Redirigir según el rol del usuario
            if ($user['rol_nombre'] === 'admin') {
                return $response->withHeader('Location', '/admin')->withStatus(302);
            } else {
                return $response->withHeader('Location', '/')->withStatus(302);
            }
        } catch (\Exception $e) {
            // Log error and redirect with message
            error_log("Login error: " . $e->getMessage());
            return $response->withHeader('Location', '/login?error=system_error')->withStatus(302);
        }
    }

    public function mostrarRegister(Request $request, Response $response, $args)
    {
        return View::render($response, "auth/register", [
            "title" => "Registrarse - Cineplanet",
            "page_css" => "/css/register.css",
        ]);
    }

    public function procesarRegister(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $nombre = trim($data['nombre'] ?? '');
        $apellido = trim($data['apellido'] ?? '');
        $email = filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $password = $data['password'] ?? '';
        $confirm_password = $data['confirm_password'] ?? '';

        // Validación básica
        if (empty($nombre) || empty($email) || empty($password) || empty($confirm_password)) {
            return $response->withHeader('Location', '/register?error=missing_fields')->withStatus(302);
        }
        
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $response->withHeader('Location', '/register?error=invalid_email')->withStatus(302);
        }
        
        // Validate password strength
        if (strlen($password) < 8) {
            return $response->withHeader('Location', '/register?error=weak_password')->withStatus(302);
        }

        if ($password !== $confirm_password) {
            return $response->withHeader('Location', '/register?error=password_mismatch')->withStatus(302);
        }

        try {
            $db = Database::getConnection();

            // Verificar si el email ya existe
            $checkStmt = $db->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
            $checkStmt->execute([$email]);
            $existingUser = $checkStmt->fetch();

            if ($existingUser) {
                // El email ya está en uso
                return $response->withHeader('Location', '/register?error=email_exists')->withStatus(302);
            }

            // Hash the password before storing
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert the new user with hashed password
            $stmt = $db->prepare("INSERT INTO usuarios (nombre, apellido, email, password_hash, activo, fecha_registro) VALUES (?, ?, ?, ?, 1, NOW())");
            $stmt->execute([$nombre, $apellido, $email, $passwordHash]);

            // Obtener el ID del nuevo usuario
            $userId = $db->lastInsertId();
            
            // Assign default role (cliente)
            $stmt = $db->prepare("INSERT INTO usuarios_roles (id_usuario, id_rol) VALUES (?, (SELECT id_rol FROM roles WHERE nombre = 'cliente' LIMIT 1))");
            $stmt->execute([$userId]);

            // Iniciar sesión para el nuevo usuario
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_nombre'] = $nombre;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_rol'] = 'cliente'; // Default role

            // Redirigir a la página principal
            return $response->withHeader('Location', '/')->withStatus(302);
        } catch (\Exception $e) {
            // Log error and redirect with message
            error_log("Registration error: " . $e->getMessage());
            return $response->withHeader('Location', '/register?error=registration_failed')->withStatus(302);
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
}
