<?php

namespace Gatovel\Database\migration;

use Gatovel\Database\Database;
use Gatovel\Database\migration\Migration;

class CreateRolesTable extends Migration
{
    public function up(): void
    {
        Database::schema()->create(
            'roles',
            [
                'id' => 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'name' => 'VARCHAR(100) NOT NULL',
            ]
        );
    }

    public function down(): void
    {
        Database::schema()->drop('roles');
    }
}
