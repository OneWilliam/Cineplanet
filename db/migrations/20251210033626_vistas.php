<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Vistas extends AbstractMigration
{
    public function up(): void
    {
        $sqlFile = __DIR__ . "/../../sql/migrations/008_vistas.sql";
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
        $this->execute("DROP VIEW IF EXISTS vistaciudad;");
        $this->execute("DROP VIEW IF EXISTS vistacine;");
        $this->execute("DROP VIEW IF EXISTS vistaformato;");
        $this->execute("DROP VIEW IF EXISTS vistapelicula;");
        $this->execute("DROP VIEW IF EXISTS vistahorario;");
        $this->execute("DROP VIEW IF EXISTS vistaestado;");
        $this->execute("DROP VIEW IF EXISTS vistasala;");
        $this->execute("DROP VIEW IF EXISTS vistapeliculaformato;");
        $this->execute("DROP VIEW IF EXISTS vistafuncion;");
        $this->execute("DROP VIEW IF EXISTS vistaasiento;");
        $this->execute("DROP VIEW IF EXISTS vistadulceria;");
        $this->execute("DROP VIEW IF EXISTS vistacategoria;");
        $this->execute("DROP VIEW IF EXISTS vistadulceriacategoria;");
        $this->execute("DROP VIEW IF EXISTS vistadulce;");
        $this->execute("DROP VIEW IF EXISTS vistadulceriaticket;");
        $this->execute("DROP VIEW IF EXISTS vistacompradulceria;");
        $this->execute("DROP VIEW IF EXISTS vistacineticket;");
        $this->execute("DROP VIEW IF EXISTS vistacompracine;");
        $this->execute("DROP VIEW IF EXISTS vistacliente;");
        $this->execute("DROP VIEW IF EXISTS vistaboleta;");
        $this->execute("DROP VIEW IF EXISTS vistaempleado;");
        $this->execute("DROP VIEW IF EXISTS vistaadmin;");
    }
}
