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

        $statements = array_filter(
            array_map('trim', explode(';', $sql)),
            fn($stmt) => !empty($stmt)
        );

        foreach ($statements as $statement) {
            $this->execute($statement);
        }
    }
}
