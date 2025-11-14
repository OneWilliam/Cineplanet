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

            $hxRequest = $request->getHeaderLine("HX-Request");
            if ($hxRequest === "true") {
                return View::renderPartial($response, "peliculas/lista", [
                    "peliculas" => $peliculas,
                    "page_css" => "/css/peliculas.css",
                ]);
            } else {
                return View::render($response, "peliculas/lista", [
                    "peliculas" => $peliculas,
                    "page_css" => "/css/peliculas.css",
                ]);
            }
        } catch (\Exception $e) {
            $html =
                '<div class="alert alert-danger">Error: ' .
                htmlspecialchars($e->getMessage()) .
                "</div>";
            $response->getBody()->write($html);
            return $response->withStatus(500);
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
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        // Validación básica
        if (empty($email) || empty($password)) {
            // Aquí debería redirigir con un mensaje de error
            return $response->withHeader('Location', '/cuenta')->withStatus(302);
        }

        try {
            $db = Database::getConnection();

            // Hash the password for comparison (in a real system, passwords should be hashed)
            // For now, we'll store plain text passwords in the seed for testing
            $stmt = $db->prepare("CALL autenticarUsuario(?, ?)");
            $stmt->execute([$email, $password]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($user) {
                // Usuario autenticado correctamente
                $_SESSION['user_id'] = $user['id_usuario'];
                $_SESSION['user_nombre'] = $user['nombre'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_rol'] = $user['rol_nombre'];

                // Actualizar el último acceso
                $stmt = $db->prepare("CALL actualizarUltimoAcceso(?)");
                $stmt->execute([$user['id_usuario']]);

                // Redirigir según el rol del usuario
                if ($user['rol_nombre'] === 'admin') {
                    return $response->withHeader('Location', '/admin')->withStatus(302);
                } else {
                    return $response->withHeader('Location', '/')->withStatus(302);
                }
            } else {
                // Credenciales incorrectas
                // Aquí debería redirigir con un mensaje de error
                return $response->withHeader('Location', '/cuenta')->withStatus(302);
            }
        } catch (\Exception $e) {
            // En caso de error, redirigir con mensaje
            return $response->withHeader('Location', '/cuenta')->withStatus(302);
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
        $nombre = $data['nombre'] ?? '';
        $apellido = $data['apellido'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $confirm_password = $data['confirm_password'] ?? '';

        // Validación básica
        if (empty($nombre) || empty($email) || empty($password) || empty($confirm_password)) {
            // Aquí debería redirigir con un mensaje de error
            return $response->withHeader('Location', '/register')->withStatus(302);
        }

        if ($password !== $confirm_password) {
            // Las contraseñas no coinciden
            return $response->withHeader('Location', '/register')->withStatus(302);
        }

        try {
            $db = Database::getConnection();

            // Verificar si el email ya existe
            $checkStmt = $db->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
            $checkStmt->execute([$email]);
            $existingUser = $checkStmt->fetch();

            if ($existingUser) {
                // El email ya está en uso
                return $response->withHeader('Location', '/register')->withStatus(302);
            }

            // Insertar el nuevo usuario (en un sistema real, el password debería estar hasheado)
            $stmt = $db->prepare("CALL registrarUsuario(?, ?, ?, ?, 1)"); // 1 = cliente role
            $stmt->execute([$nombre, $apellido, $email, $password]);

            // Obtener el ID del nuevo usuario
            $userId = $db->lastInsertId();

            // Iniciar sesión para el nuevo usuario
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_nombre'] = $nombre;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_rol'] = 'cliente'; // Default role

            // Redirigir a la página principal
            return $response->withHeader('Location', '/')->withStatus(302);
        } catch (\Exception $e) {
            // En caso de error, redirigir con mensaje
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
}
