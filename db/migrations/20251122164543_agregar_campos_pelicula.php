<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AgregarCamposPelicula extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('pelicula');
        
        // Verificar si las columnas ya existen antes de agregarlas
        if (!$table->hasColumn('imagen')) {
            $table->addColumn('imagen', 'string', ['limit' => 255, 'null' => true, 'default' => 'default.jpg'])
                  ->update();
        }
        
        if (!$table->hasColumn('descripcion')) {
            $table->addColumn('descripcion', 'text', ['null' => true])
                  ->update();
        }
        
        if (!$table->hasColumn('clasificacion')) {
            $table->addColumn('clasificacion', 'string', ['limit' => 10, 'null' => true, 'default' => 'ATP'])
                  ->update();
        }
    }
}
