<?php

namespace Cineplanet\App\Repository;

use Cineplanet\App\Database;

class MovieRepository
{
    public function getAllMovies(): array
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("CALL listarPeliculas()");
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getMovieById(int $id): ?array
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT id_pelicula AS pelicula_id, nombre, duracion FROM pelicula WHERE id_pelicula = ?");
            $stmt->execute([$id]);
            $movie = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $movie ?: null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function searchMovies(string $searchTerm): array
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT id_pelicula AS pelicula_id, nombre, duracion FROM pelicula WHERE nombre LIKE ?");
            $stmt->execute(["%{$searchTerm}%"]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getTotalCount(): int
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT COUNT(*) as total FROM pelicula");
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return (int)($result['total'] ?? 0);
        } catch (\Exception $e) {
            return 0;
        }
    }
}