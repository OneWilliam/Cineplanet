<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CrearProcedimientosUsuarios extends AbstractMigration
{
    public function up(): void
    {
        $sqlFile =
            __DIR__ . "/../../sql/migrations/005_procedures_usuarios.sql";
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
        $this->execute("DROP PROCEDURE IF EXISTS registrarUsuario");
        $this->execute("DROP PROCEDURE IF EXISTS autenticarUsuario");
        $this->execute("DROP PROCEDURE IF EXISTS obtenerUsuarioPorId");
        $this->execute("DROP PROCEDURE IF EXISTS actualizarUltimoAcceso");
        $this->execute("DROP PROCEDURE IF EXISTS listarUsuarios");
        $this->execute("DROP PROCEDURE IF EXISTS actualizarRolUsuario");
        $this->execute("DROP PROCEDURE IF EXISTS desactivarUsuario");
        $this->execute("DROP PROCEDURE IF EXISTS activarUsuario");
    }
}
