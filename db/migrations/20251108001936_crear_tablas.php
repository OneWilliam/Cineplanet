<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CrearTablas extends AbstractMigration
{
    public function up(): void
    {
        $sqlFile = __DIR__ . "/../../sql/estructura_tablas.sql";
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
        $this->execute("SET foreign_key_checks = 0;");
        $this->execute("DROP TABLE IF EXISTS boleta;");
        $this->execute("DROP TABLE IF EXISTS compracine;");
        $this->execute("DROP TABLE IF EXISTS compradulceria;");
        $this->execute("DROP TABLE IF EXISTS asiento;");
        $this->execute("DROP TABLE IF EXISTS funcion;");
        $this->execute("DROP TABLE IF EXISTS dulceriaticket;");
        $this->execute("DROP TABLE IF EXISTS cineticket;");
        $this->execute("DROP TABLE IF EXISTS dulce;");
        $this->execute("DROP TABLE IF EXISTS dulceriacategoria;");
        $this->execute("DROP TABLE IF EXISTS dulceria;");
        $this->execute("DROP TABLE IF EXISTS peliculaformato;");
        $this->execute("DROP TABLE IF EXISTS sala;");
        $this->execute("DROP TABLE IF EXISTS cliente;");

        $this->execute("DROP TABLE IF EXISTS pelicula;");
        $this->execute("DROP TABLE IF EXISTS horario;");
        $this->execute("DROP TABLE IF EXISTS estado;");
        $this->execute("DROP TABLE IF EXISTS categoria;");
        $this->execute("DROP TABLE IF EXISTS cine;");
        $this->execute("DROP TABLE IF EXISTS formato;");
        $this->execute("DROP TABLE IF EXISTS ciudad;");

        $this->execute("SET foreign_key_checks = 1;");
    }
}
