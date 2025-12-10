<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddMovieFields extends AbstractMigration
{
    public function up(): void
    {
        $sqlFile = __DIR__ . "/../../sql/migrations/006_add_movie_fields.sql";
        $sql = file_get_contents($sqlFile);

        if ($sql === false) {
            throw new \RuntimeException(
                "No se pudo leer el archivo SQL: $sqlFile",
            );
        }

        $this->execute($sql);
    }

    public function down(): void
    {
        $this->execute("ALTER TABLE pelicula DROP COLUMN director, DROP COLUMN duracion, DROP COLUMN clasificacion, DROP COLUMN descripcion, DROP COLUMN imagen;");
    }
}
