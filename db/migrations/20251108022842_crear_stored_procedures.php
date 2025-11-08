<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CrearStoredProcedures extends AbstractMigration
{
    public function up(): void
    {
        $sqlFile = __DIR__ . "/../../sql/migrations/002_procedures.sql";
        $sql = file_get_contents($sqlFile);

        if ($sql === false) {
            throw new \RuntimeException(
                "No se pudo leer el archivo SQL: $sqlFile",
            );
        }

        // Quitar y separar delimitadores y otros antes de llegar al PDO
        $sql = preg_replace('/^\s*DELIMITER\s+\/\/\s*$/mi', "", $sql);
        $sql = preg_replace('/^\s*DELIMITER\s*;\s*$/mi', "", $sql);
        $sql = preg_replace(
            "/END\s*\/\/\s*/mi",
            "END; -- @@SPLIT@@" . PHP_EOL,
            $sql,
        );
        $sql = preg_replace(
            '/^(DROP\s+PROCEDURE\s+IF\s+EXISTS\s+\w+\s*;)\s*$/mi',
            '$1 -- @@SPLIT@@',
            $sql,
        );

        $statements = array_filter(
            array_map("trim", explode("-- @@SPLIT@@", $sql)),
            fn($stmt) => $stmt !== "",
        );

        foreach ($statements as $statement) {
            $this->execute($statement);
        }
    }

    public function down(): void
    {
        $this->execute("DROP FUNCTION IF EXISTS buscarCiudadId");
        $this->execute("DROP FUNCTION IF EXISTS buscarCineId");
        $this->execute("DROP PROCEDURE IF EXISTS insertarCiudad");
        $this->execute("DROP PROCEDURE IF EXISTS insertarCine");
        $this->execute("DROP PROCEDURE IF EXISTS insertarSala");
        $this->execute("DROP PROCEDURE IF EXISTS insertarFormato");
        $this->execute("DROP PROCEDURE IF EXISTS insertarPelicula");
        $this->execute("DROP PROCEDURE IF EXISTS insertarFuncion");
        $this->execute("DROP PROCEDURE IF EXISTS insertarHorario");
        $this->execute("DROP PROCEDURE IF EXISTS insertarEstado");
        $this->execute("DROP PROCEDURE IF EXISTS insertarPeliculaFormato");
    }
}
