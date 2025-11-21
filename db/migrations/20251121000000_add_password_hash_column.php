<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddPasswordHashColumn extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('usuarios');
        
        // Add password_hash column if it doesn't exist
        if (!$table->hasColumn('password_hash')) {
            $table->addColumn('password_hash', 'string', [
                'limit' => 255,
                'null' => false,
                'default' => '',
                'comment' => 'Hashed password using PHP password_hash()',
            ])
            ->update();
        }
        
        // Note: If you have existing users with plain text passwords,
        // you'll need to either:
        // 1. Have them reset their passwords
        // 2. Create a separate script to hash existing passwords
        // 3. Force password reset on first login
    }

    public function down(): void
    {
        $table = $this->table('usuarios');
        
        if ($table->hasColumn('password_hash')) {
            $table->removeColumn('password_hash')
                ->update();
        }
    }
}
