<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ProceduresEliminacion extends AbstractMigration
{
    public function up(): void
    {
        $sqlFile = __DIR__ . "/../../sql/migrations/007_procedures_eliminacion.sql";
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
        $this->execute("DROP PROCEDURE IF EXISTS eliminarCiudad;");
        $this->execute("DROP PROCEDURE IF EXISTS eliminarCine;");
        $this->execute("DROP PROCEDURE IF EXISTS eliminarFormato;");
        $this->execute("DROP PROCEDURE IF EXISTS eliminarPelicula;");
        $this->execute("DROP PROCEDURE IF EXISTS eliminarHorario;");
        $this->execute("DROP PROCEDURE IF EXISTS eliminarEstado;");
        $this->execute("DROP PROCEDURE IF EXISTS eliminarSala;");
        $this->execute("DROP PROCEDURE IF EXISTS eliminarPeliculaFormato;");
        $this->execute("DROP PROCEDURE IF EXISTS eliminarFuncion;");
        $this->execute("DROP PROCEDURE IF EXISTS eliminarAsiento;");
        $this->execute("DROP PROCEDURE IF EXISTS eliminarDulceria;");
        $this->execute("DROP PROCEDURE IF EXISTS eliminarCategoria;");
        $this->execute("DROP PROCEDURE IF EXISTS eliminarDulceriaCategoria;");
        $this->execute("DROP PROCEDURE IF EXISTS eliminarDulce;");
        $this->execute("DROP PROCEDURE IF EXISTS eliminarDulceriaTicket;");
        $this->execute("DROP PROCEDURE IF EXISTS eliminarCompraDulceria;");
        $this->execute("DROP PROCEDURE IF EXISTS eliminarCineTicket;");
        $this->execute("DROP PROCEDURE IF EXISTS eliminarCompraCine;");
        $this->execute("DROP PROCEDURE IF EXISTS eliminarCliente;");
        $this->execute("DROP PROCEDURE IF EXISTS eliminarBoleta;");
        $this->execute("DROP PROCEDURE IF EXISTS eliminarEmpleado;");
        $this->execute("DROP PROCEDURE IF EXISTS eliminarAdmin;");
    }
}
