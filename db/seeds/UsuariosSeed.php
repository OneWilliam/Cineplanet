<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

final class UsuariosSeed extends AbstractSeed
{
    public function run(): void
    {
        $sqlFile = __DIR__ . "/../../sql/seeds/002_usuarios_seeds.sql";
        $sql = file_get_contents($sqlFile);

        if ($sql === false) {
            throw new \RuntimeException(
                "No se pudo leer el archivo SQL: $sqlFile",
            );
        }

        // Hashear contraseñas en los CALL registrarUsuario antes de ejecutar
        $sql = preg_replace_callback(
            "/CALL registrarUsuario\\s*\\(([^)]+)\\)/i",
            function ($matches) {
                $params = array_map("trim", explode(",", $matches[1]));
                // El cuarto parámetro es la contraseña (índice 3)
                $plainPassword = trim($params[3], " '\"");
                $hashedPassword = password_hash(
                    $plainPassword,
                    PASSWORD_DEFAULT,
                );
                // Reemplaza la contraseña en el array de parámetros
                $params[3] = "'" . $hashedPassword . "'";
                return "CALL registrarUsuario(" . implode(", ", $params) . ")";
            },
            $sql,
        );

        // Use centralized SQL parser helper to split statements and handle optional DELIMITER blocks
        require_once __DIR__ . "/../migrations/helpers/SqlMigrationHelper.php";
        $statements = SqlMigrationHelper::splitSql($sql);

        foreach ($statements as $statement) {
            if (trim($statement) !== "") {
                $this->execute($statement);
            }
        }
    }
}
