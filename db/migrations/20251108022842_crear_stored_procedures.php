<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CrearStoredProcedures extends AbstractMigration
{
    public function up(): void
    {
        $sqlFile = __DIR__ . "/../../sql/migrations/002_procedures.sql";

        // Use centralized SQL parser helper to split statements and handle DELIMITER blocks
        require_once __DIR__ . "/helpers/SqlMigrationHelper.php";

        $statements = SqlMigrationHelper::splitSqlFile($sqlFile);

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
