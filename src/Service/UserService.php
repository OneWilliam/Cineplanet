<?php

namespace Cineplanet\App\Service;

use Cineplanet\App\Repository\UserRepository;

class UserService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function authenticateUser(string $email, string $password): ?array
    {
        try {
            $user = $this->userRepository->authenticateUser($email, $password);

            if ($user) {
                // Actualizar el último acceso
                $this->userRepository->updateUserLastAccess($user['id_usuario']);
            }

            return $user;
        } catch (\Exception $e) {
            error_log("Authentication error: " . $e->getMessage());
            return null;
        }
    }

    public function registerUser(string $nombre, string $apellido, string $email, string $password): bool
    {
        try {
            return $this->userRepository->registerUser($nombre, $apellido, $email, $password);
        } catch (\Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            return false;
        }
    }

    public function getUserById(int $userId): ?array
    {
        try {
            return $this->userRepository->getUserById($userId);
        } catch (\Exception $e) {
            error_log("Get user error: " . $e->getMessage());
            return null;
        }
    }

    public function isAdmin(): bool
    {
        return isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'admin';
    }

    public function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public function logout(): void
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
    }
}