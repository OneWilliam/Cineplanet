<?php
// src/Controllers/AuthController.php

namespace Cineplanet\App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PDO;

class AuthController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function login(Request $request, Response $response)
    {
        $data = json_decode($request->getBody(), true);

        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        if (empty($email) || empty($password)) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Email and password are required'
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }

        try {
            // First, get user data to check credentials
            $stmt = $this->pdo->prepare("SELECT id_usuario, nombre, apellido, email, password, id_rol, estado FROM usuarios WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($user && $user['estado'] === 'activo') {
                // Verify the password
                if (password_verify($password, $user['password'])) {
                    // Update last access time
                    $updateStmt = $this->pdo->prepare("CALL actualizarUltimoAcceso(:id_usuario)");
                    $updateStmt->bindParam(':id_usuario', $user['id_usuario']);
                    $updateStmt->execute();

                    // Get role info
                    $roleStmt = $this->pdo->prepare("SELECT nombre AS rol_nombre FROM roles WHERE id_rol = :id_rol");
                    $roleStmt->bindParam(':id_rol', $user['id_rol']);
                    $roleStmt->execute();
                    $role = $roleStmt->fetch(\PDO::FETCH_ASSOC);

                    $user['rol_nombre'] = $role['rol_nombre'];

                    // Remove password from response
                    unset($user['password']);

                    $response->getBody()->write(json_encode([
                        'success' => true,
                        'user' => $user
                    ]));

                    return $response
                        ->withHeader('Content-Type', 'application/json');
                }
            }

            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Invalid credentials'
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        } catch (\PDOException $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }

    public function register(Request $request, Response $response)
    {
        $data = json_decode($request->getBody(), true);

        $nombre = $data['nombre'] ?? '';
        $apellido = $data['apellido'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $id_rol = $data['id_rol'] ?? 1; // Default to 'cliente' role

        if (empty($nombre) || empty($email) || empty($password)) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Name, email, and password are required'
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }

        // Check if user already exists
        $checkStmt = $this->pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = :email");
        $checkStmt->bindParam(':email', $email);
        $checkStmt->execute();

        if ($checkStmt->fetch(\PDO::FETCH_ASSOC)) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'User with this email already exists'
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(409);
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Call the stored procedure to register user
            $stmt = $this->pdo->prepare("CALL registrarUsuario(:nombre, :apellido, :email, :password, :id_rol)");
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword); // Store hashed password
            $stmt->bindParam(':id_rol', $id_rol);
            $stmt->execute();

            $response->getBody()->write(json_encode([
                'success' => true,
                'message' => 'User registered successfully'
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json');
        } catch (\PDOException $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }
}