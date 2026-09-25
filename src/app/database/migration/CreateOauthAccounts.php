<?php

namespace app\database\migration;

use Gatovel\Database\Database;
use Gatovel\Database\migration\Migration;

class CreateOauthAccounts extends Migration
{
    public function up(): void
    {
        Database::schema()->create(
            'oauth_accounts',
            [
                'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
                'user_id' => 'INT NOT NULL',
                'provider' => 'VARCHAR(50) NOT NULL',
                'provider_user_id' => 'VARCHAR(255) NOT NULL',
                'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
                'updated_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
                'UNIQUE KEY unique_oauth_provider_user' =>
                    '(provider, provider_user_id)',
            ]
        );
    }

    public function down(): void
    {
        Database::schema()->drop(
            'oauth_accounts'
        );
    }
}