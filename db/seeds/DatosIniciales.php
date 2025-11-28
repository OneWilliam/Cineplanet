<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class DatosIniciales extends AbstractSeed
{
    public function run(): void
    {
        $sqlFile = __DIR__ . "/../../sql/seeds/001_datos_iniciales.sql";
        $sql = file_get_contents($sqlFile);

        if ($sql === false) {
            throw new \RuntimeException(
                "No se pudo leer el archivo SQL: $sqlFile",
            );
        }

        // Use centralized SQL parser helper to split statements and handle optional DELIMITER blocks
        require_once __DIR__ . "/../migrations/helpers/SqlMigrationHelper.php";
        $statements = SqlMigrationHelper::splitSql($sql);

        foreach ($statements as $statement) {
            $this->execute($statement);
        }
    }
}
