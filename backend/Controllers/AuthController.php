<?php

namespace Cineplanet\App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PDO;

class AuthController
{
    private $pdo;

    /**
     * Constructor del controlador de autenticación.
     *
     * @param PDO $pdo Conexión PDO a la base de datos.
     * @see https://www.php.net/manual/es/class.pdo.php - Documentación oficial de PDO
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Iniciar sesión de usuario.
     *
     * @see https://www.php.net/manual/es/function.password-verify.php - Verificación de contraseñas
     * @see https://www.php.net/manual/es/function.session-start.php - Manejo de sesiones en PHP
     * @see https://www.php.net/manual/es/function.json-decode.php - Decodificación JSON
     * @see https://www.php.net/manual/es/function.session-destroy.php - Destruir sesión
     * @see https://www.php.net/manual/es/function.header.php - Manejo de cabeceras HTTP
     * @see https://www.php.net/manual/es/function.date.php - Fecha y hora
     * @see https://www.php.net/manual/es/function.password-hash.php - Hash de contraseñas
     * @see https://www.php.net/manual/es/function.password-verify.php - Verificar hash de contraseña
     * @see https://www.slimframework.com/docs/v4/objects/request.html - Slim PSR-7 Request
     * @see https://www.slimframework.com/docs/v4/objects/response.html - Slim PSR-7 Response
     *
     * @param Request $request Solicitud PSR-7
     * @param Response $response Respuesta PSR-7
     * @return Response
     */
    public function login(Request $request, Response $response)
    {
        $data = json_decode($request->getBody(), true);

        $email = $data["email"] ?? "";
        $password = $data["password"] ?? "";

        if (empty($email) || empty($password)) {
            $response->getBody()->write(
                json_encode([
                    "success" => false,
                    "message" => "El correo y la contraseña son obligatorios",
                ]),
            );
            return $response
                ->withHeader("Content-Type", "application/json")
                ->withStatus(400);
        }

        try {
            $stmt = $this->pdo->prepare(
                "SELECT id_usuario, nombre, apellido, email, password, id_rol, estado FROM usuarios WHERE email = :email",
            );
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($user && $user["estado"] === "activo") {
                // Verifica la contraseña usando password_verify
                // Documentación: https://www.php.net/manual/es/function.password-verify.php
                if (password_verify($password, $user["password"])) {
                    $updateStmt = $this->pdo->prepare(
                        "CALL actualizarUltimoAcceso(:id_usuario)",
                    );
                    $updateStmt->bindParam(":id_usuario", $user["id_usuario"]);
                    $updateStmt->execute();

                    $roleStmt = $this->pdo->prepare(
                        "SELECT nombre AS rol_nombre FROM roles WHERE id_rol = :id_rol",
                    );
                    $roleStmt->bindParam(":id_rol", $user["id_rol"]);
                    $roleStmt->execute();
                    $role = $roleStmt->fetch(\PDO::FETCH_ASSOC);

                    $user["rol_nombre"] = $role["rol_nombre"];

                    unset($user["password"]);

                    // Guardar datos de sesión
                    $_SESSION["user_id"] = $user["id_usuario"];
                    $_SESSION["user_data"] = $user;

                    $response->getBody()->write(
                        json_encode([
                            "success" => true,
                            "user" => $user,
                        ]),
                    );

                    return $response->withHeader(
                        "Content-Type",
                        "application/json",
                    );
                }
            }

            $response->getBody()->write(
                json_encode([
                    "success" => false,
                    "message" => "Credenciales inválidas",
                ]),
            );

            return $response
                ->withHeader("Content-Type", "application/json")
                ->withStatus(401);
        } catch (\PDOException $e) {
            $response->getBody()->write(
                json_encode([
                    "success" => false,
                    "message" =>
                        "Error en la base de datos: " . $e->getMessage(),
                ]),
            );

            return $response
                ->withHeader("Content-Type", "application/json")
                ->withStatus(500);
        }
    }

    /**
     * Registrar un nuevo usuario.
     *
     * @see https://www.php.net/manual/es/function.password-hash.php - Hash de contraseñas
     * @see https://www.php.net/manual/es/function.json-decode.php - Decodificación JSON
     * @see https://www.php.net/manual/es/function.header.php - Manejo de cabeceras HTTP
     * @see https://www.php.net/manual/es/function.session-start.php - Manejo de sesiones en PHP
     * @see https://www.slimframework.com/docs/v4/objects/request.html - Slim PSR-7 Request
     * @see https://www.slimframework.com/docs/v4/objects/response.html - Slim PSR-7 Response
     *
     * @param Request $request Solicitud PSR-7
     * @param Response $response Respuesta PSR-7
     * @return Response
     */
    public function register(Request $request, Response $response)
    {
        $data = json_decode($request->getBody(), true);

        $nombre = $data["nombre"] ?? "";
        $apellido = $data["apellido"] ?? "";
        $email = $data["email"] ?? "";
        $password = $data["password"] ?? "";
        $id_rol = $data["id_rol"] ?? 1;

        if (empty($nombre) || empty($email) || empty($password)) {
            $response->getBody()->write(
                json_encode([
                    "success" => false,
                    "message" => "Nombre, correo y contraseña son obligatorios",
                ]),
            );
            return $response
                ->withHeader("Content-Type", "application/json")
                ->withStatus(400);
        }

        $checkStmt = $this->pdo->prepare(
            "SELECT id_usuario FROM usuarios WHERE email = :email",
        );
        $checkStmt->bindParam(":email", $email);
        $checkStmt->execute();

        if ($checkStmt->fetch(\PDO::FETCH_ASSOC)) {
            $response->getBody()->write(
                json_encode([
                    "success" => false,
                    "message" => "Ya existe un usuario con este correo",
                ]),
            );
            return $response
                ->withHeader("Content-Type", "application/json")
                ->withStatus(409);
        }

        // Hashea la contraseña antes de guardar
        // Documentación: https://www.php.net/manual/es/function.password-hash.php
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $this->pdo->prepare(
                "CALL registrarUsuario(:nombre, :apellido, :email, :password, :id_rol)",
            );
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":apellido", $apellido);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $hashedPassword);
            $stmt->bindParam(":id_rol", $id_rol);
            $stmt->execute();

            $response->getBody()->write(
                json_encode([
                    "success" => true,
                    "message" => "Usuario registrado exitosamente",
                ]),
            );

            return $response->withHeader("Content-Type", "application/json");
        } catch (\PDOException $e) {
            $response->getBody()->write(
                json_encode([
                    "success" => false,
                    "message" =>
                        "Error en la base de datos: " . $e->getMessage(),
                ]),
            );

            return $response
                ->withHeader("Content-Type", "application/json")
                ->withStatus(500);
        }
    }

    /**
     * Cerrar sesión del usuario.
     *
     * @see https://www.php.net/manual/es/function.session-destroy.php - Destruir sesión
     * @see https://www.php.net/manual/es/function.session-start.php - Iniciar sesión
     * @see https://www.slimframework.com/docs/v4/objects/response.html - Slim PSR-7 Response
     *
     * @param Request $request Solicitud PSR-7
     * @param Response $response Respuesta PSR-7
     * @return Response
     */
    public function logout(Request $request, Response $response)
    {
        // Destruir la sesión
        // https://www.php.net/manual/es/function.session-destroy.php
        session_destroy();

        // Regenerar el ID de sesión por seguridad
        session_start();

        $response->getBody()->write(
            json_encode([
                "success" => true,
                "message" => "Sesión cerrada correctamente",
            ]),
        );

        return $response->withHeader("Content-Type", "application/json");
    }
}
