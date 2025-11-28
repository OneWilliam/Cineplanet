<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AgregarProceduresListado extends AbstractMigration
{
    public function up(): void
    {
        $sqlFile = __DIR__ . "/../../sql/migrations/003_procedures_listado.sql";
        $sql = file_get_contents($sqlFile);
        if ($sql === false) {
            throw new \RuntimeException(
                "No se pudo leer el archivo SQL: $sqlFile",
            );
        }
        // Use centralized SQL parser helper to split statements and handle DELIMITER blocks
        require_once __DIR__ . "/helpers/SqlMigrationHelper.php";
        $statements = SqlMigrationHelper::splitSqlFile($sqlFile);
        foreach ($statements as $statement) {
            $this->execute($statement);
        }
    }

    public function down(): void
    {
        $this->execute("DROP PROCEDURE IF EXISTS listarPeliculas");
    }
}
