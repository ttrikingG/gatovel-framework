<?php

namespace Gatovel\Database\migration;

use Gatovel\Database\Database;
use Gatovel\Database\migration\Migration;

class CreatePermissionsTable extends Migration
{
    public function up(): void
    {
        Database::schema()->create(
            'permissions',
            [
                'id' => 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'name' => 'VARCHAR(150) NOT NULL',
            ]
        );
    }

    public function down(): void
    {
        Database::schema()->drop('permissions');
    }
}
