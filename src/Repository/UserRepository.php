<?php

namespace Cineplanet\App\Repository;

use Cineplanet\App\Database;

class UserRepository
{
    public function getUserById(int $userId): ?array
    {
        try {
            $sql = "SELECT id_usuario, nombre, apellido, email, rol_nombre FROM usuarios WHERE id_usuario = ?";
            return Database::executeQuerySingle($sql, [$userId]);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function authenticateUser(string $email, string $password): ?array
    {
        try {
            return Database::executeProcedureSingle("autenticarUsuario", [$email, $password]);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function registerUser(string $nombre, string $apellido, string $email, string $password): bool
    {
        try {
            // Check if user exists first
            $existingUser = Database::executeQuery("SELECT id_usuario FROM usuarios WHERE email = ?", [$email]);
            if (!empty($existingUser)) {
                return false; // User already exists
            }
            
            Database::executeProcedure("registrarUsuario", [$nombre, $apellido, $email, $password, 1]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function updateUserLastAccess(int $userId): bool
    {
        try {
            Database::executeProcedure("actualizarUltimoAcceso", [$userId]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getAllUsers(): array
    {
        try {
            return Database::executeProcedure("listarUsuarios");
        } catch (\Exception $e) {
            return [];
        }
    }
}