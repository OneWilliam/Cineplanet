<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CrearTablaUsuariosRoles extends AbstractMigration
{
    public function up(): void
    {
        $sqlFile = __DIR__ . "/../../sql/migrations/004_estructura_usuarios.sql";
        $sql = file_get_contents($sqlFile);

        if ($sql === false) {
            throw new \RuntimeException(
                "No se pudo leer el archivo SQL: $sqlFile",
            );
        }

        // Split by semicolon for regular SQL statements (no DELIMITER in this file, but just in case)
        $statements = array_filter(
            array_map("trim", explode(";", $sql)),
            fn($stmt) => $stmt !== "",
        );

        foreach ($statements as $statement) {
            if (trim($statement) !== '') {
                $this->execute($statement . ";");
            }
        }
    }

    public function down(): void
    {
        $this->execute("DROP TABLE IF EXISTS usuarios, roles");
    }
}