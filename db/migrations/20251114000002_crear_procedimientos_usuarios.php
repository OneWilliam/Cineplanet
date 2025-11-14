<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CrearProcedimientosUsuarios extends AbstractMigration
{
    public function up(): void
    {
        $sqlFile = __DIR__ . "/../../sql/migrations/005_procedures_usuarios.sql";
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