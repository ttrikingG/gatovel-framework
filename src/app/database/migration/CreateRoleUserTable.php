<?php

namespace Gatovel\Database\migration;

use Gatovel\Database\Database;
use Gatovel\Database\migration\Migration;

class CreateRoleUserTable extends Migration
{
    public function up(): void
    {
        Database::schema()->create(
            'role_user',
            [
                'user_id' => 'INT NOT NULL',
                'role_id' => 'INT NOT NULL',
                'PRIMARY KEY' => '(user_id, role_id)',
            ]
        );
    }

    public function down(): void
    {
        Database::schema()->drop('role_user');
    }
}
