<?php

namespace app\database\migration;

use Gatovel\Database\Database;
use Gatovel\Database\migration\Migration;

class CreateUserMfa extends Migration
{
    public function up(): void
    {
        Database::schema()->create(
            'user_mfa',
            [
                'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
                'user_id' => 'INT NOT NULL',
                'secret' => 'VARCHAR(255) NOT NULL',
                'enabled' => 'TINYINT(1) NOT NULL DEFAULT 0',
                'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
                'updated_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
                'UNIQUE KEY unique_user_mfa' =>
                    '(user_id)',
            ]
        );
    }

    public function down(): void
    {
        Database::schema()->drop(
            'user_mfa'
        );
    }
}