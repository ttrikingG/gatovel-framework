<?php

namespace Gatovel\Database\migration;

use Gatovel\Database\Database;
use Gatovel\Database\migration\Migration;

class AddEmailVerification extends Migration
{
    public function up(): void
    {
        Database::schema()->addColumn(
            'users',
            'email_verified_at',
            'TIMESTAMP NULL DEFAULT NULL'
        );

        Database::schema()->create(
            'email_verifications',
            [
                'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
                'user_id' => 'INT NOT NULL',
                'token_hash' => 'VARCHAR(255) NOT NULL',
                'expires_at' => 'TIMESTAMP NOT NULL',
                'used_at' => 'TIMESTAMP NULL DEFAULT NULL',
                'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            ]
        );
    }

    public function down(): void
    {
        Database::schema()->drop(
            'email_verifications'
        );
    }
}